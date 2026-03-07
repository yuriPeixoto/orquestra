import { PageProps } from '@/types';
import { Link, router, usePage } from '@inertiajs/react';
import { PropsWithChildren, ReactNode, useState } from 'react';

type SharedProps = PageProps<{
    currentWorkspace: { id: number; name: string; slug: string } | null;
}>;

interface NavItem {
    label: string;
    href: string;
    routePattern: string;
    icon: ReactNode;
}

function IconHome() {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.75} strokeLinecap="round" strokeLinejoin="round" className="h-5 w-5">
            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            <polyline points="9 22 9 12 15 12 15 22" />
        </svg>
    );
}

function IconList() {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.75} strokeLinecap="round" strokeLinejoin="round" className="h-5 w-5">
            <line x1="8" y1="6" x2="21" y2="6" />
            <line x1="8" y1="12" x2="21" y2="12" />
            <line x1="8" y1="18" x2="21" y2="18" />
            <line x1="3" y1="6" x2="3.01" y2="6" />
            <line x1="3" y1="12" x2="3.01" y2="12" />
            <line x1="3" y1="18" x2="3.01" y2="18" />
        </svg>
    );
}

function IconKanban() {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.75} strokeLinecap="round" strokeLinejoin="round" className="h-5 w-5">
            <rect x="3" y="3" width="5" height="18" rx="1" />
            <rect x="10" y="3" width="5" height="12" rx="1" />
            <rect x="17" y="3" width="5" height="7" rx="1" />
        </svg>
    );
}

function IconBook() {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.75} strokeLinecap="round" strokeLinejoin="round" className="h-5 w-5">
            <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" />
        </svg>
    );
}

function IconUsers() {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.75} strokeLinecap="round" strokeLinejoin="round" className="h-5 w-5">
            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 00-3-3.87" />
            <path d="M16 3.13a4 4 0 010 7.75" />
        </svg>
    );
}

function IconChevronDown() {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round" className="h-4 w-4">
            <polyline points="6 9 12 15 18 9" />
        </svg>
    );
}

function IconMenu() {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round" className="h-6 w-6">
            <line x1="3" y1="6" x2="21" y2="6" />
            <line x1="3" y1="12" x2="21" y2="12" />
            <line x1="3" y1="18" x2="21" y2="18" />
        </svg>
    );
}

function IconX() {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={2} strokeLinecap="round" strokeLinejoin="round" className="h-6 w-6">
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
        </svg>
    );
}

