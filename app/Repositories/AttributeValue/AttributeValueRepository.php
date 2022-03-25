<?php

namespace App\Repositories\AttributeValue;

interface AttributeValueRepository
{
    public function create($id = null);

    public function getAttribute($id);

    public function reorder(array $attributeValues);
}
