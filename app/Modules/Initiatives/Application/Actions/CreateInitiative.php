<?php

namespace App\Modules\Initiatives\Application\Actions;

use App\Models\User;
use App\Modules\Initiatives\Domain\Enums\InitiativeStatus;
use App\Modules\Initiatives\Infrastructure\Initiative;
use App\Modules\Workspaces\Infrastructure\Workspace;
use Carbon\Carbon;

class CreateInitiative
{
    public function execute(
        Workspace $workspace,
        User $owner,
        string $title,
        ?string $description = null,
        ?Carbon $dueDate = null,
        InitiativeStatus $status = InitiativeStatus::Draft,
    ): Initiative {
        return Initiative::create([
            'workspace_id' => $workspace->id,
            'owner_id' => $owner->id,
            'title' => $title,
            'description' => $description,
            'status' => $status,
            'due_date' => $dueDate,
        ]);
    }
}
