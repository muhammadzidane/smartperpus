<?php

namespace App\Http\Controllers;

use App\Models\{Customer, User, District, City, Province};
use Illuminate\Support\Facades\{Validator, Auth, DB};
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
        $validator = Validator::make(
            $request->all(),
            array(
                'nama_penerima'       => array('required', 'string'),
                'alamat_tujuan'       => array('required', 'min:10'),
                'nomer_handphone'     => array('required', 'min:9', 'max:15'),
                'kota_atau_kecamatan' => array('required'),
            )
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {
            $request_address = explode('-', $request->kota_atau_kecamatan);
            $provinsi        = Province::find($request_address[0]);
            $kota            = City::find($request_address[1]);
            $kecamatan       = District::find($request_address[2]);
            $address         = ucwords($request->alamat_tujuan);
            $user            = User::find(Auth::id());

            $create    = array(
                'name'         => $request->nama_penerima,
                'address'      => $address,
                'phone_number' => $request->nomer_handphone,
                'district_id'  => $kecamatan->id,
                'city_id'      => $kota->id,
                'province_id'  => $provinsi->id,
            );
            $message  = 'Berhasil menambah alamat';


            $user->customers()->create($create);

            return redirect()->back()->with('message', $message);
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
        $validator = Validator::make(
            $request->all(),
            array(
                'nama_penerima'       => array('required'),
                'alamat_tujuan'       => array('required', 'min:10'),
                'nomer_handphone'     => array('required', 'min:9', 'max:15'),
            )
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {
            $request_address = explode('-', $request->kota_atau_kecamatan);
            $provinsi        = Province::find($request_address[0]);
            $kota            = City::find($request_address[1]);
            $kecamatan       = District::find($request_address[2]);
            $address         = ucwords($request->alamat_tujuan);

            $update    = array(
                'name'         => $request->nama_penerima,
                'address'      => $address,
                'phone_number' => $request->nomer_handphone,
                'district_id'  => $kecamatan->id,
                'city_id'      => $kota->id,
                'province_id'  => $provinsi->id,
            );

            $customer->update($update);

            $message = 'Berhasil mengedit alamat';

            return redirect()->back()->with('message', $message);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $message = 'Berhasil menghapus alamat';

        $customer->delete();

        $response = array(
            'status'  => 'success',
            'code'    => 200,
            'message' => $message,
        );

        return response()->json($response);
    }

    public function ajaxEditSubmitGetData(Request $request, Customer $customer)
    {
        return response()->json(compact('customer'));
    }

    public function ajaxCityOrDistrict(Request $request)
    {
        $keywords = $request->keywords;

        $selects = array(
            'provinces.id AS province_id',
            'provinces.name AS province_name',
            'cities.type',
            'cities.id AS city_id',
            'cities.name AS city_name',
            'districts.id AS district_id',
            'districts.name AS district_name'
        );

        $request_address = DB::table('provinces')
            ->join('cities', 'provinces.id', '=', 'cities.province_id')
            ->join('districts', 'cities.id', '=', 'districts.city_id')
            ->where('cities.name', 'LIKE', "%$keywords%")
            ->orWhere('districts.name', 'LIKE', "%$keywords%")
            ->select($selects)
            ->get();

        return response()->json(compact('request_address'));
    }

    public function changeMainAddress(Customer $customer)
    {
        if ($customer->exists()) {
            $data = array(
                'main' => false,
            );

            $customer->where('main', true)
                ->update($data);

            $data = array(
                'main' => true,
            );

            $customer->update($data);

            $response = array(
                'status'  => 'success',
                'code'    => 200,
                'data'    => $customer,
                'message' => 'Berhasil mengubah alamat pengiriman utama'
            );
        } else {
            $response = array(
                'status'  => 'fail',
                'code'    => 400,
                'data'    => $customer,
                'message' => 'Alamat tersebut tidak ada, harap cek request'
            );
        }

        return response()->json($response);
    }
}
