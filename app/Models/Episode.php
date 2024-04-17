<?php

namespace App\Models;

use App\Models\Concerns\ConvertsMarkdownToHtml;
use App\Observers\EpisodeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[ObservedBy([EpisodeObserver::class])]
class Episode extends Model
{
    use HasFactory;
    use ConvertsMarkdownToHtml;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }

    public function showRoute(array $parameters = [])
    {
        return route('guides.show', [$this, Str::slug($this->name), ...$parameters]);
    }
}
