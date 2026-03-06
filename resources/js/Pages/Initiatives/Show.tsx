import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

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
}

export default function Show({ workspace, initiative }: Props) {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    {initiative.title}
                </h2>
            }
        >
            <Head title={initiative.title} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900 space-y-4">
                            <p><strong>Status:</strong> {initiative.status}</p>
                            <p><strong>Owner:</strong> {initiative.owner.name}</p>
                            {initiative.due_date && <p><strong>Prazo:</strong> {initiative.due_date}</p>}
                            {initiative.description && <p>{initiative.description}</p>}
                            <Link
                                href={route('initiatives.edit', [workspace.id, initiative.id])}
                                className="rounded bg-gray-600 px-4 py-2 text-white"
                            >
                                Editar
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
