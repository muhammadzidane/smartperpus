<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator, Storage};

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        return view('user.index',
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
        $this->authorize('viewAny', User::class);

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

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('viewAny', User::class);

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
        $user = User::find($id);

        $first_name = $user->first_name;
        $last_name  = $user->last_name;
        $pesan      = 'Berhasil men-update ' . $first_name . ' ' . $last_name;

        $update = array(
            'first_name' => $request->first_name ?? $request->nama_awal,
            'last_name' => $request->last_name ?? $request->nama_akhir,
            'email' => $request->email,
            'role' => $request->role,
        );

        if ($request->ajax()) { // AJAX
            $validator = Validator::make($request->all(),
                array(
                    'first_name' => array('required'),
                    'last_name'  => array('required'),
                    'email'      => array('email:rfc,dns'),
                )
            );

            if ($validator->fails()) {
                return response()->json(array('pesan' => $validator->getMessageBag()));
            }
            else {
                $user->update($update);

                return response()->json(array('pesan' => $pesan, 'success' => true));
            }

        }
        else { // non AJAX
            $validate_data = $request->validate(
                array(
                    'nama_awal'  => array('required'),
                    'nama_akhir' => array('required'),
                    'email'      => array('required', 'email:rfc,dns')
                )
            );

            $user->update($update);
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

    public function photoUpdateOrInsert(Request $request) {
        $request->validate(
            array(
                'foto_profile' => array('nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2000'),
            )
        );

        $photo_profile = $request->foto_profile->getClientOriginalName();

        $user = Auth::user();

        if ($user->profile_image !== null) {
            Storage::delete($user->profile_image);
            unlink(storage_path('app\public\user\profile\\' . $user->profile_image));

            $pesan = 'Berhasil men-edit foto profil ' . $user->first_name . ' ' . $user->last_name;
        }
        else {
            $pesan = 'Berhasil menambah foto profil ' . $user->first_name . ' ' . $user->last_name;
        }

        User::find(Auth::id())->update(array('profile_image' => $photo_profile));

        if (!Storage::exists('public/users/profile/' . $photo_profile)) {
            $request->foto_profile->storeAs('public/user/profile',
              str_replace(' ', '_', strtolower($photo_profile)));

        }

        return redirect()->back()->with('pesan', $pesan);
    }

    public function destroyPhotoProfile(Request $request, User $user) {
        $update = array(
            'profile_image' => null
        );

        $pesan = 'Berhasil menghapus foto profil ' . $user->first_name . ' ' . $user->last_name;

        Storage::delete($user->profile_image);
        unlink(storage_path('app\public\user\profile\\' . $user->profile_image));

        $user->update($update);

        if ($request->ajax()) {
            return response()->json(array('pesan' => $pesan));
        }
        else {
            return redirect()->back()->with('pesan', $pesan);
        }
    }
}
