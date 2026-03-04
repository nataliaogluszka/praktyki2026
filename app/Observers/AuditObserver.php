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
        // Pobieramy tylko te pola, które faktycznie się zmieniły
        $changes = $model->getDirty();
        
        if (empty($changes)) return;

        $oldValues = [];
        $newValues = [];

        foreach ($changes as $column => $newValue) {
            // Opcjonalnie: filtrujemy tylko ważne kolumny (np. price, status)
            if (in_array($column, ['price', 'status', 'stock', 'total_price'])) {
                $oldValues[$column] = $model->getOriginal($column);
                $newValues[$column] = $newValue;
            }
        }

        if (!empty($newValues)) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'auditable_type' => get_class($model),
                'auditable_id' => $model->id,
                'event' => 'updated',
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'url' => Request::fullUrl(),
                'ip_address' => Request::ip(),
            ]);
        }
    }
}