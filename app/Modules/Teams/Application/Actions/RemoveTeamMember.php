<?php

namespace App\Modules\Teams\Application\Actions;

use App\Models\User;
use App\Modules\Teams\Infrastructure\Team;
use Illuminate\Auth\Access\AuthorizationException;

class RemoveTeamMember
{
    /**
     * @throws AuthorizationException
     */
    public function execute(Team $team, User $userToRemove): void
    {
        if ($team->owner_id === $userToRemove->id) {
            throw new AuthorizationException('Cannot remove the team owner.');
        }

        $team->members()->detach($userToRemove->id);
    }
}
