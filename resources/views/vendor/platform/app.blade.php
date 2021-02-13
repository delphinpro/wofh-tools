<!DOCTYPE html>
<html lang="{{  app()->getLocale() }}" data-controller="layouts--html-load">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>
    @yield('title', config('app.name'))
    @hasSection('title')
      - {{ config('app.name') }}
    @endif
  </title>
  <meta name="csrf_token" content="{{  csrf_token() }}" id="csrf_token" data-turbolinks-permanent>
  <meta name="auth" content="{{  Auth::check() }}" id="auth" data-turbolinks-permanent>
  @if(file_exists(public_path('/css/orchid/orchid.css')))
    <link rel="stylesheet" type="text/css" href="{{  mix('/css/orchid/orchid.css') }}">
  @else
    <link rel="stylesheet" type="text/css" href="{{  orchid_mix('/css/orchid.css','orchid') }}">
  @endif

  @stack('head')

  <meta name="turbolinks-root" content="{{  Dashboard::prefix() }}">
  <meta name="dashboard-prefix" content="{{  Dashboard::prefix() }}">

  <script src="{{ orchid_mix('/js/manifest.js','orchid') }}"></script>
  <script src="{{ orchid_mix('/js/vendor.js','orchid') }}"></script>
  <script src="{{ orchid_mix('/js/orchid.js','orchid') }}"></script>

  @foreach(Dashboard::getResource('stylesheets') as $stylesheet)
    <link rel="stylesheet" href="{{  $stylesheet }}">
  @endforeach

  @stack('stylesheets')

  @foreach(Dashboard::getResource('scripts') as $scripts)
    <script src="{{  $scripts }}" defer></script>
  @endforeach
</head>

<body>


<div class="container-fluid" data-controller="@yield('controller')" @yield('controller-data')>

  <div class="row">
    @yield('body-left')

    <div class="col min-vh-100 overflow-hidden">
      <div class="d-flex flex-column-fluid">
        <div class="container-fluid h-full px-0">
          @yield('body-right')
        </div>
      </div>
    </div>
  </div>


  @include('platform::partials.toast')
</div>

@stack('scripts')


</body>
</html>
