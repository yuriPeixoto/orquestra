<?php

use App\Models\User;
use App\Modules\Auth\Domain\Enums\PermissionName;
use App\Modules\Auth\Domain\Enums\RoleName;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function (): void {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);
});

test('user can be assigned a role', function (): void {
    $user = User::factory()->create();
    $user->assignRole(RoleName::WorkspaceMember->value);

    expect($user->hasRole(RoleName::WorkspaceMember->value))->toBeTrue();
});

test('admin has all permissions', function (): void {
    $user = User::factory()->create();
    $user->assignRole(RoleName::Admin->value);

    expect($user->can(PermissionName::ManagePlatform->value))->toBeTrue()
        ->and($user->can(PermissionName::ManageWorkspace->value))->toBeTrue()
        ->and($user->can(PermissionName::CreateInitiative->value))->toBeTrue()
        ->and($user->can(PermissionName::ViewDecision->value))->toBeTrue();
});

test('workspace_owner has management permissions but not manage_platform', function (): void {
    $user = User::factory()->create();
    $user->assignRole(RoleName::WorkspaceOwner->value);

    expect($user->can(PermissionName::ManageWorkspace->value))->toBeTrue()
        ->and($user->can(PermissionName::InviteMembers->value))->toBeTrue()
        ->and($user->can(PermissionName::DeleteInitiative->value))->toBeTrue()
        ->and($user->can(PermissionName::ManagePlatform->value))->toBeFalse();
});

test('workspace_member can create and edit but not manage workspace', function (): void {
    $user = User::factory()->create();
    $user->assignRole(RoleName::WorkspaceMember->value);

    expect($user->can(PermissionName::CreateInitiative->value))->toBeTrue()
        ->and($user->can(PermissionName::EditDecision->value))->toBeTrue()
        ->and($user->can(PermissionName::ManageWorkspace->value))->toBeFalse()
        ->and($user->can(PermissionName::DeleteInitiative->value))->toBeFalse();
});

test('workspace_viewer has only view permissions', function (): void {
    $user = User::factory()->create();
    $user->assignRole(RoleName::WorkspaceViewer->value);

    expect($user->can(PermissionName::ViewInitiative->value))->toBeTrue()
        ->and($user->can(PermissionName::ViewDecision->value))->toBeTrue()
        ->and($user->can(PermissionName::CreateInitiative->value))->toBeFalse()
        ->and($user->can(PermissionName::EditDecision->value))->toBeFalse();
});

test('route protected by role middleware returns 403 for user without role', function (): void {
    Route::get('/test-admin-only', fn () => response('ok'))
        ->middleware(['web', 'role:admin']);

    $user = User::factory()->create();
    $user->assignRole(RoleName::WorkspaceMember->value);

    $this->actingAs($user)->get('/test-admin-only')->assertForbidden();
});

test('route protected by role middleware passes for user with role', function (): void {
    Route::get('/test-admin-pass', fn () => response('ok'))
        ->middleware(['web', 'role:admin']);

    $user = User::factory()->create();
    $user->assignRole(RoleName::Admin->value);

    $this->actingAs($user)->get('/test-admin-pass')->assertOk();
});

test('route protected by permission middleware returns 403 without permission', function (): void {
    Route::get('/test-permission', fn () => response('ok'))
        ->middleware(['web', 'permission:manage_platform']);

    $user = User::factory()->create();
    $user->assignRole(RoleName::WorkspaceViewer->value);

    $this->actingAs($user)->get('/test-permission')->assertForbidden();
});
