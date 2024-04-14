<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

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
                ->map(fn ($bodyColumn) => Str::markdown($model->$bodyColumn, [
                    'html_input' => 'strip',
                    'allow_unsafe_links' => false,
                    'max_nesting_level' => 5,
                ])
            );

            return $model->fill($markdownData->all());
        });
    }
}