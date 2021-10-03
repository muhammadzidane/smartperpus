<?php

namespace App\Http\Controllers;

use App\Models\{BookImage, User, Book};
use Illuminate\Support\Facades\{Auth, File, Validator, Storage};
use Illuminate\Http\Request;

class BookImageController extends Controller
{
    public function update(Request $request, BookImage $bookImage)
    {
        $rules = array(
            'image' => 'required|mimes:jpg,jpeg,png|max:2000',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        } else {
            $time     = time();
            $image    = $time . '.' . $request->image->getClientOriginalExtension();
            $filename = 'storage/books/book_images/' . $bookImage->image;
            $data     = array('image' => $image);

            File::delete($filename);
            $request->image->storeAs('public/books/book_images', $image);
            $bookImage->update($data);

            $message = 'Berhasil mengupdate foto buku';

            return redirect()->back()->with('message', $message);
        }
    }

    public function destroy(BookImage $bookImage)
    {
        $delete = $bookImage->delete();

        $filename = 'storage/books/book_images/' . $bookImage->image;

        File::delete($filename);

        return response()->json(compact('delete'));
    }

    public function store(Request $request, Book $book)
    {
        $rules = array(
            'image' => 'required|mimes:png,jpg,jpeg|max:2000',
        );

        $validator = Validator::make($request->all(), $rules);

        $request->validate($rules);

        if (!$validator->fails()) {
            $time       = time();
            $image      = $time . '.' . $request->image->getClientOriginalExtension();
            $create     = array('image' => $image);
            $message    = 'Berhasil menambah foto buku';

            $book->book_images()->create($create);
            $request->image->storeAs('public/books/book_images', $image);

            return redirect()->back()->with('message', $message);
        } else {
            return redirect()->back()->withErrors($validator);
        }
    }

    public function updateMainImage(Request $request, Book $book) {
        $time          = time();
        $image         = $time . '.' . $request->image->getClientOriginalExtension();
        $deleted_image = 'storage/books/' . $book->image;
        $data          = array('image' => $image);

        File::delete($deleted_image);
        $request->image->storeAs('public/books', $image);
        $book->update($data);

        $message = 'Berhasil mengupdate gambar utama';

        return redirect()->back()->with('message', $message);
    }
}
