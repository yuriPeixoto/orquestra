<?php

namespace App\Modules\Teams\Application\Actions;

use App\Models\User;
use App\Modules\Teams\Infrastructure\Team;
use App\Modules\Workspaces\Infrastructure\Workspace;
use Illuminate\Support\Str;

class CreateTeam
{
    public function execute(Workspace $workspace, User $owner, string $name): Team
    {
        $team = Team::create([
            'workspace_id' => $workspace->id,
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::random(4),
            'owner_id' => $owner->id,
        ]);

        $team->members()->attach($owner->id);

        return $team;
    }
}
