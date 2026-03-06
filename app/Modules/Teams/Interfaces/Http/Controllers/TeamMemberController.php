<?php

namespace App\Modules\Teams\Interfaces\Http\Controllers;

use App\Models\User;
use App\Modules\Auth\Domain\Enums\PermissionName;
use App\Modules\Teams\Application\Actions\AddTeamMember;
use App\Modules\Teams\Application\Actions\RemoveTeamMember;
use App\Modules\Teams\Infrastructure\Team;
use App\Modules\Workspaces\Infrastructure\Workspace;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TeamMemberController extends Controller
{
    public function store(Request $request, Workspace $workspace, Team $team, AddTeamMember $action): RedirectResponse
    {
        abort_unless($request->user()->can(PermissionName::InviteMembers->value), 403);
        abort_unless($team->workspace_id === $workspace->id, 404);

        $request->validate(['user_id' => ['required', 'integer', 'exists:users,id']]);

        $userToAdd = User::findOrFail($request->integer('user_id'));

        $action->execute($team, $userToAdd);

        return back()->with('success', 'Membro adicionado ao time.');
    }

    public function destroy(Request $request, Workspace $workspace, Team $team, User $user, RemoveTeamMember $action): RedirectResponse
    {
        abort_unless($request->user()->can(PermissionName::RemoveMembers->value), 403);
        abort_unless($team->workspace_id === $workspace->id, 404);

        $action->execute($team, $user);

        return back()->with('success', 'Membro removido do time.');
    }
}
