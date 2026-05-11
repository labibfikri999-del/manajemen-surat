<?php

namespace App\Traits;

use App\Models\SuratAudit;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

trait LogsAudit
{
    public static function bootLogsAudit()
    {
        static::created(function ($model) {
            $model->logAudit('created', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $newValues = Arr::except($model->getChanges(), ['updated_at']);

            if (!empty($newValues)) {
                $oldValues = Arr::only($model->getOriginal(), array_keys($newValues));
                $model->logAudit('updated', $oldValues, $newValues);
            }
        });

        static::deleted(function ($model) {
            $model->logAudit('deleted', $model->getAttributes(), null);
        });
    }

    public function logAudit($action, $oldValues = null, $newValues = null)
    {
        SuratAudit::create([
            'user_id' => Auth::id(),
            'auditable_type' => get_class($this),
            'auditable_id' => $this->id,
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
        ]);
    }

    public function audits()
    {
        return $this->morphMany(SuratAudit::class, 'auditable')->latest();
    }
}
