<?php

namespace App\Orchid\Screens;

use App\Models\Task;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class TaskScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'tasks' => Task::latest()->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Simple Todo List';
    }

    public function description(): string|null
    {
        return 'Simple Todo List';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            ModalToggle::make('Create Task')
                ->modal('taskModal')
                ->method('create')
                ->icon('plus')
                ->type(Color::PRIMARY()),
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
            Layout::table('tasks', [
                TD::make('name'),

                TD::make('Actions')
                    ->alignRight()
                    ->render(function (Task $task) {
                        return Button::make('Delete Task')
                            ->icon('trash')
                            ->type(Color::DANGER())
                            ->confirm('Are you sure?')
                            ->method('delete', ['task' => $task->id]);
                    }),
            ]),
            Layout::modal('taskModal', Layout::rows([
                Input::make('task.name')
                    ->title('Name')
                    ->placeholder('Enter task name')
            ]))
                ->title('Create new task')
                ->applyButton('Create'),
        ];
    }

    public function create(Request $request)
    {
        $request->validate([
            'task.name' => 'required',
        ]);

        $task = new Task;
        $task->name = $request->input('task.name');
        $task->save();
    }

    public function delete(Task $task)
    {
        $task->delete();
    }

}
