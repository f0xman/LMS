@component('mail::message')
# {{ $data['name'] }}, здравствуйте!

<p>Вы успешно приобрели семинар <b>"{{ $data['seminar_title'] }}"</b>.</p>

<p>Семинар доступен в личном кабинете <b><a href="{{ url( config('app.url').'/dashboard/seminar/'.$data['seminar_id'] ) }}">по ссылке</a></b>.</p>

Спасибо,
<br>
{{ config('app.name') }}

@component('mail::button', ['url' => config('app.url') ])
    Перейти на сайт
@endcomponent

@endcomponent
