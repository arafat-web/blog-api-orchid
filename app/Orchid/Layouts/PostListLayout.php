<?php

namespace App\Orchid\Layouts;

use App\Models\Post;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class PostListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'posts';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('title', 'Title')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function (Post $post) {
                    return Link::make($post->title)
                        ->style('text-decoration: underline;')
                        ->route('platform.post.edit', $post->id);
                }),
            TD::make('image', 'Image')
                ->render(function (Post $post) {
                    $image = $post->attachment()->first();
                    return '<image src="' . $image->url() . '" width="80" height="80"/>';
                }),

            TD::make('slug', 'Slug'),
            TD::make('Category')
                ->render(function (Post $post) {
                    return $post->category->name;
                })
                ->filter(TD::FILTER_TEXT),
        ];
    }
}
