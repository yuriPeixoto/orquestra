<?php

namespace App\Modules\Initiatives\Infrastructure;

use App\Models\User;
use App\Modules\Initiatives\Domain\Enums\InitiativeStatus;
use App\Modules\Workspaces\Domain\Traits\BelongsToWorkspace;
use App\Modules\Workspaces\Infrastructure\Workspace;
use Database\Factories\InitiativeFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Initiative extends Model
{
    /** @use HasFactory<InitiativeFactory> */
    use BelongsToWorkspace, HasFactory;

    protected $fillable = [
        'workspace_id',
        'owner_id',
        'title',
        'description',
        'status',
        'due_date',
    ];

    protected $casts = [
        'status' => InitiativeStatus::class,
        'due_date' => 'date',
    ];

    protected static function newFactory(): Factory
    {
        return InitiativeFactory::new();
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
