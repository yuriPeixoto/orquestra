<?php

namespace App\Modules\Decisions\Infrastructure;

use App\Models\User;
use App\Modules\Decisions\Domain\Enums\DecisionStatus;
use App\Modules\Initiatives\Infrastructure\Initiative;
use App\Modules\Workspaces\Domain\Traits\BelongsToWorkspace;
use Database\Factories\DecisionFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Decision extends Model
{
    /** @use HasFactory<DecisionFactory> */
    use BelongsToWorkspace, HasFactory;

    protected $fillable = [
        'workspace_id',
        'initiative_id',
        'author_id',
        'title',
        'context',
        'decision',
        'consequences',
        'status',
    ];

    protected $casts = [
        'status' => DecisionStatus::class,
    ];

    protected static function newFactory(): Factory
    {
        return DecisionFactory::new();
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Workspaces\Infrastructure\Workspace::class);
    }

    public function initiative(): BelongsTo
    {
        return $this->belongsTo(Initiative::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
