<?php

namespace Database\Seeders;

use App\Modules\Auth\Domain\Enums\PermissionName;
use App\Modules\Auth\Domain\Enums\RoleName;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = collect(PermissionName::cases())
            ->map(fn (PermissionName $p) => Permission::firstOrCreate(['name' => $p->value]));

        $allPermissions = $permissions->pluck('name')->toArray();

        $ownerPermissions = [
            PermissionName::ManageWorkspace->value,
            PermissionName::DeleteWorkspace->value,
            PermissionName::InviteMembers->value,
            PermissionName::RemoveMembers->value,
            PermissionName::AssignRoles->value,
            PermissionName::CreateInitiative->value,
            PermissionName::EditInitiative->value,
            PermissionName::DeleteInitiative->value,
            PermissionName::ViewInitiative->value,
            PermissionName::CreateDecision->value,
            PermissionName::EditDecision->value,
            PermissionName::DeleteDecision->value,
            PermissionName::ViewDecision->value,
        ];

        $memberPermissions = [
            PermissionName::CreateInitiative->value,
            PermissionName::EditInitiative->value,
            PermissionName::ViewInitiative->value,
            PermissionName::CreateDecision->value,
            PermissionName::EditDecision->value,
            PermissionName::ViewDecision->value,
        ];

        $viewerPermissions = [
            PermissionName::ViewInitiative->value,
            PermissionName::ViewDecision->value,
        ];

        Role::firstOrCreate(['name' => RoleName::Admin->value])
            ->syncPermissions($allPermissions);

        Role::firstOrCreate(['name' => RoleName::WorkspaceOwner->value])
            ->syncPermissions($ownerPermissions);

        Role::firstOrCreate(['name' => RoleName::WorkspaceMember->value])
            ->syncPermissions($memberPermissions);

        Role::firstOrCreate(['name' => RoleName::WorkspaceViewer->value])
            ->syncPermissions($viewerPermissions);
    }
}
