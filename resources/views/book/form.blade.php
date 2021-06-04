<div class="text-right p-0"><a href="{{ route('home') }}"><i class="fas fa-long-arrow-alt-left text-body"></i></a></div>
<div class="mb-4">
    <h5 class="tred-bold">{{ $title }}</h5>
</div>

<div class="d-flex flex-wrap">
    <div class="form-group w-50">
        <label for="nama_penulis">Nama Penulis</label>
        <input type="text" name="nama_penulis" id="nama_penulis"
          class="form-control-custom w-90 book-edit-inp" value="{{ old('nama_penulis') ?? $book->author->name ?? '' }}">
        @error('nama_penulis')
        <span class="tred small small" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group w-50">
        <label for="isbn">ISBN</label>
        <input type="number" name="isbn" id="isbn"
          class="form-control-custom w-90 book-edit-inp" value="{{ old('isbn') ?? $book->isbn ?? '' }}">
        @error('isbn')
        <span class="tred small small" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group w-50">
        <label for="judul_buku">Judul Buku</label>
        <input type="text" name="judul_buku" id="judul_buku"
          class="form-control-custom w-90 book-edit-inp" value="{{ old('judul_buku') ?? $book->name ?? '' }}">
        @error('judul_buku')
        <span class="tred small" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group w-50">
        <div class="d-flex">
            <label for="sinopsis" class="mr-2">Sinopsis</label>
            <textarea class="w-75" name="sinopsis" id="sinopsis">{{ old('sinopsis') ?? $book->synopsis->text ?? '' }}</textarea>
        </div>

        @error('sinopsis')
        <div class="tred small" role="alert">
            <strong>{{ $message }}</strong>
        </div>
        @enderror
    </div>
    <div class="form-group w-50">
        <label for="price">Harga</label>
        <input type="number" name="price" id="price"
          class="form-control-custom w-90 book-edit-inp" value="{{ old('price') ?? $book->price ?? '' }}">
        @error('price')
        <span class="tred small" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group w-50">
        <label for="tambah_diskon">Tambah / Edit Diskon <small class="tred-bold">(Boleh kosong)</small></label>
        <input type="number" name="tambah_diskon" id="tambah_diskon"
          class="form-control-custom w-90 book-edit-inp" value="{{ old('tambah_diskon') ?? $book->diskon ?? '' }}">
        @error('tambah_diskon')
        <span class="tred small" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group w-50">
        <label for="kategori">Kategori</label>
        <select name="kategori" id="kategori" class="form-control-custom w-90">
            @foreach (\App\Models\Category::get() as $category)
                <option
                  value="{{ $category->name }}"
                    @if (isset($book))
                        @if ($book->categories[0]->name == $category->name)
                            {{ 'selected' }}
                        @endif
                    @else
                        @if ($category->name == old('kategori')))
                            {{ 'selected' }}
                        @endif
                    @endif

                >{{ $category->name }}</option>
            @endforeach
        </select>
        @error('kategori')
        <span class="tred small" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group w-50">
        <label for="tersedia_dalam_ebook">Tersedia Dalam E-book</label>
        <div>
            <select name="tersedia_dalam_ebook" id="tersedia_dalam_ebook" class="form-control-custom w-90">
                <option value="0" {{ old('tersedia_dalam_ebook') ?? $book->ebook ?? '' == 0 ? 'selected' : '' }}>Tidak</option>
                <option value="1" {{ old('tersedia_dalam_ebook') ?? $book->ebook ?? '' == 1 ? 'selected' : '' }}>Ya</option>
            </select>
        </div>
        @error('tersedia_dalam_ebook')
        <span class="tred small" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group w-50">
        <label for="jumlah_barang">Jumlah Buku Cetak</label>
        <input type="text" name="jumlah_barang" id="jumlah_barang"
          class="form-control-custom w-90 book-edit-inp"
          value="{{ old('jumlah_barang') ?? $book->printedStock->amount ?? '' }}">
        @error('jumlah_barang')
        <span class="tred small" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group w-50">
        <label for="penerbit">Penerbit</label>
        <input type="text" name="penerbit" id="penerbit"
          class="form-control-custom w-90 book-edit-inp"
          value="{{ old('penerbit') ?? $book->publisher ?? '' }}">
        @error('penerbit')
        <span class="tred small" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group w-50">
        <label for="jumlah_halaman">Jumlah Halaman</label>
        <input type="number" name="jumlah_halaman" id="jumlah_halaman"
          class="form-control-custom w-90 book-edit-inp"
          value="{{ old('jumlah_halaman') ?? $book->pages ?? '' }}">

        @error('jumlah_halaman')
        <span class="tred small" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group w-50">
        <label for="tanggal_rilis">Tanggal Rilis</label>

        <input type="date" name="tanggal_rilis" id="tanggal_rilis"
          class="form-control-custom w-90"


          @isset($book)
            value="{{ old('tanggal_rilis')  ?? $book->getRawOriginal('release_date') ?? '' }}"
          @endisset
    >

        @error('tanggal_rilis')
        <span class="tred small" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group w-50">
        <label for="subtitle">Subtitle</label>
        <input type="text" name="subtitle" id="subtitle"
          class="form-control-custom w-90 book-edit-inp"
          value="{{ old('subtitle') ?? $book->subtitle ?? '' }}">
        @error('subtitle')
        <span class="tred small" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group w-50">
        <label for="berat">Berat (gram)</label>
        <input type="number" name="berat" id="berat"
          class="form-control-custom w-90 book-edit-inp"
          value="{{ old('berat') ?? $book->weight ?? '' }}">
        @error('berat')
        <span class="tred small" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group w-50">
        <label for="panjang">Panjang (cm)</label>
        <input type="number" min="0.0" step="0.01" name="panjang" id="panjang"
          class="form-control-custom w-90 book-edit-inp"
          value="{{ old('panjang') ?? $book->width ?? '' }}">
        @error('panjang')
        <span class="tred small" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group w-50">
        <label for="lebar">Lebar (cm)</label>
        <input type="number" min="0.0" step="0.01" name="lebar" id="lebar"
          class="form-control-custom w-90 book-edit-inp"
          value="{{ old('lebar') ?? $book->height ?? '' }}">
        @error('lebar')
        <span class="tred small" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group w-50">
        <label for="gambar_sampul_buku">Gambar Sampul Buku (jpg, png) <small class="tred-bold">(Boleh kosong)</small></label>

        @if (Route::has('books.edit'))
            @isset($book)
                <div class="w-25 mb-3">
                    <img class="w-100" src="{{ asset('storage/books/' . $book->image ) }}">
                </div>
            @endisset
        @endif

        <input type="file" name="gambar_sampul_buku" id="gambar_sampul_buku"
          class="form-control-custom w-90">

        @error('gambar_sampul_buku')
        <span class="tred small" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="form-group mt-4">
    <button id="{{ $button_id }}" class="button-submit" type="submit">{{ $button_text  }}</button>
</div>
