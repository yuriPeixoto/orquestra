import InputError from '@/Components/InputError';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('workspaces.store'));
    };

    return (
        <AuthenticatedLayout header="New workspace">
            <Head title="New workspace" />

            <div className="mx-auto max-w-lg">
                <div className="rounded-lg border border-gray-200 bg-white p-8 shadow-sm">
                    <h1 className="text-lg font-semibold text-[#0F172A]">
                        Create a workspace
                    </h1>
                    <p className="mt-1 text-sm text-[#475569]">
                        A workspace groups your teams, initiatives, and decisions.
                    </p>

                    <form onSubmit={submit} className="mt-6 space-y-5">
                        <div>
                            <label
                                htmlFor="name"
                                className="block text-sm font-medium text-[#0F172A]"
                            >
                                Workspace name
                            </label>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value={data.name}
                                autoFocus
                                onChange={(e) => setData('name', e.target.value)}
                                placeholder="e.g. Acme Engineering"
                                className="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-[#020617] placeholder-gray-400 shadow-sm transition-colors focus:border-[#0369A1] focus:outline-none focus:ring-1 focus:ring-[#0369A1]"
                            />
                            <InputError message={errors.name} className="mt-1" />
                        </div>

                        <div className="flex items-center justify-end gap-3 pt-2">
                            <Link
                                href={route('dashboard')}
                                className="cursor-pointer text-sm text-[#475569] hover:text-[#0F172A] focus:outline-none"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                disabled={processing}
                                className="cursor-pointer rounded-md bg-[#0369A1] px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-[#0284C7] focus:outline-none focus:ring-2 focus:ring-[#0369A1] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
                            >
                                {processing ? 'Creating…' : 'Create workspace'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
