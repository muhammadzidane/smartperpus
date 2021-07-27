@extends('layouts/app')
@section('content')

@if (session('pesan'))
<div class="alert alert-primary" role="alert">
    <strong>{{ session('pesan') }}</strong>
</div>
@endif

<div id="pesan" class="alert alert-warning d-none" role="alert">
    <strong></strong>
</div>

<div class="register-user py-4">
    <div id="book-create" class="form-register w-75 mx-auto">
        <a class="float-right" href="{{ isset($book) ? '\books\\' . $book->id : url()->previous() }}">
            <i class="fas fa-long-arrow-alt-left text-body"></i>
        </a>
        <form id="{{ isset($book) ? 'book-edit-form' : 'book-store-form' }}" data-id="{{ isset($book) ? $book->id : '' }}" enctype="multipart/form-data" action="\books\{{ isset($book) ? $book->id : '' }}" method="POST">
            <div class="mb-4">
                <h5 class="tred-bold">{{ isset($book) ? 'Edit Buku' : 'Tambah Buku' }}</h5>
            </div>

            <div class="d-flex flex-wrap">
                <div class="form-group w-50">
                    <label for="nama_penulis">Nama Penulis</label>
                    <input type="text" name="nama_penulis" id="nama_penulis" class="form-control-custom w-90 book-edit-inp" value="{{ old('nama_penulis') ?? (isset($book) ? $book->author->name : '') ?? '' }}">
                </div>
                <div class="form-group w-50">
                    <label for="isbn">ISBN</label>
                    <input type="text" name="isbn" id="isbn" class="form-control-custom w-90 book-edit-inp" value="{{ old('isbn') ?? (isset($book) ? $book->isbn : '') ?? '' }}">
                </div>
                <div class="form-group w-50">
                    <label for="judul_buku">Judul Buku</label>
                    <input type="text" name="judul_buku" id="judul_buku" class="form-control-custom w-90 book-edit-inp" value="{{ old('judul_buku') ?? (isset($book) ? $book->name : '') ?? '' }}">
                </div>
                <div class="form-group w-50">
                    <label class="d-block mr-2">
                        <span>Sinopsis</span>
                        <textarea class="w-100" name="sinopsis" id="sinopsis">{{ old('sinopsis') ?? $book->synopsis->text ?? '' }}</textarea>
                    </label>
                </div>
                <div class="form-group w-50">
                    <label for="price">Harga <small>( tanpa diskon )</small></label>
                    <input type="number" name="price" id="price" class="form-control-custom w-90 book-edit-inp" value="{{ old('price') ?? (isset($book) ? $book->price : '') ?? '' }}">
                </div>
                <div class="form-group w-50">
                    <label for="diskon">Diskon</label>
                    <input type="number" name="diskon" id="diskon" class="form-control-custom w-90 book-edit-inp" value="{{ isset($book) ? $book->discount : ''}}">
                </div>
                <div class="form-group w-50">
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control-custom w-90">
                        @foreach (\App\Models\Category::get() as $category)
                        <option value="{{ $category->id }}" @if (isset($book)) @if ($book->category->name == $category->name)
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
                <div class="form-group w-50">
                    <label for="gambar_sampul_buku">Gambar Sampul Buku (jpg, png) <small class="tred-bold">(Boleh kosong)</small></label>

                    @if (Route::has('books.edit'))
                    @isset($book)
                    <div class="w-25 mb-3">
                        <img id="book-show-image" class="w-100" src="{{ asset('storage/books/' . $book->image )  }}">
                    </div>

                    @else
                    <div class="w-25">
                        <img id="book-show-image" class="w-100" src="">
                    </div>
                    @endisset
                    @endif

                    <input type="file" data-href="{{ isset($book) ? asset('storage/books/' . $book->image) : '' }}" name="gambar_sampul_buku" id="gambar_sampul_buku" class="form-control-custom w-90">
                </div>
            </div>

            <div class="form-group mt-4">
                <button class="button-submit" type="submit">Edit</button>
            </div>

            @isset($book)

            @method('PATCH')
            @endisset

            @csrf
        </form>
    </div>
</div>
@endsection
