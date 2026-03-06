<?php

use App\Models\User;
use App\Modules\Auth\Domain\Enums\RoleName;
use App\Modules\Workspaces\Application\Actions\CreateWorkspace;
use App\Modules\Workspaces\Infrastructure\Workspace;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function (): void {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    setPermissionsTeamId(null);
    $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);
});

test('workspace member can access workspace route', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Test Workspace');

    $this->actingAs($owner)
        ->get("/workspaces/{$workspace->id}")
        ->assertStatus(200);
});

test('unauthenticated user cannot access workspace route', function (): void {
    $workspace = Workspace::factory()->create();

    $this->get("/workspaces/{$workspace->id}")
        ->assertRedirect('/login');
});

test('user without workspace role gets 403', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Test Workspace');

    $outsider = User::factory()->create();

    $this->actingAs($outsider)
        ->get("/workspaces/{$workspace->id}")
        ->assertForbidden();
});

test('global admin can access any workspace', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Test Workspace');

    $admin = User::factory()->create();
    setPermissionsTeamId(0);
    $admin->assignRole(RoleName::Admin->value);

    $this->actingAs($admin)
        ->get("/workspaces/{$workspace->id}")
        ->assertStatus(200);
});

test('workspace access is isolated per workspace per request', function (): void {
    $ownerA = User::factory()->create();
    $ownerB = User::factory()->create();
    $workspaceA = app(CreateWorkspace::class)->execute($ownerA, 'Workspace A');
    $workspaceB = app(CreateWorkspace::class)->execute($ownerB, 'Workspace B');

    // ownerA can access workspaceA but is blocked from workspaceB
    $this->actingAs($ownerA)->get("/workspaces/{$workspaceA->id}")->assertOk();
    $this->actingAs($ownerA)->get("/workspaces/{$workspaceB->id}")->assertForbidden();

    // ownerB has the inverse access
    $this->actingAs($ownerB)->get("/workspaces/{$workspaceB->id}")->assertOk();
    $this->actingAs($ownerB)->get("/workspaces/{$workspaceA->id}")->assertForbidden();
});

test('workspace viewer role grants read access', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Test Workspace');

    $viewer = User::factory()->create();
    setPermissionsTeamId($workspace->id);
    $viewer->assignRole(RoleName::WorkspaceViewer->value);

    $this->actingAs($viewer)
        ->get("/workspaces/{$workspace->id}")
        ->assertStatus(200);
});
