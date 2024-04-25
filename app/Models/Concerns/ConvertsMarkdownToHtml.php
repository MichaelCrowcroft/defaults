<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;
use Spatie\LaravelMarkdown\MarkdownRenderer;

trait ConvertsMarkdownToHtml
{
    protected static function getMarkdownToHtmlMap(): array
    {
        return [
            'body' => 'html',
        ];
    }

    protected static function bootConvertsMarkdownToHtml()
    {
        static::saving(function (self $model) {
            $markdownData = collect(self::getMarkdownToHtmlMap())
                ->flip()
                ->map(fn ($bodyColumn) => app(MarkdownRenderer::class)->toHTML($model->$bodyColumn)
            );

            return $model->fill($markdownData->all());
        });
    }
}