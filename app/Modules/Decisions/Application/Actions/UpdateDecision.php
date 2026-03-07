<?php

namespace App\Modules\Decisions\Application\Actions;

use App\Modules\Decisions\Domain\Enums\DecisionStatus;
use App\Modules\Decisions\Infrastructure\Decision;
use App\Modules\Initiatives\Infrastructure\Initiative;

class UpdateDecision
{
    public function execute(
        Decision $decision,
        string $title,
        string $context,
        string $decision_text,
        ?string $consequences = null,
        ?DecisionStatus $status = null,
        ?Initiative $initiative = null,
        bool $unlinkInitiative = false,
    ): Decision {
        $decision->update([
            'title' => $title,
            'context' => $context,
            'decision' => $decision_text,
            'consequences' => $consequences,
            'status' => $status ?? $decision->status,
            'initiative_id' => $unlinkInitiative ? null : ($initiative?->id ?? $decision->initiative_id),
        ]);

        return $decision->fresh();
    }
}
