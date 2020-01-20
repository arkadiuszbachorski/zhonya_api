<?php

namespace App\Http\Controllers;

use App\Helpers\SearchHelper;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required',
        ]);

        $search = new SearchHelper($request->input('search'), auth()->user());

        return $search->getData();
    }
}
