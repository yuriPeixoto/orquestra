<?php

namespace Database\Factories;

use App\Modules\Teams\Infrastructure\Team;
use App\Modules\Workspaces\Infrastructure\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        $name = fake()->words(2, true);

        return [
            'workspace_id' => Workspace::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::random(4),
            'owner_id' => \App\Models\User::factory(),
        ];
    }
}
