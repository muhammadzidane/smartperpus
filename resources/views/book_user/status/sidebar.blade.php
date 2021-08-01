<div class="col-sm-3 mb-4">
    <div class="white-content m-0 borbot-gray-bold {{ $margin_zero ?? '' }}">
        <div class="borbot-gray-0 pb-3">
            <div class="mt-1">
                <h4 class="hd-16">Status</h4>

                <!-- Penjual -->
                @can('viewAny', 'App\\Models\User')
                <div class="text-grey">
                    <div class="py-1 position-relative {{ $waiting_for_payment ?? '' }}">
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
                    <div class="py-1 position-relative {{ $on_process ?? '' }}">
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
                    <div class="py-1 position-relative {{ $on_delivery ?? '' }}">
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
                    <div class="py-1 position-relative {{ $arrived ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('book.users.status.arrived') }}">
                            Berhasil

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

                <!-- Pembeli -->
                @else
                <div class="text-grey">
                    <div class="py-1 position-relative {{ $failed ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('status.failed') }}">Tidak berhasil</a>
                        @isset($counts['failed'])
                        @if ($counts['failed'] != 0)
                        <span class="status-circle">{{ $counts['failed'] }}</span>
                        @endif
                        @endisset
                    </div>
                    <div class="py-1 position-relative {{ $waiting_for_payment ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('status.waiting.for.payment') }}">Menunggu pembayaran</a>
                        @isset($counts['waiting_for_payment'])
                        @if ($counts['waiting_for_payment'] != 0)
                        <span class="status-circle">{{ $counts['waiting_for_payment'] }}</span>
                        @endif
                        @endisset
                    </div>
                    <div class="py-1 position-relative {{ $on_process ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('status.on.process') }}">Sedang diproses</a>
                        @isset($counts['on_process'])
                        @if ($counts['on_process'] != 0)
                        <span class="status-circle">{{ $counts['on_process'] }}</span>
                        @endif
                        @endisset
                    </div>
                    <div class="py-1 position-relative {{ $on_delivery ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('status.on.delivery') }}">Sedang dikirim</a>
                        @isset($counts['on_delivery'])
                        @if ($counts['on_delivery'] != 0)
                        <span class="status-circle">{{ $counts['on_delivery'] }}</span>
                        @endif
                        @endisset
                    </div>
                    <div class="py-1 position-relative {{ $arrived ?? '' }}">
                        <a class="text-decoration-none text-grey" href="{{ route('status.success') }}">Berhasil</a>
                        @isset($counts['arrived'])
                        @if ($counts['arrived'] != 0)
                        <span class="status-circle">{{ $counts['arrived'] }}</span>
                        @endif
                        @endisset
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
