<?php

namespace Incevio\Package\Inspector\Traits;

use Incevio\Package\Inspector\Models\InspectorModel;
use Incevio\Package\Inspector\Services\InspectorService;

trait HasInspector
{
    public static function boot()
    {
        parent::boot();

        static::saved(function($model){
            // Inspector package
            if (is_incevio_package_loaded(['inspector'])) {

                if(app()->runningInConsole()){ // Skip in console run
                    return;
                }

                if (property_exists($model, 'inspectable') && ! empty($model::$inspectable)) {
                    $inspector = new InspectorService($model);

                    $inspector->inspectable($model::$inspectable)
                            ->filter()
                            ->inspect();

                }
            }
        });
    }

    public function inspection()
    {
        return $this->morphOne(InspectorModel::class, 'inspectable');
    }

    /**
     * Check if the model is currenctly in inspection
     *
     * @return bool
     */
    public function inInspection()
    {
        return $this->inspection_status != InspectorModel::INSPECTION_STATUS_APPROVED;
    }

    /**
     * Return Inspection Status text
     */
    public function getInspectionStatus($plain = Null)
    {
        $status = strtoupper(get_inspection_status_name($this->inspection_status));

        if($plain) {
            return $status;
        }

        switch ($this->inspection_status) {
            case InspectorModel::INSPECTION_STATUS_APPROVED:
                return '<span class="label label-success">' . $status . '</span>';

            case InspectorModel::INSPECTION_STATUS_PENDING:
                return '<span class="label label-warning">' . $status . '</span>';

            case InspectorModel::INSPECTION_STATUS_BLOCK:
                return '<span class="label label-danger">' . $status . '</span>';
        }

        return '';
    }
}