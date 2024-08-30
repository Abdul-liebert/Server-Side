<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends Controller
{
    public function index()
    {
        return Banner::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'date' => 'nullable|date'
        ]);

        $banner = Banner::create($request->all());
        return response()->json($banner, 201);
    }

    public function show($id)
    {
        return Banner::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'date' => 'nullable|date'
        ]);

        $banner = Banner::findOrFail($id);
        $banner->update($request->all());
        return response()->json($banner);
    }

    public function destroy($id)
    {
        Banner::destroy($id);
        return response()->json(['message' => 'Banner deleted successfully']);
    }
}