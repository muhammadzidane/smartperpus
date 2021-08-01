<?php

namespace App\Http\Controllers;

use App\Models\{User, BookUser};
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator, Storage, Auth, Hash, File};

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin.only')->only('create', 'index', 'edit', 'update', 'store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::withTrashed()->where('id', '!=', Auth::id())->where('role', '!=', 'guest')->get();
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('viewAny', User::class);

        return view('user.create');
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

        $book_user = BookUser::where('user_id', $user->id)
            ->where('payment_status', 'waiting_for_confirmation')
            ->get();

        return view('user.show', compact('user', 'book_user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validation = array(
            'nama_awal'       => array('required'),
            'nama_akhir'      => array('required'),
            'email'           => array('required', 'email:rfc,dns', 'unique:users,email,' . $user->id),
            'tanggal_lahir'   => array('nullable', 'date'),
            'jenis_kelamin'   => array('nullable', 'in:L,P'),
            'nomer_handphone' => array('nullable', 'min:9', 'max:15'),
        );

        $update = array(
            'first_name'    => $request->first_name ?? $request->nama_awal,
            'last_name'     => $request->last_name ?? $request->nama_akhir,
            'email'         => $request->email,
            'role'          => $request->role ?? $user->role,
            'date_of_birth' => $request->tanggal_lahir,
            'gender'        => $request->jenis_kelamin,
            'phone_number'  => $request->nomer_handphone,
        );

        $pesan      = 'Berhasil men-update ' . $user->first_name . ' ' . $user->last_name;

        if ($request->ajax()) { // AJAX
            $validator = Validator::make($request->all(), $validation);

            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->errors(), 'status' => 'fail'));
            } else {
                $user->update($update);

                return response()->json(array('pesan' => $pesan, 'success' => true));
            }
        } else { // non AJAX

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
        } else {
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
        } else {
            return redirect()->back()->with('pesan', $pesan); // non AJAX
        }
    }

    public function restore($id, Request $request)
    {
        $user = User::withTrashed()->find($id);

        $pesan = 'Berhasil melepas blokir user ' . $user->first_name . ' ' . $user->last_name;

        $user->restore();

        if ($request->userRestoreBlock !== null) {
            return response()->json(array('pesan' => $pesan)); // AJAX
        } else {
            return redirect()->back()->with('pesan', $pesan); // non AJAX
        }
    }

    public function photoUpdateOrInsert(Request $request, User $user)
    {
        $rules     = array(
            'image' => 'required|mimes:jpg,jpeg,png|max:2000',
        );
        $validator = Validator::make($request->all(), $rules);
        $errors    = $validator->errors();

        if ($validator->fails()) {
            return response()->json(compact('errors'));
        } else {
            $photo_profile = $request->image != null ? $request->image->getClientOriginalName() : null;
            $path_store    = "$user->first_name-$user->last_name-$user->email-";
            $path_store   .= time() . '.' . $request->image->getClientOriginalExtension();
            $user          = Auth::user();

            if ($user->profile_image) {
                $filename = 'storage/users/profiles/' . $user->profile_image;
                File::delete($filename);
            }

            $data = array('profile_image' => $path_store);

            $request->image->storeAs('public/users/profiles', $path_store);
            $user->update($data);

            return response()->json(compact('path_store'));
        }
    }

    public function destroyPhoto(Request $request, User $user)
    {
        $update = array(
            'profile_image' => null
        );

        if ($user != null) {
            $filename = 'storage/users/profiles/' . $user->profile_image;
            File::delete($filename);
        }

        $update = $user->update($update);

        return response()->json(compact('update'));
    }

    public function showChangePassword(User $user)
    {
        $this->authorize('view', $user);

        return view('user.change-password', compact(('user')));
    }

    public function changePassword(Request $request, User $user)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'password_lama'        => array(
                    'required', function ($attribute, $value, $fail) use ($user) {
                        if (!Hash::check($value, $user->password)) {
                            return $fail(__('Password lama anda salah.'));
                        }
                    }
                ),
                'password_baru'        => array('required', 'min:8'),
            ),
        );

        $update = array('password' => Hash::make($request->password_baru));

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->errors()));
        } else {
            $updated =  $user->update($update);

            return response()->json(compact('updated'));
        }
    }

    public function changeBiodata(Request $request, User $user)
    {
        $rules     = array(
            'first_name'    => 'required',
            'last_name'     => 'required',
            'gender'        => 'required',
            'address'       => 'nullable',
            'date_of_birth' => 'nullable|date',
            'phone_number'  => 'nullable|min:9|max:15',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $test   = $request->except('_method', '_token');

            return response()->json(compact('errors', 'test'));
        } else {
            $data      = $request->except('_token', '_method');
            $user->update($data);

            return response()->json(compact('user'));
        }
    }

    public function changeEmail(Request $request, User $user)
    {
        $rules = array(
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'required|password',
        );

        $validator      = Validator::make($request->all(), $rules);
        $errors         = $validator->errors();
        $check_password =  Hash::check($request->password, $user->password);

        if ($validator->fails()) {
            return response()->json(compact('errors'));
        } else {
            $data    = array('email' => $request->email);
            $updated = $user->update($data);

            return response()->json(compact('updated', 'user'));
        }
    }
}