export default function AuthenticatedLayout({
    header,
    children,
}: PropsWithChildren<{ header?: ReactNode }>) {
    const { auth, currentWorkspace } = usePage<SharedProps>().props;
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [userMenuOpen, setUserMenuOpen] = useState(false);

    const wsId = currentWorkspace?.id;
    const wsName = currentWorkspace?.name ?? 'No workspace';

    const navItems: NavItem[] = [
        {
            label: 'Dashboard',
            href: route('dashboard'),
            routePattern: 'dashboard',
            icon: <IconHome />,
        },
        ...(wsId
            ? [
                  {
                      label: 'Initiatives',
                      href: route('initiatives.index', { workspace: wsId }),
                      routePattern: 'initiatives.*',
                      icon: <IconList />,
                  },
                  {
                      label: 'Kanban',
                      href: route('initiatives.kanban', { workspace: wsId }),
                      routePattern: 'initiatives.kanban',
                      icon: <IconKanban />,
                  },
                  {
                      label: 'Decisions',
                      href: route('decisions.index', { workspace: wsId }),
                      routePattern: 'decisions.*',
                      icon: <IconBook />,
                  },
                  {
                      label: 'Teams',
                      href: route('teams.index', { workspace: wsId }),
                      routePattern: 'teams.*',
                      icon: <IconUsers />,
                  },
              ]
            : []),
    ];

    function isActive(routePattern: string): boolean {
        if (routePattern.endsWith('.*')) {
            const prefix = routePattern.slice(0, -2);
            return route().current(prefix + '.*') ?? false;
        }
        return route().current(routePattern) ?? false;
    }

    function handleLogout() {
        router.post(route('logout'));
    }

    const SidebarContent = () => (
        <div className="flex h-full flex-col">
            {/* Logo */}
            <div className="flex h-16 shrink-0 items-center border-b border-[#1E293B] px-6">
                <Link href={route('dashboard')} className="flex items-center gap-2 focus:outline-none">
                    <span className="text-lg font-bold tracking-tight text-white">
                        Orquestra
                    </span>
                </Link>
            </div>

            {/* Workspace badge */}
            {currentWorkspace && (
                <div className="mx-4 mt-4 rounded-md bg-[#1E293B] px-3 py-2">
                    <p className="text-xs font-medium text-[#94A3B8]">Workspace</p>
                    <p className="truncate text-sm font-semibold text-white">{wsName}</p>
                </div>
            )}

            {/* Nav */}
            <nav className="flex-1 space-y-1 px-3 py-4">
                {navItems.map((item) => {
                    const active = isActive(item.routePattern);
                    return (
                        <Link
                            key={item.label}
                            href={item.href}
                            onClick={() => setSidebarOpen(false)}
                            className={`flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-[#0369A1] focus:ring-offset-2 focus:ring-offset-[#0F172A] ${
                                active
                                    ? 'bg-[#0369A1] text-white'
                                    : 'text-[#94A3B8] hover:bg-[#1E293B] hover:text-white'
                            }`}
                        >
                            {item.icon}
                            {item.label}
                        </Link>
                    );
                })}
            </nav>

            {/* User section */}
            <div className="border-t border-[#1E293B] p-4">
                <div className="relative">
                    <button
                        type="button"
                        onClick={() => setUserMenuOpen((v) => !v)}
                        className="flex w-full cursor-pointer items-center gap-3 rounded-md px-2 py-2 text-left transition-colors hover:bg-[#1E293B] focus:outline-none focus:ring-2 focus:ring-[#0369A1] focus:ring-offset-2 focus:ring-offset-[#0F172A]"
                        aria-label="User menu"
                    >
                        <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#0369A1] text-sm font-semibold text-white">
                            {auth.user.name.charAt(0).toUpperCase()}
                        </div>
                        <div className="min-w-0 flex-1">
                            <p className="truncate text-sm font-medium text-white">
                                {auth.user.name}
                            </p>
                            <p className="truncate text-xs text-[#64748B]">
                                {auth.user.email}
                            </p>
                        </div>
                        <IconChevronDown />
                    </button>

                    {userMenuOpen && (
                        <div className="absolute bottom-full left-0 right-0 mb-1 rounded-md border border-[#1E293B] bg-[#0F172A] py-1 shadow-lg">
                            <Link
                                href={route('profile.edit')}
                                className="block px-4 py-2 text-sm text-[#94A3B8] transition-colors hover:bg-[#1E293B] hover:text-white focus:outline-none"
                                onClick={() => setUserMenuOpen(false)}
                            >
                                Profile
                            </Link>
                            <button
                                type="button"
                                onClick={handleLogout}
                                className="block w-full cursor-pointer px-4 py-2 text-left text-sm text-[#94A3B8] transition-colors hover:bg-[#1E293B] hover:text-white focus:outline-none"
                            >
                                Sign out
                            </button>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );

    return (
        <div className="flex h-screen overflow-hidden bg-[#F8FAFC]">
            {/* Mobile overlay */}
            {sidebarOpen && (
                <div
                    className="fixed inset-0 z-20 bg-black/50 lg:hidden"
                    onClick={() => setSidebarOpen(false)}
                    aria-hidden="true"
                />
            )}

            {/* Mobile sidebar drawer */}
            <aside
                className={`fixed inset-y-0 left-0 z-30 w-64 transform bg-[#0F172A] transition-transform duration-200 ease-in-out lg:hidden ${
                    sidebarOpen ? 'translate-x-0' : '-translate-x-full'
                }`}
                aria-label="Sidebar navigation"
            >
                <div className="absolute right-3 top-4">
                    <button
                        type="button"
                        onClick={() => setSidebarOpen(false)}
                        className="cursor-pointer rounded-md p-1 text-[#94A3B8] hover:text-white focus:outline-none"
                        aria-label="Close sidebar"
                    >
                        <IconX />
                    </button>
                </div>
                <SidebarContent />
            </aside>

            {/* Desktop sidebar */}
            <aside className="hidden w-64 shrink-0 flex-col bg-[#0F172A] lg:flex">
                <SidebarContent />
            </aside>

            {/* Main content */}
            <div className="flex flex-1 flex-col overflow-hidden">
                {/* Topbar */}
                <header className="flex h-16 shrink-0 items-center justify-between border-b border-gray-200 bg-white px-4 sm:px-6">
                    <div className="flex items-center gap-4">
                        {/* Mobile menu toggle */}
                        <button
                            type="button"
                            className="cursor-pointer rounded-md p-1 text-[#334155] hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#0369A1] lg:hidden"
                            onClick={() => setSidebarOpen(true)}
                            aria-label="Open sidebar"
                        >
                            <IconMenu />
                        </button>
                        {header && (
                            <div className="text-sm font-medium text-[#0F172A]">
                                {header}
                            </div>
                        )}
                    </div>
                </header>

                {/* Page content */}
                <main className="flex-1 overflow-y-auto p-6">
                    {children}
                </main>
            </div>
        </div>
    );
}
