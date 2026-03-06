<?php

use App\Models\User;
use App\Modules\Auth\Domain\Enums\RoleName;
use App\Modules\Teams\Application\Actions\AddTeamMember;
use App\Modules\Teams\Application\Actions\CreateTeam;
use App\Modules\Teams\Application\Actions\RemoveTeamMember;
use App\Modules\Workspaces\Application\Actions\CreateWorkspace;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function (): void {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    setPermissionsTeamId(null);
    $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);
});

test('workspace member can be added to a team', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    $member = User::factory()->create();
    setPermissionsTeamId($workspace->id);
    $member->assignRole(RoleName::WorkspaceMember->value);

    $team = app(CreateTeam::class)->execute($workspace, $owner, 'Engineering');

    app(AddTeamMember::class)->execute($team, $member);

    expect($team->members()->where('user_id', $member->id)->exists())->toBeTrue();
});

test('user without workspace role cannot be added to a team', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    $team = app(CreateTeam::class)->execute($workspace, $owner, 'Engineering');

    $outsider = User::factory()->create(); // no workspace role

    expect(fn () => app(AddTeamMember::class)->execute($team, $outsider))
        ->toThrow(AuthorizationException::class);
});

test('adding the same member twice is idempotent', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    $team = app(CreateTeam::class)->execute($workspace, $owner, 'Engineering');

    app(AddTeamMember::class)->execute($team, $owner); // owner is already a member

    expect($team->members()->where('user_id', $owner->id)->count())->toBe(1);
});

test('member can be removed from a team', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    $member = User::factory()->create();
    setPermissionsTeamId($workspace->id);
    $member->assignRole(RoleName::WorkspaceMember->value);

    $team = app(CreateTeam::class)->execute($workspace, $owner, 'Engineering');
    app(AddTeamMember::class)->execute($team, $member);

    app(RemoveTeamMember::class)->execute($team, $member);

    expect($team->members()->where('user_id', $member->id)->exists())->toBeFalse();
});

test('team owner cannot be removed', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    $team = app(CreateTeam::class)->execute($workspace, $owner, 'Engineering');

    expect(fn () => app(RemoveTeamMember::class)->execute($team, $owner))
        ->toThrow(AuthorizationException::class);
});

test('workspace owner can add member via POST', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    $member = User::factory()->create();
    setPermissionsTeamId($workspace->id);
    $member->assignRole(RoleName::WorkspaceMember->value);

    setPermissionsTeamId($workspace->id);
    $team = app(CreateTeam::class)->execute($workspace, $owner, 'Engineering');

    $this->actingAs($owner)
        ->post("/workspaces/{$workspace->id}/teams/{$team->id}/members", [
            'user_id' => $member->id,
        ])
        ->assertRedirect();

    expect($team->members()->where('user_id', $member->id)->exists())->toBeTrue();
});
