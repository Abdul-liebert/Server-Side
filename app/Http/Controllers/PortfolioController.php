<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Portfolio;

class PortfolioController extends Controller
{
    public function index()
    {
        return Portfolio::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'author' => 'required|string'
        ]);

        $portfolio = Portfolio::create($request->all());
        return response()->json($portfolio, 201);
    }

    public function show($id)
    {
        return Portfolio::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'author' => 'required|string'
        ]);

        $portfolio = Portfolio::findOrFail($id);
        $portfolio->update($request->all());
        return response()->json($portfolio);
    }

    public function destroy($id)
    {
        Portfolio::destroy($id);
        return response()->json(['message' => 'Portfolio deleted successfully']);
    }
}