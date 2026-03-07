import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

interface Initiative {
    id: number;
    title: string;
}

interface Decision {
    id: number;
    title: string;
    context: string;
    decision: string;
    consequences: string | null;
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
    decision: Decision;
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

export default function Show({ workspace, decision }: Props) {
    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        {decision.title}
                    </h2>
                    <div className="flex items-center gap-3">
                        <span
                            className={`rounded-full px-3 py-1 text-xs font-medium ${STATUS_COLORS[decision.status] ?? 'bg-gray-100 text-gray-600'}`}
                        >
                            {STATUS_LABELS[decision.status] ?? decision.status}
                        </span>
                        <Link
                            href={route('decisions.edit', [workspace.id, decision.id])}
                            className="rounded border border-gray-300 px-3 py-1 text-sm text-gray-700 hover:bg-gray-50"
                        >
                            Editar
                        </Link>
                    </div>
                </div>
            }
        >
            <Head title={decision.title} />

            <div className="py-12">
                <div className="mx-auto max-w-3xl sm:px-6 lg:px-8 space-y-4">
                    {decision.initiative && (
                        <div className="rounded-lg border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                            Vinculada à iniciativa:{' '}
                            <Link
                                href={route('initiatives.show', [workspace.id, decision.initiative.id])}
                                className="font-medium underline hover:text-blue-900"
                            >
                                {decision.initiative.title}
                            </Link>
                        </div>
                    )}

                    <div className="overflow-hidden rounded-lg bg-white shadow-sm">
                        <Section title="Contexto" content={decision.context} />
                        <Section title="Decisão" content={decision.decision} />
                        {decision.consequences && (
                            <Section title="Consequências" content={decision.consequences} />
                        )}
                    </div>

                    <p className="text-right text-xs text-gray-400">
                        Registrado por {decision.author.name}
                    </p>

                    <div className="pt-2">
                        <Link
                            href={route('decisions.index', workspace.id)}
                            className="text-sm text-gray-500 hover:text-gray-700"
                        >
                            ← Voltar para Decisões
                        </Link>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

function Section({ title, content }: { title: string; content: string }) {
    return (
        <div className="border-b border-gray-100 p-6 last:border-b-0">
            <h3 className="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">
                {title}
            </h3>
            <p className="whitespace-pre-wrap text-sm text-gray-800">{content}</p>
        </div>
    );
}
