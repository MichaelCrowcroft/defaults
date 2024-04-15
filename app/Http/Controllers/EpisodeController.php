<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEpisodeRequest;
use App\Http\Requests\UpdateEpisodeRequest;
use App\Models\Episode;

class EpisodeController extends Controller
{
    public function store(StoreEpisodeRequest $request)
    {
        //
    }

    public function show(Episode $episode)
    {
        //
    }

    public function update(UpdateEpisodeRequest $request, Episode $episode)
    {
        //
    }

    public function destroy(Episode $episode)
    {
        //
    }
}
