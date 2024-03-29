@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
        {{-- <img class="email-header-logo" src="{{ config('app.url') . \Session::get('email_logo_src') }}"> --}}
        <img class="email-header-logo" src="{{ config('app.url') }}/images/emails/TP-flat-white.png">
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    {{-- @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }}
            @if(auth() -> user() && stristr(auth() -> user() -> group, 'agent'))
                {{ \Session::get('user_details') -> company }}
                @else
                Taylor Properties
            @endif
            Taylor Properties
            @lang('All rights reserved.')
        @endcomponent
    @endslot --}}
@endcomponent
