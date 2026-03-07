<?php

namespace App\Modules\Decisions\Application\Actions;

use App\Models\User;
use App\Modules\Decisions\Domain\Enums\DecisionStatus;
use App\Modules\Decisions\Infrastructure\Decision;
use App\Modules\Initiatives\Infrastructure\Initiative;
use App\Modules\Workspaces\Infrastructure\Workspace;

class CreateDecision
{
    public function execute(
        Workspace $workspace,
        User $author,
        string $title,
        string $context,
        string $decision,
        ?string $consequences = null,
        DecisionStatus $status = DecisionStatus::Proposed,
        ?Initiative $initiative = null,
    ): Decision {
        return Decision::create([
            'workspace_id' => $workspace->id,
            'initiative_id' => $initiative?->id,
            'author_id' => $author->id,
            'title' => $title,
            'context' => $context,
            'decision' => $decision,
            'consequences' => $consequences,
            'status' => $status,
        ]);
    }
}
