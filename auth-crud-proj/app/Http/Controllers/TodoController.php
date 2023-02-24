<?php

namespace App\Http\Controllers;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::user()->id;
    $todos = Todo::where(['user_id' => $userId])->get();
    return view('todo.list', ['todos' => $todos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('todo.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = Auth::user()->id;
        $input = $request->input();
        $input['user_id'] = $userId;
        $todoStatus = Todo::create($input);
        if ($todoStatus) {
            return back()->with('success', 'Todo successfully added');
        } else {
            return back()->with('error', 'Oops something went wrong, Todo not saved');
        }
        return redirect('todo');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userId = Auth::user()->id;
    $todo = Todo::where(['user_id' => $userId, 'id' => $id])->first();
    if (!$todo) {
        return redirect('todo')->with('error', 'Todo not found');
    }
    return view('todo.view', ['todo' => $todo]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $userId = Auth::user()->id;
    $todo = Todo::where(['user_id' => $userId, 'id' => $id])->first();
    if ($todo) {
        return view('todo.edit', [ 'todo' => $todo ]);
    } else {
        return redirect('todo')->with('error', 'Todo not found');
    }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $userId = Auth::user()->id;
    $todo = Todo::find($id);
    if (!$todo) {
        return redirect('todo')->with('error', 'Todo not found.');
    }
    $input = $request->input();
    $input['user_id'] = $userId;
    $todoStatus = $todo->update($input);
    if ($todoStatus) {
        return redirect('todo')->with('success', 'Todo successfully updated.');
    } else {
        return redirect('todo')->with('error', 'Oops something went wrong. Todo not updated');
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userId = Auth::user()->id;
    $todo = Todo::where(['user_id' => $userId, 'id' => $id])->first();
    $respStatus = $respMsg = '';
    if (!$todo) {
        $respStatus = 'error';
        $respMsg = 'Todo not found';
    }
    $todoDelStatus = $todo->delete();
    if ($todoDelStatus) {
        $respStatus = 'success';
        $respMsg = 'Todo deleted successfully';
    } else {
        $respStatus = 'error';
        $respMsg = 'Oops something went wrong. Todo not deleted successfully';
    }
    return redirect('todo')->with($respStatus, $respMsg);
    }
}
