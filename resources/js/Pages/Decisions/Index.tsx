import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

interface Initiative {
    id: number;
    title: string;
}

interface Decision {
    id: number;
    title: string;
    status: string;
    author: { name: string };
    initiative: Initiative | null;
    created_at: string;
}

interface Workspace {
    id: number;
    name: string;
}

interface Props {
    workspace: Workspace;
    decisions: Decision[];
}

const STATUS_LABELS: Record<string, string> = {
    proposed: 'Proposed',
    accepted: 'Accepted',
    deprecated: 'Deprecated',
    superseded: 'Superseded',
};

const STATUS_COLORS: Record<string, string> = {
    proposed: 'bg-yellow-100 text-yellow-800',
    accepted: 'bg-green-100 text-green-800',
    deprecated: 'bg-gray-100 text-gray-600',
    superseded: 'bg-red-100 text-red-700',
};

export default function Index({ workspace, decisions }: Props) {
    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Decisões – {workspace.name}
                    </h2>
                    <Link
                        href={route('decisions.create', workspace.id)}
                        className="rounded bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700"
                    >
                        Nova Decisão
                    </Link>
                </div>
            }
        >
            <Head title={`Decisões – ${workspace.name}`} />

            <div className="py-12">
                <div className="mx-auto max-w-5xl sm:px-6 lg:px-8">
                    {decisions.length === 0 ? (
                        <div className="rounded-lg bg-white p-12 text-center shadow-sm">
                            <p className="text-gray-500">Nenhuma decisão registrada ainda.</p>
                            <Link
                                href={route('decisions.create', workspace.id)}
                                className="mt-4 inline-block rounded bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700"
                            >
                                Registrar primeira decisão
                            </Link>
                        </div>
                    ) : (
                        <div className="space-y-3">
                            {decisions.map((decision) => (
                                <div
                                    key={decision.id}
                                    className="flex items-start justify-between rounded-lg bg-white p-5 shadow-sm"
                                >
                                    <div className="space-y-1">
                                        <Link
                                            href={route('decisions.show', [workspace.id, decision.id])}
                                            className="font-medium text-gray-900 hover:text-blue-600"
                                        >
                                            {decision.title}
                                        </Link>
                                        <div className="flex items-center gap-3 text-xs text-gray-500">
                                            <span>{decision.author.name}</span>
                                            {decision.initiative && (
                                                <span>
                                                    Iniciativa:{' '}
                                                    <Link
                                                        href={route('initiatives.show', [workspace.id, decision.initiative.id])}
                                                        className="hover:text-blue-600"
                                                    >
                                                        {decision.initiative.title}
                                                    </Link>
                                                </span>
                                            )}
                                        </div>
                                    </div>
                                    <span
                                        className={`rounded-full px-2 py-1 text-xs font-medium ${STATUS_COLORS[decision.status] ?? 'bg-gray-100 text-gray-600'}`}
                                    >
                                        {STATUS_LABELS[decision.status] ?? decision.status}
                                    </span>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
