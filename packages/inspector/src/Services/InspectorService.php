<?php

namespace Incevio\Package\Inspector\Services;

use App\BaseModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Incevio\Package\Inspector\Jobs\AfterInspectionJob;
use Incevio\Package\Inspector\Models\InspectorModel;
use Incevio\Package\Inspector\Notifications\Inspecting;
use Incevio\Package\Inspector\src\Notifications\ApproveInspected;

class InspectorService
{
    /**
     * The inspectable data to be filtered by inspector
     */
    private $inspectable;

    /**
     * The model to be filtered by inspector
     */
    public $model;

    /**
     * The status by inspector
     */
    public $caught;

    public function __construct($model)
    {
        $this->model = $model;
        $this->caught = Null;
    }

    /**
     * Set inspectable data to be filtered by inspector
     *
     * @return self
     */
    public function inspectable($inspectable = []): self
    {
        $this->inspectable = $inspectable;

        return $this;
    }

    /**
     * Filter model data and return false is caught by inspector filter
     */
    public function filter(): self
    {
        $restricted = get_from_option_table('inspector_keywords', []);

        // Return if has no restricted keyword or inspectable content
        if (empty($restricted) || empty($this->inspectable)) {
            return $this;
        }

        foreach ($this->inspectable as $filter) {
            // Skip if the dataset doesn't have the inspectable key
            if (! $this->model->$filter) {
                continue;
            }

            $match = array_filter($restricted, function($keyword) use ($filter) {
                return stripos($this->model->$filter, $keyword);
                // return Str::contains($this->model->$filter, $keyword);
            });

            // Found a match
            if ($match){
                $this->caught = $match;

                return $this;
            }
        }

        return $this;
    }

    /**
     * Filter model data and return false is caught by inspector filter
     */
    public function inspect(): self
    {
        $inspection = $this->model->inspection;

        if ($this->caught) {
            // Using Query builder to avoid the inspection loop
            DB::table($this->model->getTable())->where('id', $this->model->id)
                ->update([
                    'inspection_status' => InspectorModel::INSPECTION_STATUS_PENDING,
                    'active' => BaseModel::INACTIVE
                ]);

            if ($inspection) { // Update the status to pending again
                $inspection->update([
                    'status' => InspectorModel::INSPECTION_STATUS_PENDING,
                    'caught' => implode(' ', $this->caught),
                    'attempts' => $inspection->attempts + 1,
                ]);
            }
            else {
                $create = $this->model->inspection()->create();

                InspectorModel::where('id', $create->id)->update([
                    'caught' => implode(' ', $this->caught),
                ]);
            }

            // Dispatch the after inspection Job
            AfterInspectionJob::dispatch($this, Inspecting::class);

        }
        // If passed then delete previous inspection
        else if($inspection) {
            //delete Inspection
            $inspection->delete();

            // Using Query builder to avoid the inspection loop
            DB::table($this->model->getTable())->where('id', $this->model->id)->update([
                    'inspection_status' => InspectorModel::INSPECTION_STATUS_APPROVED,
                    'active' => InspectorModel::ACTIVE,
                ]);

            // Dispatch the after Auto Approved the inspected content.
            AfterInspectionJob::dispatch($this, ApproveInspected::class);
        }
        else{
            //If no prohibited key and no Inspection are found!
            DB::table($this->model->getTable())->where('id', $this->model->id)
                ->update(['inspection_status' => InspectorModel::INSPECTION_STATUS_APPROVED]);

        }

        return $this;
    }

    /**
     * Approve Inspected Items
     */
    public function approveInspectedItem($id): self
    {
        $class = get_class($this->model);

        $update = DB::table($this->model->getTable())->where('id', $id)->update([
            'active' => InspectorModel::ACTIVE,
            'inspection_status' => InspectorModel::INSPECTION_STATUS_APPROVED,
            ]);

        if ($update){
            InspectorModel::where('inspectable_type', $class)->where('inspectable_id', $id)->delete();
        }

        return $this;
    }

    /**
     * Block Inspected Items
     */
    public function blockInspectedItem($id): self
    {
        $class = get_class($this->model);

        $update = DB::table($this->model->getTable())->where('id', $id)->update([
            'inspection_status' => InspectorModel::INSPECTION_STATUS_BLOCK,
            'active' => InspectorModel::INACTIVE,
            ]);

        if ($update) {
            InspectorModel::where('inspectable_id', $id)->where('inspectable_type', $class)->delete();
        }

        return $this;
    }


}
