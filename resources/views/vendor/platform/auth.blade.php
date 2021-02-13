@extends('platform::app')

@push('head')
  <meta name="robots" content="noindex"/>
  @include('partials.favicon')
@endpush

@push('stylesheets')
  <style>
    .mt-login-bg {
      position: fixed;
      left: 0;
      top: 0;
      right: 0;
      bottom: 0;
      width: 100%;
      height: 100%;
      background-color: #242c3d;
      background-position: center;
      background-size: cover;
    }

    @if($img = config('platform.login_bgi'))
    .mt-login-bg {
      background-image: url({{ $img }});
    }

    .mt-login-main {
      -webkit-backdrop-filter: blur(3px);
      backdrop-filter: blur(3px);
    }

    @endif

    .mt-login-main {
      background: rgba(255, 255, 255, 0.7);
      padding: 0.75rem;
      border-radius: 5px;
    }

    .mt-login-top {
      padding-bottom: 0.75rem;
    }

    .mt-login-top a {
      color: #000;
    }
  </style>
@endpush

@section('body-right')

  <div class="mt-login-bg"></div>
  <div class="form-signin container h-full p-0 px-sm-5 py-5 my-sm-5">
    <div class="row justify-content-center">
      <div class="col-md-7 col-lg-6">
        <div class="mt-login-main">
          <div class="mt-login-top">
            <a class="d-flex justify-content-center" href="{{Dashboard::prefix()}}">
              <p class="h2 n-m font-weight-normal v-center">
                <span class="ml-3 d-none d-sm-block">{{ config('app.name') }}</span>
              </p>
            </a>
          </div>
          <div class="bg-white p-4 p-sm-5 rounded shadow">
            @yield('content')
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
