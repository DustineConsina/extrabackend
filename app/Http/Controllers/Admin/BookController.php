<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index() 
    {
        $user_id = auth()->user()->id;

        $books = Book::where('user_id', $user_id)->get();

        return response()->json([
            "status" => true,
            "message" => "Books retrieved successfully",
            "data" => $books,
        ]);
    }

    public function store(Request $request) 
    {
        $data = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'category' => 'required|string',
            'status' => 'required|string',
            'image' => 'nullable|image|max:2048', // Optional image upload
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('books', 'public');
        }

        $data['user_id'] = auth()->user()->id;

        $book = Book::create($data);

        return response()->json([
            "status" => true,
            "message" => "Book created successfully",
            "data" => $book,
        ]);
    }

    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                "status" => false,
                "message" => "Book not found",
            ], 404);
        }

        return response()->json([
            "status" => true,
            "message" => "Book retrieved successfully",
            "data" => $book,
        ]);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                "status" => false,
                "message" => "Book not found",
            ], 404);
        }

        $data = $request->validate([
            'title' => 'sometimes|required|string',
            'author' => 'sometimes|required|string',
            'category' => 'sometimes|required|string',
            'status' => 'sometimes|required|string',
            'image' => 'nullable|image|max:2048', // Optional image upload
        ]);

        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($book->image) {
                Storage::disk('public')->delete($book->image);
            }

            $data['image'] = $request->file('image')->store('books', 'public');
        }

        $book->update($data);

        return response()->json([
            "status" => true,
            "message" => "Book updated successfully",
            "data" => $book,
        ]);
    }

    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                "status" => false,
                "message" => "Book not found",
            ], 404);
        }

        // Delete the image if it exists
        if ($book->image) {
            Storage::disk('public')->delete($book->image);
        }

        $book->delete();

        return response()->json([
            "status" => true,
            "message" => "Book deleted successfully",
        ]);
    }

    public function userBooks()
    {
        $books = Book::all(); // Fetch all books (or apply filters if needed)

        return response()->json([
            "status" => true,
            "message" => "Books retrieved successfully",
            "data" => $books,
        ]);
    }
}