<?php

namespace App\Modules\Initiatives\Application\Actions;

use App\Modules\Initiatives\Domain\Enums\InitiativeStatus;
use App\Modules\Initiatives\Infrastructure\Initiative;

class UpdateInitiativeStatus
{
    public function execute(Initiative $initiative, InitiativeStatus $status): Initiative
    {
        $initiative->update(['status' => $status]);

        return $initiative->fresh();
    }
}
