<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EpisodeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', fn () => UserResource::make($this->user)),
            'guide' => $this->whenLoaded('guide', fn () => GuideResource::make($this->guide)),
            'order' => $this->order,
            'name' => $this->name,
            'body' => $this->body,
            'html' => $this->html,
            'video_url' => $this->video_url,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'routes' => [
                'show' => $this->showRoute(),
            ],
        ];
    }
}