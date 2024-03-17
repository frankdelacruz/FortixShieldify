<?php

namespace Fortix\Shieldify\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'role_id' => $this->role_id,
            'module_id' => $this->module_id,
            'permissions' => json_decode($this->permissions),
            // Include any other permission attributes or related models you need
        ];
    }
}
