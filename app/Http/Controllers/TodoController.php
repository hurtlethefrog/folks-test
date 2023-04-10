<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Requests\TodoRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        // won't retrieve soft deleted todos
        return response()->json([
            'todos' => Todo::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") AS creation_date, id, title, description, done'))->get()
        ], 200);
    }

    /**
     * Update a todo
     * @param the update request $request
     * @param int $id
     * @return a custom json resource
     */
    public function update(TodoRequest  $request, int $id) {
        $updated_todo = Todo::find($id);
        $updated_todo->update($request->validated());
        $updated_todo->save();

        return response()->json(['todo' => $updated_todo], 200);
    }

    /**
     * @param the store request $request
     * @return a custom json resource
     */
    public function store(TodoRequest $request) {
        $new_todo = new Todo($request->validated());
        $new_todo->save();

        return response()->json(['todo' => $new_todo], 201);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return a generic response
     */
    public function destroy(Request $request, int $id) {
        $todo = Todo::find($id)->delete();

        return response('', 204);
    }
}
