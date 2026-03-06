import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

interface Initiative {
    id: number;
    title: string;
    status: string;
    due_date: string | null;
}

interface Workspace {
    id: number;
    name: string;
}

interface Props {
    workspace: Workspace;
    initiatives: Initiative[];
}

export default function Index({ workspace, initiatives }: Props) {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Iniciativas – {workspace.name}
                </h2>
            }
        >
            <Head title={`Iniciativas – ${workspace.name}`} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <div className="mb-4">
                                <Link
                                    href={route('initiatives.create', workspace.id)}
                                    className="rounded bg-blue-600 px-4 py-2 text-white"
                                >
                                    Nova Iniciativa
                                </Link>
                            </div>
                            {initiatives.length === 0 ? (
                                <p>Nenhuma iniciativa criada.</p>
                            ) : (
                                <ul className="space-y-2">
                                    {initiatives.map((initiative) => (
                                        <li key={initiative.id}>
                                            <Link href={route('initiatives.show', [workspace.id, initiative.id])}>
                                                {initiative.title}
                                            </Link>
                                            <span className="ml-2 text-sm text-gray-500">{initiative.status}</span>
                                        </li>
                                    ))}
                                </ul>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
