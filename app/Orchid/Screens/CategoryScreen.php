<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class CategoryScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'categories' => Category::query()->paginate(10),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Manage Categories';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            ModalToggle::make('Add category')
            ->modal('categoryModal')
            ->method('create')
            ->type(Color::DARK)
            ->icon('plus'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::table('categories', [
                TD::make('name')->sort(),
                TD::make('slug'),
                TD::make('Action')
                    ->alignRight()
                    ->render(function (Category $category) {
                        return Button::make('Delete')
                            ->icon('trash')
                            ->type(Color::DANGER())
                            ->confirm('Are you sure?')
                            ->method('delete', ['category' => $category->id]);
                    }),
                
            ]),
            Layout::modal('categoryModal', Layout::rows([
                Input::make('category.name')->title('Name')->placeholder('Category name'),
                Input::make('category.slug')->title('Slug')->placeholder('Category slug'),
            ]))
                ->title('Add new category')
                ->applyButton('Create'),
        ];
    }

    public function create(Request $request)
    {
        $request->validate([
            'category.name' => 'required',
            'category.slug' => 'required',
        ]);

        $category = new Category();
        $category->name = $request->input('category.name');
        $category->slug = $request->input('category.slug');
        $category->save();
    }

    
}
