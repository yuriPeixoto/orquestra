<?php

namespace App\Modules\Workspaces\Domain\Traits;

use App\Modules\Workspaces\Infrastructure\Workspace;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trait for Eloquent models that belong to a workspace (tenant scope).
 *
 * Usage: add `use BelongsToWorkspace;` to any tenant model.
 * The model's table must have a `workspace_id` FK column.
 */
trait BelongsToWorkspace
{
    public static function bootBelongsToWorkspace(): void
    {
        static::addGlobalScope('workspace', function (Builder $query): void {
            if (app()->bound('current.workspace')) {
                $query->where('workspace_id', app('current.workspace')->id);
            }
        });

        static::creating(function (self $model): void {
            if (! isset($model->workspace_id) && app()->bound('current.workspace')) {
                $model->workspace_id = app('current.workspace')->id;
            }
        });
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }
}
