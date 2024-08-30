<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        return Blog::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'author' => 'required|string',
            'tags' => 'nullable|string',
            'date' => 'nullable|date'
        ]);

        $blog = Blog::create($request->all());
        return response()->json($blog, 201);
    }

    public function show($id)
    {
        return Blog::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'author' => 'required|string',
            'tags' => 'nullable|string',
            'date' => 'nullable|date'
        ]);

        $blog = Blog::findOrFail($id);
        $blog->update($request->all());
        return response()->json($blog);
    }

    public function destroy($id)
    {
        Blog::destroy($id);
        return response()->json(['message' => 'Blog deleted successfully']);
    }
}