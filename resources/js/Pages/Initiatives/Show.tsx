import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

interface Decision {
    id: number;
    title: string;
    status: string;
    author: { name: string };
}

interface Initiative {
    id: number;
    title: string;
    description: string | null;
    status: string;
    due_date: string | null;
    owner: { id: number; name: string };
}

interface Workspace {
    id: number;
    name: string;
}

interface Props {
    workspace: Workspace;
    initiative: Initiative;
    decisions: Decision[];
}

const STATUS_COLORS: Record<string, string> = {
    proposed: 'bg-yellow-100 text-yellow-800',
    accepted: 'bg-green-100 text-green-800',
    deprecated: 'bg-gray-100 text-gray-600',
    superseded: 'bg-red-100 text-red-700',
};

export default function Show({ workspace, initiative, decisions }: Props) {
    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        {initiative.title}
                    </h2>
                    <Link
                        href={route('initiatives.edit', [workspace.id, initiative.id])}
                        className="rounded border border-gray-300 px-3 py-1 text-sm text-gray-700 hover:bg-gray-50"
                    >
                        Editar
                    </Link>
                </div>
            }
        >
            <Head title={initiative.title} />

            <div className="py-12">
                <div className="mx-auto max-w-3xl space-y-6 sm:px-6 lg:px-8">
                    {/* Initiative details */}
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="space-y-3 p-6">
                            <p><strong>Status:</strong> {initiative.status}</p>
                            <p><strong>Owner:</strong> {initiative.owner.name}</p>
                            {initiative.due_date && (
                                <p><strong>Prazo:</strong> {initiative.due_date}</p>
                            )}
                            {initiative.description && (
                                <p className="whitespace-pre-wrap text-gray-700">{initiative.description}</p>
                            )}
                        </div>
                    </div>

                    {/* Linked decisions */}
                    <div>
                        <div className="mb-3 flex items-center justify-between">
                            <h3 className="text-sm font-semibold uppercase tracking-wide text-gray-500">
                                Decisões vinculadas
                            </h3>
                            <Link
                                href={route('decisions.create', workspace.id)}
                                className="text-sm text-blue-600 hover:text-blue-800"
                            >
                                + Nova decisão
                            </Link>
                        </div>

                        {decisions.length === 0 ? (
                            <p className="rounded-lg border border-dashed border-gray-200 p-6 text-center text-sm text-gray-400">
                                Nenhuma decisão vinculada a esta iniciativa.
                            </p>
                        ) : (
                            <div className="space-y-2">
                                {decisions.map((decision) => (
                                    <div
                                        key={decision.id}
                                        className="flex items-center justify-between rounded-lg bg-white p-4 shadow-sm"
                                    >
                                        <div>
                                            <Link
                                                href={route('decisions.show', [workspace.id, decision.id])}
                                                className="text-sm font-medium text-gray-900 hover:text-blue-600"
                                            >
                                                {decision.title}
                                            </Link>
                                            <p className="text-xs text-gray-500">{decision.author.name}</p>
                                        </div>
                                        <span
                                            className={`rounded-full px-2 py-1 text-xs font-medium ${STATUS_COLORS[decision.status] ?? 'bg-gray-100 text-gray-600'}`}
                                        >
                                            {decision.status}
                                        </span>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
