@extends('layouts.app')
@section('content')

@include('content-header',
array(
'icon_html' => '<i class="user-icon fas fa-chart-pie mr-2"></i>',
'title' => 'Penghasilan Hari ini',
))

<div class="row d-md-flex flex-md-row-reverse mt-md-4">
    @include('profile-sidebar')

    <div class="col-md-9">
        @include('status.search-content', array(
            'placeholder' => 'Cari'
        ))

        <div class="mt-4">
            <div class="white-content">
                <div>wew</div>
            </div>
        </div>
    </div>
</div>

@endsection
