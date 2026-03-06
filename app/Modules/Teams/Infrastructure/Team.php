<?php

namespace App\Modules\Teams\Infrastructure;

use App\Models\User;
use App\Modules\Workspaces\Domain\Traits\BelongsToWorkspace;
use App\Modules\Workspaces\Infrastructure\Workspace;
use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    /** @use HasFactory<TeamFactory> */
    use BelongsToWorkspace, HasFactory;

    protected $fillable = [
        'workspace_id',
        'name',
        'slug',
        'owner_id',
    ];

    protected static function newFactory(): Factory
    {
        return TeamFactory::new();
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_members')->withTimestamps();
    }
}
