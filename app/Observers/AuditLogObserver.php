<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditLogObserver
{
    /**
     * Handle the "created" event for the given model.
     */
    public function created(Model $model): void
    {
        $this->record($model, 'created', $model->getAttributes());
    }

    /**
     * Handle the "updated" event for the given model.
     */
    public function updated(Model $model): void
    {
        $this->record($model, 'updated', $model->getChanges());
    }

    /**
     * Handle the "deleted" event for the given model.
     */
    public function deleted(Model $model): void
    {
        $this->record($model, 'deleted', null);
    }

    /**
     * Create an audit log entry for the given model event.
     *
     * @param  array<string, mixed>|null  $changes
     */
    private function record(Model $model, string $action, ?array $changes): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->getKey(),
            'changes' => $changes,
        ]);
    }
}
