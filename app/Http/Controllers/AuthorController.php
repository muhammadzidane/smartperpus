<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('author.index', array('authors' => Author::get()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate_data = $request->validate(
            array(
                'name'      => array('required'),
                'price'     => array('required', 'integer'),
                'image'     => array('required', 'file', 'max:2000', 'mimes:jpg'),
                'author_id' => array('required'),
                'genre'     => array('required'),
            )
        );

        $validate_data['image'] = $validate_data['image']->getClientOriginalName();

        // Author::create(
        //     array(
        //         'name' => $validate_data['author_id']
        //     )
        // );

        // $author = Author::where('name', $validate_data['author_id'])->first();

        // $author->books->create(
        //     array(
        //         'name'      => $validate_data['name'],
        //         'price'     => $validate_data['price'],
        //         'image'     => $validate_data['image'],
        //         'author_id' => $author->id,
        //     )
        // );

        // return 'berhasil menambah buku ';
        dump($validate_data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        return view('author.show', compact('author'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        return view('author.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {


        $validate_data = $request->validate(
            array(
                'name' => array('required', 'unique:authors,name,' . $author->id),
            )
        );

        $author->update(
            array(
                'name' => $validate_data['name'],
            )
        );

        $pesan = 'Berhasil edit author ' . $author->name;
        return redirect()->route('authors.index')->with('pesan', $pesan);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        //
    }
}
