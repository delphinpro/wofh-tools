@guest
  <p>Crafted with <span class="mr-1">❤️</span> by Alexandr Chernyaev</p>
@else
  <div class="text-left">
    <p class="small m-0">
      Env: {{ config('app.env') }} |
      Debug: {{ config('app.debug') ? 'On' : 'Off' }}
    </p>
    <p class="small m-0">PHP: {{ PHP_VERSION }}</p>
    <p class="small m-0">
      {{ __('Powered by') }}
      <a href="http://orchid.software" target="_blank" rel="noopener">
        Orchid Platform v{{\Orchid\Platform\Dashboard::VERSION}}
      </a>
    </p>
  </div>
@endguest
