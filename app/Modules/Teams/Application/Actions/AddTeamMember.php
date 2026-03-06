<?php

namespace App\Modules\Teams\Application\Actions;

use App\Models\User;
use App\Modules\Auth\Domain\Enums\RoleName;
use App\Modules\Teams\Infrastructure\Team;
use Illuminate\Auth\Access\AuthorizationException;

class AddTeamMember
{
    /**
     * @throws AuthorizationException
     */
    public function execute(Team $team, User $userToAdd): void
    {
        // Only workspace members (any role) can be added to teams.
        $workspaceRoles = [
            RoleName::WorkspaceOwner->value,
            RoleName::WorkspaceMember->value,
            RoleName::WorkspaceViewer->value,
        ];

        if (! $userToAdd->hasAnyRole($workspaceRoles)) {
            throw new AuthorizationException('User is not a member of this workspace.');
        }

        if (! $team->members()->where('user_id', $userToAdd->id)->exists()) {
            $team->members()->attach($userToAdd->id);
        }
    }
}
