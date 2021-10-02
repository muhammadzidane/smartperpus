@extends('layouts/app')
@section('content')

@include('content-header',
    array(
        'title' => isset($book) ? 'Edit Buku' : 'Tambah Buku',
        'icon_html' => '<i class="user-icon fas fa-book text-green mr-2"></i></i>'
    )
)

<div class="row d-md-flex flex-md-row-reverse mt-md-4">
    @include('profile-sidebar')

    <div class="col-md-9">
        <div id="book-create" class="form-register">
            <form id="{{ isset($book) ? 'book-edit-form' : 'book-store-form' }}" data-id="{{ isset($book) ? $book->id : '' }}" enctype="multipart/form-data" action="/books/{{ isset($book) ? $book->id : '' }}" method="POST">
                <div class="mb-4">
                    <h5 class="tred-bold">{{ isset($book) ? 'Edit Buku' : 'Tambah Buku' }}</h5>
                </div>

                <div class="d-flex flex-wrap">
                    <div class="form-group w-50">
                        <label for="nama_penulis">Nama Penulis</label>
                        <div class="position-relative">
                            <input type="text" name="nama_penulis" id="nama_penulis" class="form-control-custom w-90 book-edit-inp" value="{{ old('nama_penulis') ?? (isset($book) ? $book->author->name : '') ?? '' }}">
                        </div>

                    </div>
                    <div class="form-group w-50">
                        <label for="isbn">ISBN <small>(13 Digit)</small></label>
                        <input type="text" name="isbn" id="isbn" class="form-control-custom w-90 book-edit-inp" value="{{ old('isbn') ?? (isset($book) ? $book->isbn : '') ?? '' }}">
                    </div>
                    <div class="form-group w-50">
                        <label for="judul_buku">Judul Buku</label>
                        <input type="text" name="judul_buku" id="judul_buku" class="form-control-custom w-90 book-edit-inp" value="{{ old('judul_buku') ?? (isset($book) ? $book->name : '') ?? '' }}">
                    </div>
                    <div class="form-group w-50">
                        <label class="d-block mr-2">
                            <span>Sinopsis</span>
                            <textarea class="w-100" rows="5" name="sinopsis" id="sinopsis">{{ old('sinopsis') ?? $book->synopsis->text ?? '' }}</textarea>
                        </label>
                    </div>
                    <div class="form-group w-50">
                        <label for="price">Harga <small>( tanpa diskon )</small></label>
                        <input type="number" name="price" id="price" class="form-control-custom w-90 book-edit-inp" value="{{ old('price') ?? (isset($book) ? $book->price : '') ?? '' }}">
                    </div>
                    <div class="form-group w-50">
                        <label for="diskon">Diskon <small>(boleh kosong)</small></label>
                        <input type="number" name="diskon" id="diskon" class="form-control-custom w-90 book-edit-inp" value="{{ isset($book) ? $book->discount : ''}}">
                    </div>
                    <div class="form-group w-50">
                        <label for="kategori">Kategori</label>
                        <select name="kategori" id="kategori" class="form-control-custom w-90">
                            @foreach (\App\Models\Category::get() as $category)
                            <option value="{{ $category->id }}"
                                @if (isset($book)) @if ($book->category->name == $category->name)
                                    {{ 'selected' }}
                                @endif

                                @else

                                    @if ($category->name == old('kategori')))
                                        {{ 'selected' }}
                                    @endif
                                @endif

                                >{{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group w-50">
                        <label for="tersedia_dalam_ebook">Tersedia Dalam E-book</label>
                        <div>
                            <select name="tersedia_dalam_ebook" id="tersedia_dalam_ebook" class="form-control-custom w-90">
                                <option value="0" {{ old('tersedia_dalam_ebook') ?? $book->ebook ?? '' == 0 ? 'selected' : '' }}>Tidak</option>
                                <option value="1" {{ old('tersedia_dalam_ebook') ?? $book->ebook ?? '' == 1 ? 'selected' : '' }}>Ya</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group w-50">
                        <label for="jumlah_barang">Jumlah Stok Buku Cetak</label>
                        <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control-custom w-90 book-edit-inp" value="{{ old('jumlah_barang') ?? $book->printed_book_stock ?? '' }}">
                    </div>
                    <div class="form-group w-50">
                        <label for="penerbit">Penerbit</label>
                        <input type="text" name="penerbit" id="penerbit" class="form-control-custom w-90 book-edit-inp" value="{{ old('penerbit') ?? $book->publisher ?? '' }}">
                    </div>
                    <div class="form-group w-50">
                        <label for="jumlah_halaman">Jumlah Halaman</label>
                        <input type="number" name="jumlah_halaman" id="jumlah_halaman" class="form-control-custom w-90 book-edit-inp" value="{{ old('jumlah_halaman') ?? $book->pages ?? '' }}">
                    </div>
                    <div class="form-group w-50">
                        <label for="tanggal_rilis">Tanggal Rilis</label>

                        <input type="date" name="tanggal_rilis" id="tanggal_rilis" class="form-control-custom w-90" @isset($book) value="{{ old('tanggal_rilis')  ?? $book->getRawOriginal('release_date') ?? '' }}" @endisset>
                    </div>
                    <div class="form-group w-50">
                        <label for="subtitle">Subtitle</label>
                        <input type="text" name="subtitle" id="subtitle" class="form-control-custom w-90 book-edit-inp" value="{{ old('subtitle') ?? $book->subtitle ?? '' }}">
                    </div>
                    <div class="form-group w-50">
                        <label for="berat">Berat (gram)</label>
                        <input type="number" name="berat" id="berat" class="form-control-custom w-90 book-edit-inp" value="{{ old('berat') ?? $book->weight ?? '' }}">
                    </div>
                    <div class="form-group w-50">
                        <label for="panjang">Panjang (cm)</label>
                        <input type="number" min="0.0" step="0.01" name="panjang" id="panjang" class="form-control-custom w-90 book-edit-inp" value="{{ old('panjang') ?? $book->width ?? '' }}">
                    </div>
                    <div class="form-group w-50">
                        <label for="lebar">Lebar (cm)</label>
                        <input type="number" min="0.0" step="0.01" name="lebar" id="lebar" class="form-control-custom w-90 book-edit-inp" value="{{ old('lebar') ?? $book->height ?? '' }}">
                    </div>

                    @if (!isset($book))
                        <div class="form-group w-50">
                            <div for="gambar_sampul_buku">Gambar Sampul Buku (jpg, png, jpeg)</div>
                            <div class="w-50">
                                <div class="book-create-img">
                                    <img id="book-show-image" src="{{ asset('img/books_test_image/andins-kitchen-masak-tanpa-mumet.jpg') }}">
                                </div>
                            </div>
                            <div id="example-book-text" class="tred">Contoh*</div>


                            <input class="mt-4" type="file" data-href="{{ isset($book) ? asset('storage/books/' . $book->image) : '' }}" name="gambar_sampul_buku" max="2000" id="gambar_sampul_buku" class="form-control-custom w-90" accept="image/png, image/jpeg, image/jpg">
                        </div>
                    @endif
                </div>

                <div class="form-group mt-4">
                    <button class="btn btn-outline-danger w-100" type="submit">{{ isset($book) ? 'Edit' : 'Tambah' }}</button>
                </div>

                @isset($book)
                    @method('PATCH')
                @endisset

                @csrf
            </form>
        </div>
    </div>
</div>

@endsection
