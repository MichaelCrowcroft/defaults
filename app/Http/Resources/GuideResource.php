<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuideResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', fn () => UserResource::make($this->user)),
            'product' => $this->whenLoaded('product', fn () => ProductResource::make($this->product)),
            'name' => $this->name,
            'body' => $this->body,
            'html' => $this->html,
            'icon_path' => $this->icon_path,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'routes' => [
                'show' => $this->showRoute(),
            ],
        ];
    }
}