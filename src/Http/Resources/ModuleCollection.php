<?php

namespace Fortix\Shieldify\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ModuleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Example transformation, adjust as needed
        return [
            'data' => $this->collection,
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}
