<form action="#" class="mt-4">
    <div class="d-flex justify-content-between">
        <div class="tbold mr-2">KATEGORI PILIHAN</div>
        <div>
            <button id="www" type="button" class="btn btn-outline-success mr-2">Ganti urutan</button>
            <button id="home-categories-edit" type="button" class="btn btn-outline-success">Edit</button>
        </div>
    </div>
    <div>Pilih untuk mengganti urutan</div>
    <div class="row mt-3">
        <div class="col-3">
            <div class="kp-edit urutan">
                <select id="category-sort-1" data-id="1" class="nomer-urutan" name="">
                    <option value="1" selected="selected">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
                <img class="kp-img-modal" src="{{ asset('img/kategori-pilihan/biografi.jpg') }}" alt="Gambar Kategori">
            </div>
        </div>
        <div class="col-3">
            <div class="kp-edit urutan">
                <select id="category-sort-2" data-id="2" class="nomer-urutan" name="">
                    <option value="1">1</option>
                    <option value="2" selected="selected">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
                <img class="kp-img-modal" src="{{ asset('img/kategori-pilihan/biografi.jpg') }}" alt="Gambar Kategori">
            </div>
        </div>
        <div class="col-3">
            <div class="kp-edit urutan">
                <select id="category-sort-3" data-id="3" class="nomer-urutan" name="">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3" selected="selected">3</option>
                    <option value="4">4</option>
                </select>
                <img class="kp-img-modal" src="{{ asset('img/kategori-pilihan/biografi.jpg') }}" alt="Gambar Kategori">
            </div>
        </div>
        <div class="col-3">
            <div class="kp-edit urutan">
                <select id="category-sort-4" data-id="4" class="nomer-urutan" name="">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4" selected="selected">4</option>
                </select>
                <img class="kp-img-modal" src="{{ asset('img/kategori-pilihan/biografi.jpg') }}" alt="Gambar Kategori">
            </div>
        </div>
    </div>
</form>
