<?php

namespace App\Common;

use Spatie\Activitylog\Traits\LogsActivity;

trait Loggable
{
    use LogsActivity;

    /**
     * The attributes that will be logged on activity logger.
     *
     * @var bool
     */
    protected static $logFillable = true;

    /**
     * The only attributes that has been changed.
     *
     * @var bool
     */
    protected static $logOnlyDirty = true;

    /**
     * Loggs for the loggable model
     *
     * @return [type] [description]
     */
    public function logs()
    {
        return $this->activities()->orderBy('created_at', 'desc')->get();
    }
}
