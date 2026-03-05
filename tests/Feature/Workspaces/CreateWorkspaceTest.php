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

test('workspace can be created with name and slug', function (): void {
    $owner = User::factory()->create();

    $workspace = app(CreateWorkspace::class)->execute($owner, 'My Workspace');

    expect($workspace)->toBeInstanceOf(Workspace::class)
        ->and($workspace->name)->toBe('My Workspace')
        ->and($workspace->slug)->toContain('my-workspace')
        ->and($workspace->owner_id)->toBe($owner->id);
});

test('workspace creator is automatically assigned workspace_owner role', function (): void {
    $owner = User::factory()->create();

    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    expect($owner->hasRole(RoleName::WorkspaceOwner->value))->toBeTrue();
});

test('workspace_owner role is scoped to the workspace', function (): void {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    $workspace = app(CreateWorkspace::class)->execute($owner, 'Workspace A');

    setPermissionsTeamId($workspace->id);
    expect($owner->hasRole(RoleName::WorkspaceOwner->value))->toBeTrue()
        ->and($otherUser->hasRole(RoleName::WorkspaceOwner->value))->toBeFalse();
});

test('workspace owner role from one workspace does not bleed into another', function (): void {
    $owner = User::factory()->create();

    $workspaceA = app(CreateWorkspace::class)->execute($owner, 'Workspace A');
    $workspaceB = app(CreateWorkspace::class)->execute(User::factory()->create(), 'Workspace B');

    setPermissionsTeamId($workspaceA->id);
    expect($owner->hasRole(RoleName::WorkspaceOwner->value))->toBeTrue();

    // Reload relation to avoid Eloquent's cached roles from previous context.
    setPermissionsTeamId($workspaceB->id);
    expect($owner->fresh()->hasRole(RoleName::WorkspaceOwner->value))->toBeFalse();
});

test('workspace can be created via POST /workspaces', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/workspaces', ['name' => 'Test Workspace'])
        ->assertRedirect();

    expect(Workspace::where('owner_id', $user->id)->exists())->toBeTrue();
});

test('workspace creation requires authentication', function (): void {
    $this->post('/workspaces', ['name' => 'Test Workspace'])
        ->assertRedirect('/login');
});

test('workspace name is required', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/workspaces', ['name' => ''])
        ->assertSessionHasErrors('name');
});

test('workspace name must be at least 3 characters', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/workspaces', ['name' => 'AB'])
        ->assertSessionHasErrors('name');
});
