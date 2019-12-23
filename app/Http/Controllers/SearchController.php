<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required',
        ]);

        $search = $request->query('search');

        $tags = auth()->user()->tags()
            ->search($search)
            ->withCount('tasks')
            ->orderBy('tasks_count', 'DESC')
            ->get();

        $tasks = auth()->user()->tasks()
            ->search($search)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return compact('tags', 'tasks');
    }
}
