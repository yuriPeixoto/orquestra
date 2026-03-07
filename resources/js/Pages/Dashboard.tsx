import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { PageProps } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

interface RecentDecision {
    id: number;
    title: string;
    status: string;
    created_at: string;
    author: { name: string } | null;
}

interface Stats {
    initiative_count: number;
    initiative_by_status: Record<string, number>;
    decision_count: number;
    team_count: number;
    recent_decisions: RecentDecision[];
}

type DashboardProps = PageProps<{
    workspace: { id: number; name: string } | null;
    stats: Stats | null;
}>;

const STATUS_LABELS: Record<string, string> = {
    draft: 'Draft',
    active: 'Active',
    on_hold: 'On Hold',
    completed: 'Completed',
    cancelled: 'Cancelled',
};

const STATUS_COLORS: Record<string, string> = {
    draft: 'bg-gray-100 text-gray-600',
    active: 'bg-blue-100 text-blue-700',
    on_hold: 'bg-yellow-100 text-yellow-700',
    completed: 'bg-green-100 text-green-700',
    cancelled: 'bg-red-100 text-red-600',
};

const DECISION_STATUS_COLORS: Record<string, string> = {
    proposed: 'bg-blue-100 text-blue-700',
    accepted: 'bg-green-100 text-green-700',
    deprecated: 'bg-yellow-100 text-yellow-700',
    superseded: 'bg-gray-100 text-gray-600',
};

function StatCard({
    label,
    value,
    href,
}: {
    label: string;
    value: number;
    href?: string;
}) {
    const inner = (
        <div className="rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
            <p className="text-sm font-medium text-[#475569]">{label}</p>
            <p className="mt-2 text-3xl font-semibold text-[#020617]">
                {value}
            </p>
        </div>
    );

    if (href) {
        return (
            <Link href={href} className="cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#0369A1] focus:ring-offset-2 rounded-lg block">
                {inner}
            </Link>
        );
    }

    return inner;
}

function EmptyState({ workspaceId }: { workspaceId?: number }) {
    return (
        <div className="flex flex-col items-center justify-center rounded-lg border border-dashed border-gray-300 bg-white py-16 text-center">
            <div className="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#F1F5F9]">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" strokeWidth={1.5} strokeLinecap="round" strokeLinejoin="round" className="h-7 w-7">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
            </div>
            <h3 className="text-base font-semibold text-[#0F172A]">
                No workspace yet
            </h3>
            <p className="mt-1 text-sm text-[#475569]">
                Create a workspace to start organizing your team&apos;s work.
            </p>
            <Link
                href={route('workspaces.store')}
                className="mt-6 cursor-pointer rounded-md bg-[#0369A1] px-4 py-2 text-sm font-semibold text-white hover:bg-[#0284C7] focus:outline-none focus:ring-2 focus:ring-[#0369A1] focus:ring-offset-2"
            >
                Create workspace
            </Link>
        </div>
    );
}

export default function Dashboard() {
    const { workspace, stats } = usePage<DashboardProps>().props;

    const allStatuses = ['draft', 'active', 'on_hold', 'completed', 'cancelled'];

    return (
        <AuthenticatedLayout header="Dashboard">
            <Head title="Dashboard" />

            {!workspace || !stats ? (
                <EmptyState />
            ) : (
                <div className="space-y-8">
                    {/* Stat cards */}
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <StatCard
                            label="Initiatives"
                            value={stats.initiative_count}
                            href={route('initiatives.index', { workspace: workspace.id })}
                        />
                        <StatCard
                            label="Decisions"
                            value={stats.decision_count}
                            href={route('decisions.index', { workspace: workspace.id })}
                        />
                        <StatCard
                            label="Teams"
                            value={stats.team_count}
                            href={route('teams.index', { workspace: workspace.id })}
                        />
                    </div>

                    <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        {/* Initiatives by status */}
                        <div className="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                            <div className="mb-4 flex items-center justify-between">
                                <h2 className="text-sm font-semibold text-[#0F172A]">
                                    Initiatives by status
                                </h2>
                                <Link
                                    href={route('initiatives.index', { workspace: workspace.id })}
                                    className="cursor-pointer text-xs font-medium text-[#0369A1] hover:underline focus:outline-none"
                                >
                                    View all
                                </Link>
                            </div>

                            {stats.initiative_count === 0 ? (
                                <p className="py-6 text-center text-sm text-[#94A3B8]">
                                    No initiatives yet.
                                </p>
                            ) : (
                                <div className="space-y-3">
                                    {allStatuses.map((status) => {
                                        const count = stats.initiative_by_status[status] ?? 0;
                                        if (count === 0) return null;
                                        const pct = Math.round((count / stats.initiative_count) * 100);
                                        return (
                                            <div key={status}>
                                                <div className="mb-1 flex items-center justify-between text-xs">
                                                    <span className={`inline-flex rounded-full px-2 py-0.5 text-xs font-medium ${STATUS_COLORS[status]}`}>
                                                        {STATUS_LABELS[status]}
                                                    </span>
                                                    <span className="text-[#475569]">
                                                        {count} ({pct}%)
                                                    </span>
                                                </div>
                                                <div className="h-1.5 w-full overflow-hidden rounded-full bg-gray-100">
                                                    <div
                                                        className="h-full rounded-full bg-[#0369A1] transition-all"
                                                        style={{ width: `${pct}%` }}
                                                    />
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            )}
                        </div>

                        {/* Recent decisions */}
                        <div className="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                            <div className="mb-4 flex items-center justify-between">
                                <h2 className="text-sm font-semibold text-[#0F172A]">
                                    Recent decisions
                                </h2>
                                <Link
                                    href={route('decisions.index', { workspace: workspace.id })}
                                    className="cursor-pointer text-xs font-medium text-[#0369A1] hover:underline focus:outline-none"
                                >
                                    View all
                                </Link>
                            </div>

                            {stats.recent_decisions.length === 0 ? (
                                <p className="py-6 text-center text-sm text-[#94A3B8]">
                                    No decisions recorded yet.
                                </p>
                            ) : (
                                <ul className="divide-y divide-gray-100">
                                    {stats.recent_decisions.map((d) => (
                                        <li key={d.id} className="py-3 first:pt-0 last:pb-0">
                                            <Link
                                                href={route('decisions.show', { workspace: workspace.id, decision: d.id })}
                                                className="group cursor-pointer focus:outline-none"
                                            >
                                                <div className="flex items-start justify-between gap-2">
                                                    <p className="text-sm font-medium text-[#0F172A] group-hover:text-[#0369A1] transition-colors">
                                                        {d.title}
                                                    </p>
                                                    <span className={`shrink-0 inline-flex rounded-full px-2 py-0.5 text-xs font-medium ${DECISION_STATUS_COLORS[d.status] ?? 'bg-gray-100 text-gray-600'}`}>
                                                        {d.status.charAt(0).toUpperCase() + d.status.slice(1)}
                                                    </span>
                                                </div>
                                                <p className="mt-0.5 text-xs text-[#94A3B8]">
                                                    {d.author?.name ?? 'Unknown'} &middot;{' '}
                                                    {new Date(d.created_at).toLocaleDateString('en-US', {
                                                        month: 'short',
                                                        day: 'numeric',
                                                        year: 'numeric',
                                                    })}
                                                </p>
                                            </Link>
                                        </li>
                                    ))}
                                </ul>
                            )}
                        </div>
                    </div>
                </div>
            )}
        </AuthenticatedLayout>
    );
}
