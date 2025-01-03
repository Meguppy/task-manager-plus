<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'deadline_at' => $this->deadline_at,
            'deadline_at_formatted' => $this->deadline_at_formatted,
            'user' => UserResource::make($this->user),
            'done_at' => $this->done_at,
            'is_overdue' => $this->is_overdue,
        ];
    }
}
