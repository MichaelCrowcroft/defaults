<?php

namespace App\Observers;

use App\Models\Episode;

class EpisodeObserver
{
    public function creating(Episode $episode): void
    {
        $last_episode = $episode->guide->episodes()->orderBy('order', 'desc')->first();

        $episode->order = $last_episode?->order + 1;
    }
}
