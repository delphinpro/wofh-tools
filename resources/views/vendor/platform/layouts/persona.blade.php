<a href="{{ $url }}">
    <div class="persona d-sm-flex flex-row text-center text-sm-left align-items-center">
        @empty(!$image)
            <span class="thumb-sm avatar mr-sm-3 mr-md-0 mr-xl-3 d-none d-md-inline-block">
              <img src="{{ $image }}" class="bg-light" alt="">
            </span>
        @endempty
        <div class="mt-2 mt-sm-0 mt-md-2 mt-xl-0">
            <p class="mb-0">{{ $title }}</p>
            <small class="text-xs text-muted">{{ $subTitle }}</small>
        </div>
    </div>
</a>
