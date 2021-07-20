<div class="col-md-3 mb-4">
    <div class="white-content borbot-gray-bold {{ $margin_zero ?? '' }}">
        <div class="borbot-gray-0 pb-3">
            <div class="mt-1">
                <h4 class="hd-16">Status</h4>

                @can('viewAny', 'App\\Models\User')
                <div class="text-grey">
                    <div class="position-relative {{ $waiting_for_payment ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('uploaded.payments') }}">
                            Unggahan bukti pembayaran

                            @if (App\Models\BookUser::get()
                            ->where('payment_status', 'waiting_for_confirmation')
                            ->where('upload_payment_image', '!=' , null)->count() != 0
                            )
                            <div class="status-circle">
                                {{ App\Models\BookUser::get()
                                    ->where('payment_status', 'waiting_for_confirmation')
                                    ->where('upload_payment_image', '!=' , null)->count()
                                }}
                            </div>
                            @endif
                        </a>
                    </div>
                    <div class="position-relative {{ $on_process ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('confirmed.orders') }}">
                            Sedang diproses

                            @if (App\Models\BookUser::get()
                            ->where('upload_payment_image', '!=' , null)
                            ->where('payment_status', 'order_in_process')
                            ->count() != 0
                            )
                            <div class="status-circle">
                                {{
                                    App\Models\BookUser::get()->where('upload_payment_image', '!=' , null)
                                    ->where('upload_payment_image', '!=' , null)
                                    ->where('payment_status', 'order_in_process')
                                    ->count()
                                }}
                            </div>
                            @endif
                        </a>
                    </div>
                    <div class="position-relative {{ $on_delivery ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('on.delivery') }}">
                            Sedang dikirim
                            @if (App\Models\BookUser::get()
                            ->where('upload_payment_image', '!=' , null)
                            ->where('payment_status', 'being_shipped')
                            ->count() != 0
                            )
                            <div class="status-circle">
                                {{
                                    App\Models\BookUser::get()->where('upload_payment_image', '!=' , null)
                                    ->where('upload_payment_image', '!=' , null)
                                    ->where('payment_status', 'being_shipped')
                                    ->count()
                                }}
                            </div>
                            @endif
                        </a>
                    </div>
                    <div class="position-relative {{ $arrived ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('on.delivery') }}">
                            Telah sampai

                            @if (App\Models\BookUser::get()
                            ->where('upload_payment_image', '!=' , null)
                            ->where('payment_status', 'arrived')
                            ->count() != 0
                            )
                            <div class="status-circle">
                                {{ App\Models\BookUser::get()->where('upload_payment_image', '!=' , null)
                                    ->where('upload_payment_image', '!=' , null)
                                    ->where('payment_status', 'arrived')
                                    ->count()
                                }}
                            </div>
                            @endif
                        </a>
                    </div>
                </div>

                @else
                <div class="text-grey">
                    <div class="{{ $waiting_for_payment ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('waiting.for.payment') }}">Menunggu pembayaran</a>
                        @if (App\Models\BookUser::get()
                        ->where('payment_status', 'waiting_for_confirmation')
                        ->where('upload_payment_image', '!=' , null)->count() != 0
                        )
                        <span class="status-circle">
                            {{ App\Models\BookUser::get()
                                ->where('payment_status', 'waiting_for_confirmation')
                                ->where('upload_payment_image', '!=' , null)->count()
                            }}
                        </span>
                        @endif
                    </div>
                    <div class="{{ $on_process ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('on.delivery') }}">Sedang diproses</a>
                    </div>
                    <div class="{{ $on_delivery ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('on.delivery') }}">Sedang dikirim</a>
                    </div>
                    <div class="{{ $arrived ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('on.delivery') }}">Telah sampai</a>
                    </div>
                </div>
                @endcan

            </div>
        </div>
        <div class="mt-3">
            <div class="mt-1">
                <h4 class="hd-16">Kontak Masuk</h4>
                <div class="text-grey">
                    <div>
                        <a class="text-decoration-none text-grey" href="#">Ulasan</a>
                    </div>
                    <div class="{{ $product_discutions ?? '' }}">
                        <a class="text-decoration-none text-grey" href="#" class=" $product_discutions ?? ''  }}">Chat</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
