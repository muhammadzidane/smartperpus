<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://pro.rajaongkir.com/api/city?key=ce496165f4a20bc07d96b6fe3ab41ded",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: ce496165f4a20bc07d96b6fe3ab41ded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $cities = json_decode($response);
            $results = $cities->rajaongkir->results;

            foreach ($results as $result) {

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?key=ce496165f4a20bc07d96b6fe3ab41ded&city=" . $result->city_id,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "key: ce496165f4a20bc07d96b6fe3ab41ded"
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    $cities = json_decode($response);
                    $results = $cities->rajaongkir->results;

                    foreach ($results as $result) {
                        $datas  = array(
                            'city_id' => $result->city_id,
                            'type'    => $result->type,
                            'name'    => $result->subdistrict_name,
                        );

                        City::find($result->city_id)->districts()->create($datas);
                    }
                }
            }
        }
    }
}
