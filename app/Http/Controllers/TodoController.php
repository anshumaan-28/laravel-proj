<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255'
        ]);

        $todo = Todo::create([
            'title' => $validated['title'],
            'user_id' => auth()->id()
        ]);

        return response()->json($todo);
    }

    public function toggle(Todo $todo)
    {
        $todo->update(['completed' => !$todo->completed]);
        return response()->json(['completed' => $todo->completed]);
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return response()->json(['success' => true]);
    }
} 