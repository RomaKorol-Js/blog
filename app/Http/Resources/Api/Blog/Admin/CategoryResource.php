<?php

namespace App\Http\Resources\Api\Blog\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Трансформація ресурсу в масив.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $this вказує на поточний об'єкт моделі Category
            //         'title',
            // 'parent_id',
            // 'description',
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'parent_id'      => $this->parent_id,
            'category_description'   => $this->description,
            'parent_category' => $this->parentCategory?->title,
        ];
    }
}