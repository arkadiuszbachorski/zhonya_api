<?php

namespace App\Http\Controllers;

use App\Services\Search;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Search $search, Request $request)
    {
        $request->validate([
            'search' => 'required',
        ]);

        return $search->search($request->input('search'), Auth::user());
    }
}
