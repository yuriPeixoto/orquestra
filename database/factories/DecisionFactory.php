<?php

namespace Database\Factories;

use App\Models\User;
use App\Modules\Decisions\Domain\Enums\DecisionStatus;
use App\Modules\Decisions\Infrastructure\Decision;
use App\Modules\Workspaces\Infrastructure\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Decision>
 */
class DecisionFactory extends Factory
{
    protected $model = Decision::class;

    public function definition(): array
    {
        return [
            'workspace_id' => Workspace::factory(),
            'initiative_id' => null,
            'author_id' => User::factory(),
            'title' => fake()->sentence(5),
            'context' => fake()->paragraph(),
            'decision' => fake()->paragraph(),
            'consequences' => fake()->optional()->paragraph(),
            'status' => DecisionStatus::Proposed,
        ];
    }
}
