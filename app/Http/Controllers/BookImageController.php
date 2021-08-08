<?php

namespace App\Http\Controllers;

use App\Models\{BookImage, User, Book};
use Illuminate\Support\Facades\{Auth, File};
use Illuminate\Http\Request;

class BookImageController extends Controller
{
    public function edit(Request $request, BookImage $bookImage)
    {
        $user     = Auth::user();
        $time     = time();
        $image    = $user->first_name . '-' . $user->last_name . '-' . $user->email . '-' . $time;
        $image   .= '.' . $request->image->getClientOriginalExtension();
        $filename = 'storage/books/book_images/' . $bookImage->image;
        $data     = array('image' => $image);

        File::delete($filename);
        $request->image->storeAs('public/books/book_images', $image);

        $update = $bookImage->update($data);
        $src    = $image;

        return response()->json(compact('update', 'src'));
    }
}
