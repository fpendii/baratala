<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function storeAjax(Request $request) {
    $tag = Tag::create(['name' => $request->name]);
    return response()->json($tag);
}
}
