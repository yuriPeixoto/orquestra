<?php

namespace Database\Factories;

use App\Models\User;
use App\Modules\Initiatives\Domain\Enums\InitiativeStatus;
use App\Modules\Initiatives\Infrastructure\Initiative;
use App\Modules\Workspaces\Infrastructure\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Initiative>
 */
class InitiativeFactory extends Factory
{
    protected $model = Initiative::class;

    public function definition(): array
    {
        return [
            'workspace_id' => Workspace::factory(),
            'owner_id' => User::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'status' => InitiativeStatus::Draft,
            'due_date' => fake()->optional()->dateTimeBetween('now', '+6 months'),
        ];
    }
}
