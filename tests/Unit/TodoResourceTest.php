<?php

namespace Tests\Feature;

use App\Filament\Resources\TodoResource;
use App\Filament\Resources\TodoResource\Pages\EditTodo;
use App\Models\Todo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TodoResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * For Authentication
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    /**
     * @test
     */
    public function it_can_render_todo_index_page()
    {
        $this->get(TodoResource::getUrl('index'))
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function it_can_render_todo_create_page()
    {
        $this->get(TodoResource::getUrl('create'))
            ->assertSuccessful();
    }


    /**
     * @test
     */
    public function it_can_create_todo()
    {

        $date_time = Carbon::now()->format('Y-m-d\TH:i:s');

        $t = new Todo();
        $t->title = 'My Todo Title 1';
        $t->due_date = $date_time;
        $t->user_id = 1;
        $t->save();

        $this->assertDatabaseCount('todos', 1);
        $this->assertDatabaseHas('todos', [
            'title' => 'My Todo Title 1',
            'user_id' => 1,
            'due_date' => $date_time,
        ]);

        $date_time = Carbon::now()->format('Y-m-d\TH:i:s');

        $t = new Todo();
        $t->title = 'My Todo Title 1';
        $t->due_date = $date_time;
        $t->user_id = 1;
        $t->save();

        $this->assertDatabaseCount('todos', 2);
    }

    /**
     * @test
     */
    public function it_can_render_todo_edit_page()
    {
        $date_time = Carbon::now()->format('Y-m-d\TH:i:s');

        $t = new Todo();
        $t->title = 'My Todo Title 2 | Update';
        $t->due_date = $date_time;
        $t->user_id = 1;
        $t->save();

        $this->get(TodoResource::getUrl('edit', ['record' => $t->id]))
            ->assertSuccessful();
    }

    /**
     * @test
     */
    public function it_can_edit_todo()
    {
        $date_time = '2024-03-08 22:37:13';

        $todo = new Todo();
        $todo->title = 'To be updated';
        $todo->priority = 'normal';
        $todo->due_date = $date_time;
        $todo->user_id = 1;
        $todo->save();

        // Act
        Livewire::test(EditTodo::class, [
            'record' => $todo->id,
        ])
            ->set('data.title','Task updated!!')
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('todos', [
            'title' => 'Task updated!!',
            'id' => $todo->id,
        ]);
    }
}
