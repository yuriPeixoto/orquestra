<?php

namespace App\Modules\Workspaces\Infrastructure;

use App\Models\User;
use App\Modules\Teams\Infrastructure\Team;
use Database\Factories\WorkspaceFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workspace extends Model
{
    /** @use HasFactory<\Database\Factories\WorkspaceFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'owner_id',
    ];

    protected static function newFactory(): Factory
    {
        return WorkspaceFactory::new();
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
}
