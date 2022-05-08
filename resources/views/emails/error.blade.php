@component('mail::message')
# Никита, здравствуй!

Вот неприятная ошибка из <b>{{ $data['from'] }}</b>

<p>"{{ $data['error'] }}"</p>

Хорошего дня,
<br>
{{ config('app.name') }}

@component('mail::button', ['url' => config('app.url') ])
    Перейти на сайт
@endcomponent

@endcomponent
