<?php

namespace App\Http\Controllers;

use App\Models\Todo;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    public function todos()
    {
        $todos = Todo::all();

        if ($todos->count() > 0) {
            return response()->json([
                'status' => 200,
                'todos' => $todos
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'todos' => 'No Records Found!!'
            ], 404);
        }

    }

    public function getATodo($id)
    {
        try {
            $todo = Todo::findOrFail($id);
            return response()->json($todo, 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 404,
                'message' => 'Todo not found!!',
            ], 404);
        }
    }

    public function addTodo(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $todo = Todo::create([
                "title" => $req->title,
                "description" => $req->description,
              
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Todo created successfully',
                'todo' => $todo
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500, 
                'error' => 'Failed to insert todo, Please try again!!.'
            ], 500);
        }
    }

    public function updateTodo(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }
        try{
            $todo = Todo::findOrFail($id);
            $todo->title = $req->title;
            $todo->description = $req->description;

            if ($todo->save()) {
                return response()->json([
                    'status' => 202,
                    'message' => 'Todo updated successfully'
                ], 200);      
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Something went wrong, update failed!!'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'error' => 'Update failed ' . $e->getMessage(),
            ], 500);
        }
    }

    public function deleteTodo($id)
    {
        try {
            $todo = Todo::findOrFail($id);
            $todo->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Todo deleted successful',
            ], 404);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 404,
                'message' => 'Todo not found!!',
            ], 404);
        }
    }
}
