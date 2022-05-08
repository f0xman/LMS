@component('mail::message')
# {{ $data['name'] }}, здравствуйте!

Вы успешно зарегистрировались на нашем сайте. Вот данные для входа в личный кабинет сайта:


@component('mail::panel')
    Логин : {{ $data['email'] }}
    <br>
    Пароль :  {{ $data['password'] }}
@endcomponent

Спасибо,<br>
{{ config('app.name') }}

@component('mail::button', ['url' => config('app.url') ])
    Перейти на сайт
@endcomponent

@endcomponent
