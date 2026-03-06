<?php

use App\Models\User;
use App\Modules\Auth\Domain\Enums\RoleName;
use App\Modules\Teams\Application\Actions\CreateTeam;
use App\Modules\Teams\Infrastructure\Team;
use App\Modules\Workspaces\Application\Actions\CreateWorkspace;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function (): void {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    setPermissionsTeamId(null);
    $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);
});

test('workspace owner can create a team', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    $team = app(CreateTeam::class)->execute($workspace, $owner, 'Engineering');

    expect($team)->toBeInstanceOf(Team::class)
        ->and($team->name)->toBe('Engineering')
        ->and($team->workspace_id)->toBe($workspace->id)
        ->and($team->owner_id)->toBe($owner->id);
});

test('team creator is automatically added as a member', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    $team = app(CreateTeam::class)->execute($workspace, $owner, 'Engineering');

    expect($team->members()->where('user_id', $owner->id)->exists())->toBeTrue();
});

test('team belongs to the correct workspace', function (): void {
    $ownerA = User::factory()->create();
    $ownerB = User::factory()->create();
    $workspaceA = app(CreateWorkspace::class)->execute($ownerA, 'Workspace A');
    $workspaceB = app(CreateWorkspace::class)->execute($ownerB, 'Workspace B');

    setPermissionsTeamId($workspaceA->id);
    $team = app(CreateTeam::class)->execute($workspaceA, $ownerA, 'Team Alpha');

    expect($team->workspace_id)->toBe($workspaceA->id)
        ->and($team->workspace_id)->not->toBe($workspaceB->id);
});

test('workspace owner can create team via POST', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    $this->actingAs($owner)
        ->post("/workspaces/{$workspace->id}/teams", ['name' => 'Engineering'])
        ->assertRedirect();

    expect(Team::where('workspace_id', $workspace->id)->where('name', 'Engineering')->exists())->toBeTrue();
});

test('workspace viewer cannot create team', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    $viewer = User::factory()->create();
    setPermissionsTeamId($workspace->id);
    $viewer->assignRole(RoleName::WorkspaceViewer->value);

    $this->actingAs($viewer)
        ->post("/workspaces/{$workspace->id}/teams", ['name' => 'Engineering'])
        ->assertForbidden();
});

test('team name is required', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    $this->actingAs($owner)
        ->post("/workspaces/{$workspace->id}/teams", ['name' => ''])
        ->assertSessionHasErrors('name');
});
