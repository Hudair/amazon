<?php
use Incevio\Package\Inspector\Models\InspectorModel;

if (! function_exists('get_inspection_status_name'))
{
    /**
     * get_inspection_status_name
     *
     * @param  int $label
     * @return str
     */
    function get_inspection_status_name($status = 1)
    {
        switch ($status) {
            case InspectorModel::INSPECTION_STATUS_APPROVED: return trans("inspector::lang.approved");
            case InspectorModel::INSPECTION_STATUS_PENDING: return trans("inspector::lang.pending");
            case InspectorModel::INSPECTION_STATUS_BLOCK: return trans("inspector::lang.blocked");
        }

        return '';
    }
}

if (! function_exists('get_item_view_url'))
{
    /**
     * get_view_route_of_item
     *
     */
    function get_item_view_url($inspectable_type, $id)
    {
        foreach (config('inspector.models') as $model) {
            if ($inspectable_type == $model['class']){
               return route($model['show'], $id);
            }
        }

        return '';
    }
}

if (! function_exists('get_item_edit_url'))
{
    /**
     * get_edit_route_of_item
     *
     */
    function get_item_edit_url($inspectable_type, $id)
    {
        foreach (config('inspector.models') as $model) {
            if ($inspectable_type == $model['class']){
                 return route($model['edit'], $id);
            }
        }

        return '';
    }
}
