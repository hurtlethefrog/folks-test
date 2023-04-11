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
    // tests for index
    public function test_the_return_of_all_todos()
    {
        // given there are todos in the database

        // when the todos index route is called 

        $response = $this->get('/todos');
        
        $response->assertStatus(200);

        $todos = json_decode($response->content(), true);
        
        // then the todos object and its elements are formatted properly
        $this->assertArrayHasKey('todos', $todos, 'there is no todos key');
        
        $todos_array = $todos['todos'];

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

    // update tests
    public function test_update_one_todo_by_id()
    {   
        // given a todo exists
        $temp_todo = new Todo;
        $temp_todo->title = 'testeroni pizza 1';
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
    public function test_update_todo_with_no_title()
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
            'description' => 'an old description for an old todo',
            'done' => 0,
        ]);

        // then expect original todo and 422 status and an error for the title
        $response->assertStatus(422);
        
        $updated_todo = Todo::find($temp_todo->id);

        $this->assertDatabaseHas('todos', [
            'id' => $temp_todo->id,
            'title' => 'testeroni pizza',
            'description' => 'an old description for an old todo',
            'done' => false,
        ]);

        $this->assertEquals($response['errors']['title'][0], 'The title field is required.');

    }
    public function test_update_todo_with_long_title()
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
            'title' => 'a new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all new description that has nothing to do with the old one at all',
            'done' => 0,
        ]);

        // then expect original todo and 422 status
        $response->assertStatus(422);
        
        $this->assertDatabaseHas('todos', [
            'id' => $temp_todo->id,
            'title' => 'testeroni pizza',
            'description' => 'an old description for an old todo',
            'done' => false,
        ]);

        $this->assertEquals($response['errors']['title'][0], 'The title must not be greater than 100 characters.');

    }
    public function test_update_todo_with_non_unique_title()
    {   
        // given two todos exist
        $temp_todo = new Todo;
        $temp_todo->title = 'testeroni pizza';
        $temp_todo->description = 'an old description for an old todo';
        $temp_todo->done = false;
        $temp_todo->created_at = date("Y-m-d", strtotime("-1 day"));
        $temp_todo->save();
    
        $temp_todo_2 = new Todo;
        $temp_todo_2->title = 'testeroni pizza with pineapple';
        $temp_todo_2->description = 'an old description for an old todo';
        $temp_todo_2->done = true;
        $temp_todo_2->created_at = date("Y-m-d", strtotime("-1 day"));
        $temp_todo_2->save();

        // when calling /todos/{id} with missing title values
        $response = $this->putJson("/todos/".$temp_todo->id, [
            'title' => 'testeroni pizza with pineapple',
            'done' => 0,
        ]);

        // then expect original todos and 422 status
        $response->assertStatus(422);
        
        $this->assertDatabaseHas('todos', [
            'id' => $temp_todo->id,
            'title' => 'testeroni pizza',
            'description' => 'an old description for an old todo',
            'done' => false,
        ]);
        
        $this->assertDatabaseHas('todos', [
            'id' => $temp_todo_2->id,
            'title' => 'testeroni pizza with pineapple',
            'description' => 'an old description for an old todo',
            'done' => true,
        ]);

        $this->assertEquals($response['errors']['title'][0], 'The title has already been taken.');


    }
    public function test_update_todo_with_non_castable_boolean()
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
            'title' => 'testeroni pizza',
            'description' => 'a new description that has nothing to do with the old one at all new description',
            'done' => 'done',
        ]);

        // then expect original todo and 422 status
        $response->assertStatus(422);

        $this->assertDatabaseHas('todos', [
            'id' => $temp_todo->id,
            'title' => 'testeroni pizza',
            'description' => 'an old description for an old todo',
            'done' => false,
        ]);

        $this->assertEquals($response['errors']['done'][0], 'The done field must be true or false.');

    }
    public function test_update_todo_without_id()
    {
        // given a todo exists
        $temp_todo = new Todo;
        $temp_todo->title = 'testeroni pizza';
        $temp_todo->description = 'an old description for an old todo';
        $temp_todo->done = false;
        $temp_todo->created_at = date("Y-m-d", strtotime("-1 day"));
        $temp_todo->save();

        // when update /todos/ is called 
        $response = $this->putJson("/todos/", [
            'title' => 'testeroni pizza',
            'description' => 'a new description that has nothing to do with the old one at all new description',
            'done' => true,
        ]);

        // a 204 message is returned and the todo is not found
        $response->assertStatus(405);

        $this->assertDatabaseHas('todos', [
            'id' => $temp_todo['id'],
            'title' => 'testeroni pizza',
            'description' => 'an old description for an old todo',
            'done' => false,
        ]);

    }

    public function test_creating_one_todo()
    {
        // given a todo does not exist 

        // when a request is made with valid todo data  
        $response = $this->postJson("/todos", [
            'title' => 'testeroni pizza',
            'description' => 'a new description that has nothing to do with the old one at all',
            'done' => true,
        ]);

        // then a 201 status is returned and the new todo exists
        $response->assertStatus(201);

        $todo = json_decode($response->content(), true);
        
        // then the todos object and its elements are formatted properly
        $this->assertArrayHasKey('todo', $todo, 'there is no todos key');

        $this->assertDatabaseHas('todos', [
            'id' => $todo['todo']['id'],
            'title' => 'testeroni pizza',
            'description' => 'a new description that has nothing to do with the old one at all',
            'done' => true,
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
    public function test_deleting_one_todo_without_id()
    {
        // given a todo exists
        $temp_todo = new Todo;
        $temp_todo->title = 'testeroni pizza';
        $temp_todo->description = 'an old description for an old todo';
        $temp_todo->done = false;
        $temp_todo->created_at = date("Y-m-d", strtotime("-1 day"));
        $temp_todo->save();

        // when the delete /todos/{id} is called 
        $response = $this->deleteJson("/todos/");

        // a 204 message is returned and the todo is not found
        $response->assertStatus(405);

        $this->assertDatabaseHas('todos', [
            'id' => $temp_todo['id'],
            'title' => 'testeroni pizza',
            'description' => 'an old description for an old todo',
            'done' => false,
        ]);

    }

}
 