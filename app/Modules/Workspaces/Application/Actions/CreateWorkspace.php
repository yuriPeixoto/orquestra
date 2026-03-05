<?php

namespace App\Modules\Workspaces\Application\Actions;

use App\Models\User;
use App\Modules\Auth\Domain\Enums\RoleName;
use App\Modules\Workspaces\Infrastructure\Workspace;
use Illuminate\Support\Str;

class CreateWorkspace
{
    public function execute(User $owner, string $name): Workspace
    {
        $workspace = Workspace::create([
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::random(6),
            'owner_id' => $owner->id,
        ]);

        setPermissionsTeamId($workspace->id);
        $owner->assignRole(RoleName::WorkspaceOwner->value);

        return $workspace;
    }
}
