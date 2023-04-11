<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class TodoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'creation_date' => date_format($this->created_at,"Y-m-d"),
            'done' => $this->done
        ];
    }
}
