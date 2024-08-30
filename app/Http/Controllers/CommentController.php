<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::all();
        return response()->json($comments);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'subject' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'comment' => 'required|string',
            'captcha' => 'required|string',
        ]);

        $comment = Comment::create($validatedData);

        return response()->json($comment, 201);
    }

    public function show($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Komentar tidak ditemukan'], 404);
        }

        return response()->json($comment);
    }


    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Komentar tidak ditemukan'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255',
            'subject' => 'sometimes|required|string|max:255',
            'website' => 'sometimes|required|string|max:255',
            'comment' => 'sometimes|required|string',
            'captcha' => 'sometimes|required|string',
        ]);

        Log::info('Data yang diterima untuk pembaruan:', $validatedData);

        $comment->update($validatedData);

        Log::info('Komentar setelah diperbarui:', $comment->toArray());

        return response()->json($comment);
    }


    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Komentar tidak ditemukan'], 404);
        }

        $comment->delete();

        return response()->json(['message' => 'Komentar berhasil dihapus']);
    }
}