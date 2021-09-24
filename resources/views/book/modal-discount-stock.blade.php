<div class="modal fade" id="{{ $target_id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered p-5" role="document">
        <div class="modal-content modal-content-login">
            <div class="px-3 mb-4 d-flex justify-content-between">
                <h5 class="modal-title tred login-header">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-0">
                <form id="{{ $target_id }}-form" action="{{ $form_action }}" method="POST">
                    <div class="form-group">
                        <input class="form-control-custom"
                        type="number" name="amount" min="1" placeholder="Jumlah" autocomplete="off" value="{{ $target_id == 'book-add-discount-modal' ? $book->discount : '' }}">
                    </div>
                    <div class="text-right mt-4">
                        <button type="submit" class="cursor-disabled btn btn-outline-danger" disabled>Tambah</button>
                    </div>

                    @isset($method)
                        @method($method)
                    @endisset

                    @csrf
                </form>
            </div>
        </div>
    </div>
    </div>
