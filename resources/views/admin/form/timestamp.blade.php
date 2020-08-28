<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div class="input-group">

            @if ($prepend)
                <span class="input-group-addon">{!! $prepend !!}</span>
            @endif

{{--            <input {!! $attributes !!} />--}}
            <input style="width: 160px"
                type="text"
                id="{{ $id }}"
                name="{{ $name }}"
                value="{{ $value ? date('Y-m-d\TH:i:s', $value) : '' }}"
                class="form-control {{ $class }}"
                placeholder="{{ $placeholder }}"
            >

            @if ($append)
                <span class="input-group-addon clearfix">{!! $append !!}</span>
            @endif

        </div>

        @include('admin::form.help-block')

    </div>
</div>
