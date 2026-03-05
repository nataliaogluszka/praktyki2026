<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class AuditObserver
{
    public function updated(Model $model)
    {
        $changes = $model->getDirty();
        
        if (empty($changes)) return;

        $oldValues = [];
        $newValues = [];

        $ignoredColumns = ['updated_at', 'created_at', 'deleted_at'];

        foreach ($changes as $column => $newValue) {
            if (!in_array($column, $ignoredColumns)) {
                $oldValues[$column] = $model->getOriginal($column);
                $newValues[$column] = $newValue;
            }
        }

        if (!empty($newValues)) {
            $this->saveLog($model, 'updated', $oldValues, $newValues);
        }
    }

    public function deleted(Model $model)
    {
        $this->saveLog($model, 'deleted', $model->toArray(), null);
    }

    protected function saveLog(Model $model, string $event, ?array $old, ?array $new)
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'event' => $event,
            'old_values' => $old,
            'new_values' => $new,
            'url' => Request::fullUrl(),
            'ip_address' => Request::ip(),
        ]);
    }
}