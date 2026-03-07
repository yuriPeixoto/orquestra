import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

interface Status {
    name: string;
    value: string;
}

interface Initiative {
    id: number;
    title: string;
}

interface DecisionData {
    id: number;
    title: string;
    context: string;
    decision: string;
    consequences: string | null;
    status: string;
    initiative: Initiative | null;
}

interface Workspace {
    id: number;
    name: string;
}

interface Props {
    workspace: Workspace;
    decision: DecisionData;
    statuses: Status[];
    initiatives: Initiative[];
}

export default function Edit({ workspace, decision, statuses, initiatives }: Props) {
    const { data, setData, put, errors, processing } = useForm({
        title: decision.title,
        context: decision.context,
        decision: decision.decision,
        consequences: decision.consequences ?? '',
        status: decision.status,
        initiative_id: decision.initiative ? String(decision.initiative.id) : '',
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        put(route('decisions.update', [workspace.id, decision.id]));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Editar Decisão
                </h2>
            }
        >
            <Head title="Editar Decisão" />

            <div className="py-12">
                <div className="mx-auto max-w-3xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <form onSubmit={submit} className="space-y-6 p-6">
                            <div>
                                <label htmlFor="title" className="block text-sm font-medium text-gray-700">
                                    Título
                                </label>
                                <input
                                    id="title"
                                    type="text"
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    className="mt-1 block w-full rounded border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none"
                                />
                                {errors.title && <p className="mt-1 text-sm text-red-600">{errors.title}</p>}
                            </div>

                            <div>
                                <label htmlFor="context" className="block text-sm font-medium text-gray-700">
                                    Contexto
                                </label>
                                <textarea
                                    id="context"
                                    rows={4}
                                    value={data.context}
                                    onChange={(e) => setData('context', e.target.value)}
                                    className="mt-1 block w-full rounded border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none"
                                />
                                {errors.context && <p className="mt-1 text-sm text-red-600">{errors.context}</p>}
                            </div>

                            <div>
                                <label htmlFor="decision" className="block text-sm font-medium text-gray-700">
                                    Decisão
                                </label>
                                <textarea
                                    id="decision"
                                    rows={4}
                                    value={data.decision}
                                    onChange={(e) => setData('decision', e.target.value)}
                                    className="mt-1 block w-full rounded border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none"
                                />
                                {errors.decision && <p className="mt-1 text-sm text-red-600">{errors.decision}</p>}
                            </div>

                            <div>
                                <label htmlFor="consequences" className="block text-sm font-medium text-gray-700">
                                    Consequências <span className="text-gray-400">(opcional)</span>
                                </label>
                                <textarea
                                    id="consequences"
                                    rows={3}
                                    value={data.consequences}
                                    onChange={(e) => setData('consequences', e.target.value)}
                                    className="mt-1 block w-full rounded border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none"
                                />
                            </div>

                            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label htmlFor="status" className="block text-sm font-medium text-gray-700">
                                        Status
                                    </label>
                                    <select
                                        id="status"
                                        value={data.status}
                                        onChange={(e) => setData('status', e.target.value)}
                                        className="mt-1 block w-full rounded border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none"
                                    >
                                        {statuses.map((s) => (
                                            <option key={s.value} value={s.value}>
                                                {s.name}
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div>
                                    <label htmlFor="initiative_id" className="block text-sm font-medium text-gray-700">
                                        Iniciativa vinculada <span className="text-gray-400">(opcional)</span>
                                    </label>
                                    <select
                                        id="initiative_id"
                                        value={data.initiative_id}
                                        onChange={(e) => setData('initiative_id', e.target.value)}
                                        className="mt-1 block w-full rounded border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none"
                                    >
                                        <option value="">— Nenhuma —</option>
                                        {initiatives.map((i) => (
                                            <option key={i.id} value={i.id}>
                                                {i.title}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                            </div>

                            <div className="flex justify-end">
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="rounded bg-blue-600 px-6 py-2 text-white hover:bg-blue-700 disabled:opacity-50"
                                >
                                    Salvar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
