import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';

interface Status {
    name: string;
    value: string;
}

interface Initiative {
    id: number;
    title: string;
    description: string | null;
    status: string;
    due_date: string | null;
}

interface Workspace {
    id: number;
    name: string;
}

interface Props {
    workspace: Workspace;
    initiative: Initiative;
    statuses: Status[];
}

export default function Edit({ workspace, initiative, statuses }: Props) {
    const { data, setData, put, errors, processing } = useForm({
        title: initiative.title,
        description: initiative.description ?? '',
        status: initiative.status,
        due_date: initiative.due_date ?? '',
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        put(route('initiatives.update', [workspace.id, initiative.id]));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Editar Iniciativa
                </h2>
            }
        >
            <Head title="Editar Iniciativa" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg p-6">
                        <form onSubmit={submit} className="space-y-4">
                            <div>
                                <label htmlFor="title">Título</label>
                                <input
                                    id="title"
                                    type="text"
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    className="mt-1 block w-full border rounded px-3 py-2"
                                />
                                {errors.title && <p className="text-red-500 text-sm">{errors.title}</p>}
                            </div>
                            <div>
                                <label htmlFor="description">Descrição</label>
                                <textarea
                                    id="description"
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    className="mt-1 block w-full border rounded px-3 py-2"
                                />
                            </div>
                            <div>
                                <label htmlFor="status">Status</label>
                                <select
                                    id="status"
                                    value={data.status}
                                    onChange={(e) => setData('status', e.target.value)}
                                    className="mt-1 block w-full border rounded px-3 py-2"
                                >
                                    {statuses.map((s) => (
                                        <option key={s.value} value={s.value}>{s.name}</option>
                                    ))}
                                </select>
                            </div>
                            <div>
                                <label htmlFor="due_date">Prazo</label>
                                <input
                                    id="due_date"
                                    type="date"
                                    value={data.due_date}
                                    onChange={(e) => setData('due_date', e.target.value)}
                                    className="mt-1 block w-full border rounded px-3 py-2"
                                />
                            </div>
                            <button
                                type="submit"
                                disabled={processing}
                                className="rounded bg-blue-600 px-4 py-2 text-white"
                            >
                                Salvar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
