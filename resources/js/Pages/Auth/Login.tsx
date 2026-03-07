import InputError from '@/Components/InputError';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';

export default function Login({
    status,
    canResetPassword,
}: {
    status?: string;
    canResetPassword: boolean;
}) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false as boolean,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('login'), { onFinish: () => reset('password') });
    };

    return (
        <GuestLayout>
            <Head title="Sign in" />

            <div className="mb-8">
                <h1 className="text-2xl font-semibold text-[#020617]">
                    Sign in
                </h1>
                <p className="mt-1 text-sm text-[#475569]">
                    Don&apos;t have an account?{' '}
                    <Link
                        href={route('register')}
                        className="font-medium text-[#0369A1] hover:underline focus:outline-none"
                    >
                        Create one
                    </Link>
                </p>
            </div>

            {status && (
                <div className="mb-4 rounded-md bg-green-50 p-3 text-sm text-green-700">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-5">
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
                        autoFocus
                        onChange={(e) => setData('email', e.target.value)}
                        className="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-[#020617] placeholder-gray-400 shadow-sm transition-colors focus:border-[#0369A1] focus:outline-none focus:ring-1 focus:ring-[#0369A1]"
                        placeholder="you@company.com"
                    />
                    <InputError message={errors.email} className="mt-1" />
                </div>

                <div>
                    <div className="flex items-center justify-between">
                        <label
                            htmlFor="password"
                            className="block text-sm font-medium text-[#0F172A]"
                        >
                            Password
                        </label>
                        {canResetPassword && (
                            <Link
                                href={route('password.request')}
                                className="text-xs text-[#0369A1] hover:underline focus:outline-none"
                            >
                                Forgot password?
                            </Link>
                        )}
                    </div>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        autoComplete="current-password"
                        onChange={(e) => setData('password', e.target.value)}
                        className="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-[#020617] shadow-sm transition-colors focus:border-[#0369A1] focus:outline-none focus:ring-1 focus:ring-[#0369A1]"
                    />
                    <InputError message={errors.password} className="mt-1" />
                </div>

                <div className="flex items-center gap-2">
                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        checked={data.remember}
                        onChange={(e) =>
                            setData(
                                'remember',
                                (e.target.checked || false) as false,
                            )
                        }
                        className="h-4 w-4 cursor-pointer rounded border-gray-300 text-[#0369A1] focus:ring-[#0369A1]"
                    />
                    <label
                        htmlFor="remember"
                        className="cursor-pointer text-sm text-[#475569]"
                    >
                        Remember me
                    </label>
                </div>

                <button
                    type="submit"
                    disabled={processing}
                    className="flex w-full cursor-pointer items-center justify-center rounded-md bg-[#0369A1] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-[#0284C7] focus:outline-none focus:ring-2 focus:ring-[#0369A1] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    {processing ? 'Signing in…' : 'Sign in'}
                </button>
            </form>
        </GuestLayout>
    );
}
