import InputError from '@/Components/InputError';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Create account" />

            <div className="mb-8">
                <h1 className="text-2xl font-semibold text-[#020617]">
                    Create account
                </h1>
                <p className="mt-1 text-sm text-[#475569]">
                    Already have an account?{' '}
                    <Link
                        href={route('login')}
                        className="font-medium text-[#0369A1] hover:underline focus:outline-none"
                    >
                        Sign in
                    </Link>
                </p>
            </div>

            <form onSubmit={submit} className="space-y-5">
                <div>
                    <label
                        htmlFor="name"
                        className="block text-sm font-medium text-[#0F172A]"
                    >
                        Full name
                    </label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value={data.name}
                        autoComplete="name"
                        autoFocus
                        onChange={(e) => setData('name', e.target.value)}
                        required
                        className="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-[#020617] placeholder-gray-400 shadow-sm transition-colors focus:border-[#0369A1] focus:outline-none focus:ring-1 focus:ring-[#0369A1]"
                        placeholder="Jane Smith"
                    />
                    <InputError message={errors.name} className="mt-1" />
                </div>

                <div>
                    <label
                        htmlFor="email"
                        className="block text-sm font-medium text-[#0F172A]"
                    >
                        Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        autoComplete="username"
                        onChange={(e) => setData('email', e.target.value)}
                        required
                        className="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-[#020617] placeholder-gray-400 shadow-sm transition-colors focus:border-[#0369A1] focus:outline-none focus:ring-1 focus:ring-[#0369A1]"
                        placeholder="you@company.com"
                    />
                    <InputError message={errors.email} className="mt-1" />
                </div>

                <div>
                    <label
                        htmlFor="password"
                        className="block text-sm font-medium text-[#0F172A]"
                    >
                        Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        autoComplete="new-password"
                        onChange={(e) => setData('password', e.target.value)}
                        required
                        className="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-[#020617] shadow-sm transition-colors focus:border-[#0369A1] focus:outline-none focus:ring-1 focus:ring-[#0369A1]"
                    />
                    <InputError message={errors.password} className="mt-1" />
                </div>

                <div>
                    <label
                        htmlFor="password_confirmation"
                        className="block text-sm font-medium text-[#0F172A]"
                    >
                        Confirm password
                    </label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        autoComplete="new-password"
                        onChange={(e) =>
                            setData('password_confirmation', e.target.value)
                        }
                        required
                        className="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-[#020617] shadow-sm transition-colors focus:border-[#0369A1] focus:outline-none focus:ring-1 focus:ring-[#0369A1]"
                    />
                    <InputError
                        message={errors.password_confirmation}
                        className="mt-1"
                    />
                </div>

                <button
                    type="submit"
                    disabled={processing}
                    className="flex w-full cursor-pointer items-center justify-center rounded-md bg-[#0369A1] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-[#0284C7] focus:outline-none focus:ring-2 focus:ring-[#0369A1] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    {processing ? 'Creating account…' : 'Create account'}
                </button>
            </form>
        </GuestLayout>
    );
}
