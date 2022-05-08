@component('mail::message')
# {{ $data['name'] }}, здравствуйте!

<p>Вы успешно приобрели курс <b>"{{ $data['course_title'] }}"</b>.</p>

<p>Курс всегда доступен в личном кабинете <b><a href="{{ url( config('app.url').'/dashboard/course/'.$data['course_id'] ) }}">по ссылке</a></b>.</p>

Спасибо,
<br>
{{ config('app.name') }}

@component('mail::button', ['url' => config('app.url') ])
    Перейти на сайт
@endcomponent

@endcomponent
