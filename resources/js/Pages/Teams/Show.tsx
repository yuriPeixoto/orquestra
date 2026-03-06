import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

interface Member {
    id: number;
    name: string;
    email: string;
}

interface Team {
    id: number;
    name: string;
    slug: string;
    members: Member[];
}

interface Workspace {
    id: number;
    name: string;
}

interface Props {
    workspace: Workspace;
    team: Team;
}

export default function Show({ workspace, team }: Props) {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    {team.name}
                </h2>
            }
        >
            <Head title={team.name} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <h3 className="font-semibold mb-4">Membros</h3>
                            <ul>
                                {team.members.map((member) => (
                                    <li key={member.id}>{member.name}</li>
                                ))}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
