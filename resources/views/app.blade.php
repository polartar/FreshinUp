@extends('fresh-bus-forms::layouts.main')

@push('meta')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="square-app-id" content="{{ env('SQUARE_APP_ID') }}">
    <meta name="square-environment" content="{{ env('SQUARE_ENVIRONMENT') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fix For Chunk Error In Webpack and Vue Router -->
    <base href="/"/>
@endpush

@push('favicon')
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
@endpush

@push('css')

@endpush

@push('header_js')
    {{--TODO: place in a bootloader.js file --}}
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
    <script>
        //TODO: move into webpacked file?
        WebFont.load({
            google: {
                families: ['Material Icons','Roboto', 'Montserrat', 'Avenir Next'], // test: Dancing Script
            },
            custom: {
                families: ['Font Awesome 5 Icons:400,900', 'Font Awesome 5 Brands:400'],
                urls: ['//use.fontawesome.com/releases/v5.0.13/css/all.css']
            },
        });
    </script>

@endpush

@push('title')
    <title>{{ config('app.name') }} </title>
@endpush

@section('content')
    <div id="app" v-cloak>
        <div id="v-cloak--block">
            <div class="loader">
                <!-- Preloader Before Vue is Loaded -->
                <div class="loader-inner">
                    <div class="loader-line-wrap">
                        <div class="loader-line"></div>
                    </div>
                    <div class="loader-line-wrap">
                        <div class="loader-line"></div>
                    </div>
                    <div class="loader-line-wrap">
                        <div class="loader-line"></div>
                    </div>
                    <div class="loader-line-wrap">
                        <div class="loader-line"></div>
                    </div>
                    <div class="loader-line-wrap">
                        <div class="loader-line"></div>
                    </div>
                </div>
            </div>
        </div>
        <app></app>
    </div>
@endsection

@push('footer_js')

    <script>
        window.__INITIAL_STATE__ = {
            env: @json(env('APP_ENV'))
        }
        window.gaTrackingCode = @json(env('GOOGLE_ANALYTICS'))
    </script>

    @if(config('app.env') !== 'production')
        <!-- Local ENV Assets -->
        <script src="{{mix('/js/main.js')}}"></script>
    @else
        <!-- Production ENV Assets -->
        <script src="{{mix('/js/manifest.js')}}"></script>
        <script src="{{mix('/js/vendors.js')}}"></script>
        <script src="{{mix('/js/main.min.js')}}"></script>
    @endif

@endpush
