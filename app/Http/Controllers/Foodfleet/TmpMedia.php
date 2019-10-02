<?php

namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TmpMedia extends Controller
{
    public function store(Request $request)
    {
        $file = $request->file('file');
        $path = $file->storeAs(sha1($request->user()->id . microtime()), $file->getClientOriginalName(), 'tmp');
        return response()->json($path);
    }
}
