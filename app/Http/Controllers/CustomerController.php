<?php

namespace App\Http\Controllers;

use App\Models\{ Customer, User, District, City, Province };
use Illuminate\Support\Facades\{ Validator, Auth };
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $validator = Validator::make($request->all(),
            array(
                'nama_penerima'       => array('required', 'string'),
                'alamat_tujuan'       => array('required', 'min:10'),
                'nomer_handphone'     => array('required', 'min:12', 'max:13'),
            )
        );

        $kecamatan = District::find($request->kecamatan)->name;
        $kota      = City::find($request->kota_atau_kabupaten)->name;
        $provinsi  = Province::find($request->provinsi)->name;
        $address   = ucwords($request->alamat_tujuan);
        $user      = User::find(Auth::id());
        $create    = array (
            'name'         => $request->nama_penerima,
            'address'      => $address,
            'phone_number' => $request->nomer_handphone,
            'district'     => $kecamatan,
            'city'         => $kota,
            'province'     => $provinsi,
        );

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(array('errorMsg' => $validator->errors()));
            }
            else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        else {
            $customer = $user->customer()->create($create);
            $pesan    = 'Berhasil menambah alamat ' . ucwords($customer->name);

            if ($request->ajax()) {
                $status       = true;

                $dataCustomer = view('book.data-customer',
                    array(
                        'customer_address'      => $address . ', ' . $kecamatan . ', ' . $kota . '. ' . $provinsi,
                        'customer_district'     => $kecamatan,
                        'customer_city'         => $kota,
                        'customer_province'     => $provinsi,
                        'customer_name'         => $customer->name,
                        'customer_phone_number' => $customer->phone_number,
                        'customer_id'           => $customer->id,
                    )
                )->render();

                return response()->json(compact('pesan', 'status', 'dataCustomer'));
            }
            else {
                return redirect()->back()->with('pesan', $pesan);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(),
            array(
                'nama_penerima'       => array('required', 'string'),
                'alamat_tujuan'       => array('required', 'min:10'),
                'nomer_handphone'     => array('required', 'min:12', 'max:13'),
            )
        );

        $kecamatan = District::find($request->kecamatan)->name;
        $kota      = City::find($request->kota_atau_kabupaten)->name;
        $provinsi  = Province::find($request->provinsi)->name;
        $address   = ucwords($request->alamat_tujuan);
        $pesan     = 'Berhasil menupdate alamat ' . ucwords($request->nama_penerima);
        $user      = User::find(Auth::id());
        $update    = array (
            'name'         => $request->nama_penerima,
            'address'      => $address,
            'phone_number' => $request->nomer_handphone,
            'district'     => $kecamatan,
            'city'         => $kota,
            'province'     => $provinsi,
        );

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(array('errorMsg' => $validator->errors()));
            }
            else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        else {
            $customer->update($update);

            $status = true;

            if ($request->ajax()) {
                return response()->json(compact('pesan', 'status'));
            }
            else {
                return redirect()->back()->with('pesan', $pesan);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Customer $customer)
    {
        $pesan = 'Berhasil menghapus alamat ' . ucwords($customer->name);

        $customer->delete();

        if (!$request->ajax()) {
            return redirect()->back()->with('pesan', $pesan);
        }
        else {
            return response()->json()->status();
        }
    }

    public function ajaxEditSubmitGetData(Request $request, Customer $customer) {
        return response()->json(compact('customer'));
    }
}
