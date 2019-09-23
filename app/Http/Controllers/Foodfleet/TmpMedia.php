<?php

namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TmpMedia extends Controller
{
    public function index()
    {
        return response()->json(null, 501);
    }

    public function show(Request $request, $id)
    {
    }

    public function store(Request $request)
    {
        $file = $request->file('file');
        $path = $file->storeAs(sha1($request->user()->id . microtime()), $file->getClientOriginalName(), 'tmp');
        return response()->json($path);
    }

    public function update(Request $request)
    {
    }

    public function destroy(Request $request)
    {
    }
}
