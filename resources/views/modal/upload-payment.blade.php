<div class="upload-payment">
    <div class="upload-payment-select-image">
        <button id="upload-payment-cancel" class="btn-none d-none"><i class="fa fa-times" aria-hidden="true"></i></button>
        <form id="upload-payment-form" action="#" enctype="multipart/form-data" method="post">
            <label>
                <input id="upload-payment-file" type="file" class="d-none" name="upload_payment">
                <i id="upload-payment-plus-logo" class="fa fa-plus" aria-hidden="true"></i>
                @csrf
            </label>
            <div>
                <img id="upload-payment-image" class="w-100 d-none" src="" alt="gambar">
            </div>
        </form>
    </div>
    <div class=" upload-payment-information">
        <div>File harus berupa jpg, jpeg, png</div>
        <div>Maksimal 2mb</div>
    </div>
</div>
