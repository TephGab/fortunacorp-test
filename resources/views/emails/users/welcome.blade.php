@component('mail::message')
# Byenvini

Byenvini nan <strong>{{ config('app.name') }}</strong> {{ $user->first_name }}!<br>
Nou kontan genyenw pami nou.<br>

# Bienvenido 

Bienvenido a <strong>{{ config('app.name') }}</strong> {{ $user->first_name }}!<br>
Estamos encantados de tenerle entre nosotros.

@component('mail::button', ['url' => 'https://fortuna-corp.com'])
Tcheke platfom nan kunya
@endcomponent

<!-- Thanks,<br> -->
{{ config('app.name') }}
@endcomponent
