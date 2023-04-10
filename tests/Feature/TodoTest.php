<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Todo;

class TodoTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_update_one_todo_by_id()
    {   
        // given a todo exists
        $temp_todo = new Todo;
        $temp_todo->title = 'testeroni pizza';
        $temp_todo->description = 'an old description for an old todo';
        $temp_todo->done = false;
        $temp_todo->created_at = date("Y-m-d", strtotime("-1 day"));
        $temp_todo->save();

        // when calling /todos/{id} with updated values
        $response = $this->putJson("/todos/".$temp_todo->id, [
            'title' => 'new pizza',
            'description' => 'a new description that has nothing to do with the old one at all',
            'done' => true,
        ]);

        // then expect updated todo and 200 status
        $response->assertStatus(200);
        
        $updated_todo = Todo::find($temp_todo->id);

        $this->assertEquals('new pizza', $updated_todo->title, 'the todo\'s title did not match the updated title');

    }

    public function test_update_todo_with_invalid_data()
    {   
        // given a todo exists
        $temp_todo = new Todo;
        $temp_todo->title = 'testeroni pizza';
        $temp_todo->description = 'an old description for an old todo';
        $temp_todo->done = false;
        $temp_todo->created_at = date("Y-m-d", strtotime("-1 day"));
        $temp_todo->save();

        // when calling /todos/{id} with missing title values
        $response = $this->putJson("/todos/".$temp_todo->id, [
            'description' => 'a new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all',
            'done' => 100,
        ]);

        // then expect original todo and 422 status
        $response->assertStatus(422);
        
        $updated_todo = Todo::find($temp_todo->id);

        $this->assertEquals('testeroni pizza', $updated_todo->title, 'the todo\'s title has changed');
        
        $this->assertEquals(count($response['errors']), 3);

    }

    public function test_creating_one_todo()
    {
        // given a todo does not exist 

        // when a request is made with valid todo data  
        $response = $this->postJson("/todos", [
            'title' => 'new pizza',
            'description' => 'a new description that has nothing to do with the old one at all',
            'done' => true,
        ]);

        // then a 201 status is returned and the new todo exists
        $response->assertStatus(201);

        $this->assertIsString($response->content(), 'a JSON string was not returned');

        $decoded_todo = json_decode($response->content(), true)['todo'];

        $database_todo = Todo::find($decoded_todo['id']);

        $this->assertDatabaseHas('todos', [
            'id' => $decoded_todo['id'],
        ]);
    }
    
    public function test_deleting_one_todo()
    {
        // given a todo exists
        $temp_todo = new Todo;
        $temp_todo->title = 'testeroni pizza';
        $temp_todo->description = 'an old description for an old todo';
        $temp_todo->done = false;
        $temp_todo->created_at = date("Y-m-d", strtotime("-1 day"));
        $temp_todo->save();

        // when the delete /todos/{id} is called 
        $response = $this->deleteJson("/todos/".$temp_todo->id);

        // a 204 message is returned and the todo is not found
        $response->assertStatus(204);

        $this->assertSoftDeleted($temp_todo);
    }

    public function test_the_return_of_all_todos()
    {
        // given there are todos in the database

        // when the todos index route is called 

        $response = $this->get('/todos');
        
        $response->assertStatus(200);

        $todos = json_decode($response->content(), true);
        
        // then the todos object and its elements are formatted properly
        $this->assertArrayHasKey('todos', $decoded_todos, 'there is no todos key');
        
        $todos_array = $decoded_todos['todos'];

        $correctly_formatted_todos_array = array_filter($todos_array, function($todo){
            $keys = array_keys($todo);
            return in_array('title', $keys) &&
                in_array('description', $keys) &&
                in_array('done', $keys) &&
                in_array('creation_date', $keys) &&
                in_array('id', $keys) &&
                count($keys) == 5;
        });

        $this->assertEquals(count($todos_array), count($correctly_formatted_todos_array), 'a number of todos have incorrect keys');

        $this->assertEquals(count(Todo::all()), count($correctly_formatted_todos_array), 'the number of todos returned does not match the ammount in the DB');

    }
}
 