import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import axios from 'axios';
import { useState, DragEvent } from 'react';

type InitiativeStatus = 'draft' | 'active' | 'on_hold' | 'completed';

interface Initiative {
    id: number;
    title: string;
    status: InitiativeStatus;
    due_date: string | null;
    owner: { name: string } | null;
}

interface Workspace {
    id: number;
    name: string;
}

interface Props {
    workspace: Workspace;
    initiatives: Initiative[];
}

interface Column {
    id: InitiativeStatus;
    label: string;
    color: string;
    headerColor: string;
}

const COLUMNS: Column[] = [
    { id: 'draft', label: 'Backlog', color: 'bg-gray-50', headerColor: 'bg-gray-200 text-gray-700' },
    { id: 'active', label: 'In Progress', color: 'bg-blue-50', headerColor: 'bg-blue-200 text-blue-800' },
    { id: 'on_hold', label: 'Review', color: 'bg-yellow-50', headerColor: 'bg-yellow-200 text-yellow-800' },
    { id: 'completed', label: 'Done', color: 'bg-green-50', headerColor: 'bg-green-200 text-green-800' },
];

export default function Kanban({ workspace, initiatives: initialInitiatives }: Props) {
    const [initiatives, setInitiatives] = useState<Initiative[]>(initialInitiatives);
    const [draggingId, setDraggingId] = useState<number | null>(null);
    const [dragOverColumn, setDragOverColumn] = useState<InitiativeStatus | null>(null);

    const handleDragStart = (e: DragEvent<HTMLDivElement>, id: number) => {
        setDraggingId(id);
        e.dataTransfer.effectAllowed = 'move';
    };

    const handleDragOver = (e: DragEvent<HTMLDivElement>, columnId: InitiativeStatus) => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        setDragOverColumn(columnId);
    };

    const handleDrop = async (e: DragEvent<HTMLDivElement>, newStatus: InitiativeStatus) => {
        e.preventDefault();
        setDragOverColumn(null);

        if (draggingId === null) return;

        const initiative = initiatives.find((i) => i.id === draggingId);
        if (!initiative || initiative.status === newStatus) return;

        // Optimistic update
        setInitiatives((prev) =>
            prev.map((i) => (i.id === draggingId ? { ...i, status: newStatus } : i)),
        );

        try {
            await axios.patch(route('initiatives.update-status', [workspace.id, draggingId]), {
                status: newStatus,
            });
        } catch {
            // Revert on failure
            setInitiatives((prev) =>
                prev.map((i) => (i.id === draggingId ? { ...i, status: initiative.status } : i)),
            );
        } finally {
            setDraggingId(null);
        }
    };

    const handleDragEnd = () => {
        setDraggingId(null);
        setDragOverColumn(null);
    };

    const initiativesInColumn = (columnId: InitiativeStatus) =>
        initiatives.filter((i) => i.status === columnId);

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Kanban – {workspace.name}
                    </h2>
                    <div className="flex gap-3 text-sm">
                        <Link
                            href={route('initiatives.index', workspace.id)}
                            className="text-gray-500 hover:text-gray-700"
                        >
                            Lista
                        </Link>
                        <Link
                            href={route('initiatives.create', workspace.id)}
                            className="rounded bg-blue-600 px-3 py-1 text-white hover:bg-blue-700"
                        >
                            Nova Iniciativa
                        </Link>
                    </div>
                </div>
            }
        >
            <Head title={`Kanban – ${workspace.name}`} />

            <div className="py-8">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        {COLUMNS.map((column) => {
                            const cards = initiativesInColumn(column.id);
                            const isOver = dragOverColumn === column.id;

                            return (
                                <div
                                    key={column.id}
                                    className={`flex min-h-96 flex-col rounded-lg transition-colors ${column.color} ${isOver ? 'ring-2 ring-blue-400' : ''}`}
                                    onDragOver={(e) => handleDragOver(e, column.id)}
                                    onDrop={(e) => handleDrop(e, column.id)}
                                    onDragLeave={() => setDragOverColumn(null)}
                                >
                                    {/* Column header */}
                                    <div
                                        className={`flex items-center justify-between rounded-t-lg px-4 py-3 ${column.headerColor}`}
                                    >
                                        <span className="font-semibold">{column.label}</span>
                                        <span className="rounded-full bg-white/60 px-2 py-0.5 text-xs font-medium">
                                            {cards.length}
                                        </span>
                                    </div>

                                    {/* Cards */}
                                    <div className="flex flex-1 flex-col gap-2 p-3">
                                        {cards.map((initiative) => (
                                            <div
                                                key={initiative.id}
                                                draggable
                                                onDragStart={(e) => handleDragStart(e, initiative.id)}
                                                onDragEnd={handleDragEnd}
                                                className={`cursor-grab rounded-md bg-white p-3 shadow-sm transition-opacity active:cursor-grabbing ${draggingId === initiative.id ? 'opacity-40' : 'opacity-100'}`}
                                            >
                                                <Link
                                                    href={route('initiatives.show', [workspace.id, initiative.id])}
                                                    className="block text-sm font-medium text-gray-900 hover:text-blue-600"
                                                    onClick={(e) => e.stopPropagation()}
                                                >
                                                    {initiative.title}
                                                </Link>

                                                <div className="mt-2 flex items-center justify-between text-xs text-gray-500">
                                                    {initiative.owner && (
                                                        <span>{initiative.owner.name}</span>
                                                    )}
                                                    {initiative.due_date && (
                                                        <span>
                                                            {new Date(initiative.due_date).toLocaleDateString('pt-BR')}
                                                        </span>
                                                    )}
                                                </div>
                                            </div>
                                        ))}

                                        {cards.length === 0 && (
                                            <div className="flex flex-1 items-center justify-center rounded-md border-2 border-dashed border-gray-200 py-8 text-sm text-gray-400">
                                                Arraste aqui
                                            </div>
                                        )}
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
