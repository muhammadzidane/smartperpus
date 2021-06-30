<div class="data-customer borbot-gray-0 mt-3 pb-3">
    <div class="d-flex justify-content-between">
        <div>
            <div class="destination">
                <i class="fas fa-circle-notch text-grey mr-1"></i>
                <span class="tbold">Alamat Tujuan</span>
                <div id="destination" class="text-grey" data-destination-id="22"
                data-subdistrict-id="317" data-destination-type="subdistrict">
                   <span class="customer-address">{{ $customer_address . ', ' }}</span>
                   <span class="customer-district">{{ $customer_district . ', ' }}</span>
                   <span class="customer-city">{{ $customer_city  . '. ' }}</span>
                   <span class="customer-province">{{ $customer_province }}</span>
                </div>
            </div>
            <div class="mt-3">
                <i class="fas fa-circle-notch text-grey mr-1"></i>
                <span class="tbold">Nama Penerima</span> -
                <span class="customer-name text-grey">{{ $customer_name }}</span>
                <span class="customer-phone-number text-grey">( {{ $customer_phone_number }} )</span>
            </div>
        </div>
        <div>
            <label>
                <input class="customer-address" type="radio" name="alamat_pengiriman" value="{{ $customer_id }}">
                <span>Pilih</span>
            </label>
        </div>
    </div>
    <div class="d-flex mt-2">
        <div class="ml-auto">
            <div class="d-flex">
                <div class="c-middle mr-1">
                    <button class="customer-edit btn-none tred-bold"
                      data-id="{{ $customer_id }}" data-toggle="modal"
                      data-target="#modal-customer-edit">Edit</button>
                </div>
                <div>
                    <form class="customer-destroy-form" data-id="{{ $customer_id }}"
                      action="{{ route('customers.destroy', array('customer' =>  $customer_id)) }}"
                      method="post">
                        <button class="btn btn-red btn-sm-0" type="submit">Hapus</button>

                        @method('DELETE')

                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
