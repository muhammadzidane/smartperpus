<?php

namespace App\Http\Controllers;

use App\Models\{BookImage, User, Book};
use Illuminate\Support\Facades\{Auth, File};
use Illuminate\Http\Request;

class BookImageController extends Controller
{
    public function edit(Request $request, BookImage $bookImage)
    {
        $time     = time();
        $image    = $time . '.' . $request->image->getClientOriginalExtension();
        $filename = 'storage/books/book_images/' . $bookImage->image;
        $data     = array('image' => $image);

        File::delete($filename);
        $request->image->storeAs('public/books/book_images', $image);

        $update = $bookImage->update($data);
        $src    = $image;

        return response()->json(compact('update', 'src'));
    }

    public function destroy(BookImage $bookImage)
    {
        $delete = $bookImage->delete();

        return response()->json(compact('delete'));
    }
}
