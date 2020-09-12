<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
  <link rel="manifest" href="/favicon/site.webmanifest">
  <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="apple-mobile-web-app-title" content="WofhTools">
  <meta name="application-name" content="WofhTools">
  <meta name="msapplication-TileColor" content="#080927">
  <meta name="theme-color" content="#ffffff">

  <meta property="og:image" content="/favicon/og-image.jpg">
  <meta property="og:image:height" content="128">
  <meta property="og:image:width" content="85">
  <meta property="og:title" content="WofhTools">
  <meta property="og:url" content="http://wofh-tools.ru">
  <meta property="og:description"
    content="Сайт пользовательских дополнений и инструментов для онлайн игры waysofhistory.com">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <style>.fa-icon{height:1.1em;width:1.1em;fill: currentColor;}</style>
  <link href="{{ asset('assets/main.css') }}" rel="stylesheet">
</head>
<body>

{!! ssr('assets/entry-server.js')
            ->context('state', $state)
            ->fallback('<div id="app"></div>')
            ->render() !!}

<script>window.__STATE__ = @json($state, JSON_UNESCAPED_UNICODE);</script>
<script src="{{ asset('assets/manifest.js') }}"></script>
<script src="{{ asset('assets/vendor.js') }}"></script>
<script src="{{ asset('assets/entry-client.js') }}"></script>

</body>
</html>
