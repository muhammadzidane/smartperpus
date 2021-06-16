<div class="modal fade" id="{{ $modal_trigger }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered p-5" role="document">
        <div class="modal-content modal-content-login">
            <div class="px-3 mb-4 d-flex justify-content-between">
                <h5 class="modal-title tred login-header">{{ $modal_header }}</h5>
                <button type="button" class="close c-p" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="{{ $modal_trigger }}">
                    <div class="form-group mx-auto">
                        <div class="">
                            <label for="nama_penerima">Nama Penerima</label>
                            <input type="text" name="nama_penerima"
                              class="form-control-custom book-edit-inp" value="{{ old('nama_penerima') }}">

                            @error('nama_penerima')
                                <span class="tred small small" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <span class="tred small small" role="alert">
                                <strong class="error_nama_penerima validate-error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="form-group mx-auto mt-4">
                        <div class="">
                            <label for="alamat_tujuan">Alamat Tujuan</label>
                            <input type="text" name="alamat_tujuan"
                            class="form-control-custom book-edit-inp" value="{{ old('alamat_tujuan') }}">
                        </div>

                        @error('alamat_tujuan')
                            <span class="tred small small" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <span class="tred small small" role="alert">
                            <strong class="error_alamat_tujuan validate-error"></strong>
                        </span>
                    </div>
                    <div class="form-group mx-auto mt-4">
                        <div class="">
                            <label for="provinsi">Provinsi</label>
                            <select class="form-control-custom provinsi" name="provinsi">
                                @foreach (\App\Models\Province::get() as $province)
                                    <option
                                      {{ \App\Models\Province::find(old('provinsi') ?? 1)->name == $province->name ? 'selected' : '' }}
                                      value="{{ $province->id }}">{{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mx-auto mt-4">
                        <div class="">
                            <label for="kota-atau-kabupaten">Kota / Kabupaten</label>
                            <select class="form-control-custom kota-atau-kabupaten" name="kota_atau_kabupaten">
                                @foreach (\App\Models\Province::first()->cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mx-auto mt-4">
                        <div class="">
                            <label for="kecamatan">Kecamatan</label>
                            <select class="form-control-custom kecamatan" name="kecamatan">

                                @foreach (\App\Models\City::first()->districts as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mx-auto mt-4">
                        <div class="">
                            <label for="nomer_handphone">Nomer Handphone</label>
                            <input min="0" type="number" name="nomer_handphone"
                            class="form-control-custom book-edit-inp" value="{{ old('nomer_handphone') }}">

                            @error('nomer_handphone')
                                <span class="tred small small" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <span class="tred small small" role="alert">
                                <strong class="error_nomer_handphone validate-error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="form-group mt-5">
                        <button class="button-submit active-login" type="submit">{{ $modal_submit_button }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
