<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $this->authorize('viewAny', User::class);

        return view('user.list-of-employees',
            array(
                'me'    => Auth::user(),
                'users' => User::withTrashed()->where('id', '!=', Auth::id())->get(),
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $this->authorize('viewAny', User::class);

        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $this->authorize('viewAny', User::class);

        return view('user.edit', array('user' => User::find($id)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $this->authorize('update', User::class);

        $user = User::find($id);

        $first_name = $user->first_name;
        $last_name  = $user->last_name;


        $user->update(
            array(
                'first_name' => $request->first_name ?? $request->nama_awal,
                'last_name'  => $request->last_name ?? $request->nama_akhir,
                'email'      => $request->email,
                'role'       => $request->role,
            )
        );

        $pesan = 'Berhasil men-update ' . $first_name . ' ' . $last_name;

        if ($request->userUpdate !== null) { // AJAX
            return response()->json(array('pesan' => $pesan));
        }
        else { // non AJAX
            return redirect()->back()->with('pesan', $pesan);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $user  = User::find($id);
        $pesan = 'Berhasil menghapus user ' . $user->first_name . ' ' . $user->last_name;

        $user->forceDelete();

        if ($request->userDelete !== null) {
            return response()->json(array('pesan' => $pesan)); // AJAX
        }
        else {
            return redirect()->back()->with('pesan', $pesan); // non AJAX
        }
    }

    public function softDelete($id, Request $request)
    {
        $user  = User::find($id);
        $pesan = 'Berhasil menblokir user ' . $user->first_name . ' ' . $user->last_name;

        $user->delete();

        if ($request->userBlock !== null) {
            return response()->json(array('pesan' => $pesan)); // AJAX
        }
        else {
            return redirect()->back()->with('pesan', $pesan); // non AJAX
        }
    }

    public function restore($id, Request $request) {
        $user = User::withTrashed()->find($id);

        $pesan = 'Berhasil melepas blokir user ' . $user->first_name . ' ' . $user->last_name;

        $user->restore();

        if ($request->userRestoreBlock !== null) {
            return response()->json(array('pesan' => $pesan)); // AJAX
        }
        else {
            return redirect()->back()->with('pesan', $pesan); // non AJAX
        }
    }
}
