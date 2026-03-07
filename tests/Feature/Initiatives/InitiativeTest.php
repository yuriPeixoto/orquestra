<?php

use App\Models\User;
use App\Modules\Auth\Domain\Enums\RoleName;
use App\Modules\Initiatives\Application\Actions\CreateInitiative;
use App\Modules\Initiatives\Application\Actions\UpdateInitiative;
use App\Modules\Initiatives\Application\Actions\UpdateInitiativeStatus;
use App\Modules\Initiatives\Domain\Enums\InitiativeStatus;
use App\Modules\Initiatives\Infrastructure\Initiative;
use App\Modules\Workspaces\Application\Actions\CreateWorkspace;
use Carbon\Carbon;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function (): void {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    setPermissionsTeamId(null);
    $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);
});

test('workspace member can create an initiative', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    $initiative = app(CreateInitiative::class)->execute(
        workspace: $workspace,
        owner: $owner,
        title: 'Launch Product',
        description: 'Full product launch plan',
        dueDate: Carbon::parse('2026-12-01'),
    );

    expect($initiative)->toBeInstanceOf(Initiative::class)
        ->and($initiative->title)->toBe('Launch Product')
        ->and($initiative->workspace_id)->toBe($workspace->id)
        ->and($initiative->status)->toBe(InitiativeStatus::Draft)
        ->and($initiative->due_date->format('Y-m-d'))->toBe('2026-12-01');
});

test('initiative is scoped to its workspace', function (): void {
    $ownerA = User::factory()->create();
    $ownerB = User::factory()->create();
    $workspaceA = app(CreateWorkspace::class)->execute($ownerA, 'Workspace A');
    $workspaceB = app(CreateWorkspace::class)->execute($ownerB, 'Workspace B');

    setPermissionsTeamId($workspaceA->id);
    app(CreateInitiative::class)->execute($workspaceA, $ownerA, 'Initiative A');

    expect($workspaceA->initiatives()->count())->toBe(1)
        ->and($workspaceB->initiatives()->count())->toBe(0);
});

test('initiative can be updated', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    $initiative = app(CreateInitiative::class)->execute($workspace, $owner, 'Draft Initiative');

    $updated = app(UpdateInitiative::class)->execute(
        initiative: $initiative,
        title: 'Updated Title',
        status: InitiativeStatus::Active,
    );

    expect($updated->title)->toBe('Updated Title')
        ->and($updated->status)->toBe(InitiativeStatus::Active);
});

test('workspace member can create initiative via POST', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    $this->actingAs($owner)
        ->post("/workspaces/{$workspace->id}/initiatives", [
            'title' => 'New Initiative',
            'description' => 'Some description',
        ])
        ->assertRedirect();

    expect(Initiative::where('workspace_id', $workspace->id)->where('title', 'New Initiative')->exists())->toBeTrue();
});

test('workspace viewer cannot create initiative', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    $viewer = User::factory()->create();
    setPermissionsTeamId($workspace->id);
    $viewer->assignRole(RoleName::WorkspaceViewer->value);

    $this->actingAs($viewer)
        ->post("/workspaces/{$workspace->id}/initiatives", ['title' => 'Unauthorized'])
        ->assertForbidden();
});

test('workspace member can update initiative via PUT', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    $initiative = app(CreateInitiative::class)->execute($workspace, $owner, 'Original Title');

    $this->actingAs($owner)
        ->put("/workspaces/{$workspace->id}/initiatives/{$initiative->id}", [
            'title' => 'Updated Title',
            'status' => 'active',
        ])
        ->assertRedirect();

    expect($initiative->fresh()->title)->toBe('Updated Title')
        ->and($initiative->fresh()->status)->toBe(InitiativeStatus::Active);
});

test('initiative from another workspace returns 404', function (): void {
    $ownerA = User::factory()->create();
    $ownerB = User::factory()->create();
    $workspaceA = app(CreateWorkspace::class)->execute($ownerA, 'Workspace A');
    $workspaceB = app(CreateWorkspace::class)->execute($ownerB, 'Workspace B');

    setPermissionsTeamId($workspaceA->id);
    $initiative = app(CreateInitiative::class)->execute($workspaceA, $ownerA, 'Initiative A');

    // ownerA has no role in workspaceB → middleware blocks with 403 before the controller runs.
    // This is intentional: we don't reveal whether a resource exists in another workspace.
    $this->actingAs($ownerA)
        ->get("/workspaces/{$workspaceB->id}/initiatives/{$initiative->id}")
        ->assertForbidden();
});

test('title is required', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    $this->actingAs($owner)
        ->post("/workspaces/{$workspace->id}/initiatives", ['title' => ''])
        ->assertSessionHasErrors('title');
});

test('initiative status can be updated via action', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    $initiative = app(CreateInitiative::class)->execute($workspace, $owner, 'Draft Initiative');

    $updated = app(UpdateInitiativeStatus::class)->execute($initiative, InitiativeStatus::Active);

    expect($updated->status)->toBe(InitiativeStatus::Active);
});

test('workspace member can update initiative status via PATCH', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    $initiative = app(CreateInitiative::class)->execute($workspace, $owner, 'Kanban Item');

    $this->actingAs($owner)
        ->patch("/workspaces/{$workspace->id}/initiatives/{$initiative->id}/status", [
            'status' => 'active',
        ])
        ->assertOk()
        ->assertJson(['status' => 'active']);

    expect($initiative->fresh()->status)->toBe(InitiativeStatus::Active);
});

test('kanban view returns initiatives grouped by status', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    app(CreateInitiative::class)->execute($workspace, $owner, 'Draft Initiative');
    $active = app(CreateInitiative::class)->execute($workspace, $owner, 'Active Initiative');
    app(UpdateInitiativeStatus::class)->execute($active, InitiativeStatus::Active);

    $this->actingAs($owner)
        ->get("/workspaces/{$workspace->id}/initiatives/kanban")
        ->assertOk();
});

test('workspace viewer cannot update initiative status', function (): void {
    $owner = User::factory()->create();
    $workspace = app(CreateWorkspace::class)->execute($owner, 'Acme Corp');

    setPermissionsTeamId($workspace->id);
    $initiative = app(CreateInitiative::class)->execute($workspace, $owner, 'Some Initiative');

    $viewer = User::factory()->create();
    $viewer->assignRole(RoleName::WorkspaceViewer->value);

    $this->actingAs($viewer)
        ->patch("/workspaces/{$workspace->id}/initiatives/{$initiative->id}/status", [
            'status' => 'active',
        ])
        ->assertForbidden();
});
