<?php

namespace App\Orchid\Screens;

use App\Orchid\Layouts\PostListLayout;
use App\Models\Post;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Color;

class PostListScreen extends Screen
{

    public function query(): array
    {
        return [
            'posts' => Post::paginate()
        ];
    }

    /**
     * The name is displayed on the user's screen and in the headers
     */
    public function name(): ?string
    {
        return 'Blog post';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "All blog posts";
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Add New Post')
                ->type(Color::PRIMARY)
                ->icon('plus')
                ->route('platform.post.edit')
        ];
    }


    public function layout(): array
    {
        return [
            PostListLayout::class
        ];
    }
}
