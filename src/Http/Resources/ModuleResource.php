<?php

namespace Fortix\Shieldify\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{
    public function toArray($request)
    {
        // Adjust the fields to match your Module model's attributes
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description, // Assuming your Module model has a description
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
