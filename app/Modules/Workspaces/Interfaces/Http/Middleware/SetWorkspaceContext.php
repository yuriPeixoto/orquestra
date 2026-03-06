<?php

namespace App\Modules\Workspaces\Interfaces\Http\Middleware;

use App\Modules\Auth\Domain\Enums\RoleName;
use App\Modules\Workspaces\Infrastructure\Workspace;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetWorkspaceContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $workspace = $request->route('workspace');

        if (! $workspace instanceof Workspace) {
            abort(400, 'Workspace context missing from route.');
        }

        $user = $request->user();

        // Global admins (team_id=0) can access any workspace.
        setPermissionsTeamId(0);
        if ($user->hasRole(RoleName::Admin->value)) {
            setPermissionsTeamId($workspace->id);
            app()->instance('current.workspace', $workspace);

            return $next($request);
        }

        // Workspace members must have any workspace-scoped role for this workspace.
        // Unset cached roles to avoid stale results from the admin check above.
        $user->unsetRelation('roles');
        setPermissionsTeamId($workspace->id);
        $workspaceRoles = [
            RoleName::WorkspaceOwner->value,
            RoleName::WorkspaceMember->value,
            RoleName::WorkspaceViewer->value,
        ];

        if (! $user->hasAnyRole($workspaceRoles)) {
            abort(403, 'Você não tem acesso a este workspace.');
        }

        app()->instance('current.workspace', $workspace);

        return $next($request);
    }
}
