import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

interface Team {
    id: number;
    name: string;
    slug: string;
    members_count: number;
}

interface Workspace {
    id: number;
    name: string;
}

interface Props {
    workspace: Workspace;
    teams: Team[];
}

export default function Index({ workspace, teams }: Props) {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Times – {workspace.name}
                </h2>
            }
        >
            <Head title={`Times – ${workspace.name}`} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            {teams.length === 0 ? (
                                <p>Nenhum time criado ainda.</p>
                            ) : (
                                <ul>
                                    {teams.map((team) => (
                                        <li key={team.id}>
                                            {team.name} ({team.members_count} membros)
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
