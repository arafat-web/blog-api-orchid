<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class PostEditScreen extends Screen
{
    public $post;

    public function query(Post $post): iterable
    {
        return [
            'post' => $post
        ];
    }

    public function name(): ?string
    {
        return $this->post->exists ? 'Edit Post' : 'Create Post';
    }

    public function commandBar(): array
    {
        return [
            Button::make('Save Post')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->type(Color::PRIMARY())
                ->canSee(!$this->post->exists),

            Button::make('Update Post')
                ->icon('note')
                ->method('createOrUpdate')
                ->type(Color::SUCCESS())
                ->canSee($this->post->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->type(Color::DANGER())
                ->canSee($this->post->exists),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('post.title')
                    ->title('Title')
                    ->placeholder('Enter title here'),
                Input::make('post.slug')
                    ->title('Slug')
                    ->placeholder('Enter slug here'),
                TextArea::make('post.description')
                    ->title('Description')
                    ->rows(3)
                    ->maxlength(200)
                    ->placeholder('Enter description here'),
                Relation::make('post.category_id')
                    ->title('Category')
                    ->fromModel(Category::class, 'name')
                    ->display('title'),
                Relation::make('post.user_id')
                    ->title('User')
                    ->fromModel(User::class, 'name')
                    ->display('name'),
                Quill::make('post.body')
                    ->title('Body')
                    ->placeholder('Enter body here'),
                Upload::make('post.attachment')
                    ->title('Upload Image')
                    ->acceptedFiles('image/*')
            ])
        ];
    }

    public function createOrUpdate(Request $request)
    {
        $this->post->fill($request->get('post'))->save();
        $this->post->attachment()->syncWithoutDetaching(
            $request->input('post.attachment', [])
        );
        Alert::info('You have successfully created a post.');

        return redirect()->route('platform.post.list');
    }

    public function remove()
    {
        $this->post->delete();
        $this->post->attachment()->delete();
        Alert::info('You have successfully deleted the post.');
        return redirect()->route('platform.post.list');
    }
}
