<div class="modal fade" id="{{ $modal_trigger_id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog {{ $modal_size_class }} modal-dialog-centered p-5" role="document">
        <div class="modal-content modal-content-login">
            <div class="px-3 mb-4 d-flex justify-content-between">
                <h5 class="modal-title tred login-header">{{ $modal_header }}</h5>
                <button type="button" class="close c-p" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @switch($modal_content)
                @case('upload_payment')
                @include('modal.upload-payment')
                @break

                @case('bill')
                <div class="bill"></div>
                @break

                @endswitch
            </div>
        </div>
    </div>
</div>
