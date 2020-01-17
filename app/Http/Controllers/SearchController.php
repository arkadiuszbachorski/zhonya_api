<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required',
        ]);

        $search = $request->query('search');

        $tags = auth()->user()->tags();
        $tasks = auth()->user()->tasks();

        if (Str::startsWith($search, '#')) {
            $search = substr($search, 1);
            $tags->search($search);
            $tasks->whereHas('tags', function ($query) use ($search) {
                $query->where('name', 'LIKE',"%$search%");
            });
        } else {
            $tags->search($search);
            $tasks->search($search);
        }

        $tags = $tags->withCount('tasks')
            ->orderBy('tasks_count', 'DESC')
            ->limit(15)
            ->get();

        $tasks = $tasks->orderBy('updated_at', 'DESC')
            ->limit(15)
            ->get();

        return compact('tags', 'tasks');
    }
}
