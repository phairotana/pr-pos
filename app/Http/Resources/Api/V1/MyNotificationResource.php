<?php

namespace App\Http\Resources\Api\V1;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MyNotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'present_type' => $this->PresentType,
            'data' => json_decode($this->data, true),
            'read_at' => $this->read_at,
            'human_read_at' => $this->DiffHumanReadAt,
            'created_at' => $this->created_at,
            'human_created_at' => $this->DiffHumanCreatedAt,
            'updated_at' => $this->updated_at,
        ];
    }
}
