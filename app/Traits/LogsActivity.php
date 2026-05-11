<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    /**
     * Log an administrative activity.
     *
     * @param string $action
     * @param string $description
     * @param mixed $model
     * @param array|null $changes
     * @return void
     */
    protected function logActivity($action, $description, $model = null, $changes = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model' => $model ? class_basename($model) : 'System',
            'model_id' => $model ? $model->id : '0',
            'description' => $description,
            'changes' => $changes,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
