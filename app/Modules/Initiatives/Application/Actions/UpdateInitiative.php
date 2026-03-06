<?php

namespace App\Modules\Initiatives\Application\Actions;

use App\Modules\Initiatives\Domain\Enums\InitiativeStatus;
use App\Modules\Initiatives\Infrastructure\Initiative;
use Carbon\Carbon;

class UpdateInitiative
{
    public function execute(
        Initiative $initiative,
        string $title,
        ?string $description = null,
        ?Carbon $dueDate = null,
        ?InitiativeStatus $status = null,
    ): Initiative {
        $initiative->update([
            'title' => $title,
            'description' => $description,
            'due_date' => $dueDate,
            'status' => $status ?? $initiative->status,
        ]);

        return $initiative->fresh();
    }
}
