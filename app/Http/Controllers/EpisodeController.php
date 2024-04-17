<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEpisodeRequest;
use App\Http\Requests\UpdateEpisodeRequest;
use App\Models\Episode;
use App\Models\Guide;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class EpisodeController extends Controller
{
    public function store(StoreEpisodeRequest $request, Guide $guide)
    {
        $data = $request->validated();

        $guide->episodes()->create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return Redirect::to($guide->showRoute(['page' => $request->query('page')]))
            ->banner('Review Added');
    }

    public function show(Episode $episode)
    {
        //
    }

    public function update(UpdateEpisodeRequest $request, Episode $episode)
    {
        $data = $request->validated();

        $episode->update($data);

        return Redirect::to($episode->guide->showRoute(['page' => $request->query('page')]))
            ->banner('Episode Updated');
    }

    public function destroy(Episode $episode)
    {
        //
    }
}
