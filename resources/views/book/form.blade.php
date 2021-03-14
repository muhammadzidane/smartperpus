<div class="form-group">
    <label for="name">Judul Buku</label>
    <input type="text" name="name" id="name" class="form-control" placeholder=""
        value="{{ isset($book->name) ? $book->name : old('name') }}">
</div>

@error('name')
<small class="text-danger">Judul {{ $message }}</small>
@enderror

<div class="form-group">
    <label for="price">Harga</label>
    <input type="number" name="price" id="price" min="0" class="form-control" placeholder=""
        value="{{ isset($book->price) ? $book->price : old('price') }}">
</div>

@error('price')
    <small class="text-danger">Harga {{ $message }}</small>
@enderror

<div class="form-group">
    <label for="image">Foto Sampul Buku</label>
    <input type="file" class="form-control-file" name="image" id="image" placeholder=""
        value="{{ isset($book->image) ? $book->image : old('image') }}">
</div>

@error('image')
    <small class="text-danger">Sampul {{ $message }}</small>
@enderror

<div class="form-group">
  <label for="author_change">Ganti Author Yang Sudah Ada</label>
    <select class="form-control" name="author_change" id="author_change">
        @foreach($authors as $author)
            <option value="{{ $author->id }}"
                @if($author->name == $book->authors[0]->name)
                    {{ 'selected' }}
                @endif
            >
            {{ $author->name }}</option>
        @endforeach
    </select>
</div>

<div class="row">
    <div class="col-md-6">

        @foreach($book_categories as $key => $category)

        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" class="form-check-input" name="categories[]" value="{{ $category }}">
                {{ $category }}
            </label>
        </div>

        @endforeach

    </div>
    <div class="col-md-6">

    </div>
</div>

@error('categories')
<small class="text-danger">Categori {{ $message }}</small>
@enderror

<div class="form-group my-2">
    <label for="">Sinopsis</label>
    <textarea class="form-control" name="synopsis" rows="3">
        {{ isset($book->synopsis->text) ? $book->synopsis->text : old('synopsis') }}
    </textarea>
</div>

@error('synopsis')
<div>
    <small class="text-danger">Sinopsis {{ $message }}</small>
</div>
@enderror

@csrf

<button class="btn btn-primary my-3" type="submit">{{ $submit_button }}</button>
