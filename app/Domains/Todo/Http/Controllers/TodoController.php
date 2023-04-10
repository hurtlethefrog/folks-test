<?php

namespace App\Domains\Todo\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Returns all the todos
     * @param $request
     * @return a custom json resource
     */
    public function index(Request $request) {

    }

    /**
     * Update a todo
     * @param the update request $request
     * @param int $id
     * @return a custom json resource
     */
    public function update(/* the update request */ $request, int $id) {

    }

    /**
     * @param the store request $request
     * @return a custom json resource
     */
    public function store(/* the store request */ $request) {

    }

    /**
     * @param Request $request
     * @param int $id
     * @return a generic response
     */
    public function destroy(Request $request, int $id) {

    }
}
