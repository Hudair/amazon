<?php

namespace App\Repositories\Attribute;

use App\Models\Attribute;
use App\Repositories\BaseRepository;
use App\Repositories\EloquentRepository;
use Illuminate\Support\Facades\Auth;

class EloquentAttribute extends EloquentRepository implements BaseRepository, AttributeRepository
{
    protected $model;

    public function __construct(Attribute $attribute)
    {
        $this->model = $attribute;
    }

    public function all()
    {
        $query = $this->model->with('attributeType')
            ->withCount(['attributeValues', 'categories']);

        if (!Auth::user()->isFromPlatform()) {
            $query->mine();
        }

        return $query->get();
    }

    public function trashOnly()
    {
        $query = $this->model->onlyTrashed()->with('attributeType');

        if (!Auth::user()->isFromPlatform()) {
            $query->mine();
        }

        return $query->get();
    }

    public function entities($id)
    {
        $entities['attribute'] = parent::find($id);

        $entities['attributeValues'] = $entities['attribute']->attributeValues()->with('image')->get();

        $entities['trashes'] = $entities['attribute']->attributeValues()->onlyTrashed()->get();

        return $entities;
    }

    public function reorder(array $attributes)
    {
        foreach ($attributes as $id => $order) {
            $this->model->findOrFail($id)->update(['order' => $order]);
        }

        return true;
    }

    public function getAttributeTypeId($attribute)
    {
        return $this->model->findOrFail($attribute)->attribute_type_id;
    }

    public function destroy($attribute)
    {
        if (!$attribute instanceof Attribute) {
            $attribute = parent::findTrash($attribute);
        }

        $attributeValues = $attribute->attributeValues()->get();

        foreach ($attributeValues as $entity) {
            $entity->deleteImage();
        }

        return $attribute->forceDelete();
    }
}
