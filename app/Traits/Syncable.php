<?php

namespace App\Traits;

use App\Http\Controllers\Api\SyncController;
use App\Models\Setting;

trait Syncable
{
    /**
     * Boot the trait and register model events to trigger CMS synchronization.
     */
    protected static function bootSyncable()
    {
        $trigger = function ($model) {
            // Prevent infinite loop: if we're updating the sync version itself, don't trigger again
            if ($model instanceof Setting && $model->key === 'cms_sync_version') {
                return;
            }
            SyncController::triggerSync();
        };

        static::saved($trigger);
        static::deleted($trigger);

        if (method_exists(static::class, 'restored')) {
            static::restored($trigger);
        }
    }
}
