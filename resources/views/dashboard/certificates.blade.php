@extends('dashboard.app')

@section('content')

@include('dashboard.includes.messages')

<div class="box_general padding_bottom">

    <div class="header_box version_2">
        <h2><i class="fa fa-gift"></i>Сертификаты</h2>                     
    </div>

    <div class="row">

        <div class="col-xl-12">
        В этом разделе ваши сертификаты к приобретенным курсам.

            <ul class="list-group mt-3">

                @if(count($seminars)>0)
                
                    @foreach ($seminars as $seminar)

                        <li class="list-group-item list-group-item-light">

                            @if(file_exists(public_path('uploads/certificates/'.Auth::user()->id.'/'.$seminar->id.'.pdf'))) 

                                <a href="{{asset('uploads/certificates/'.Auth::user()->id.'/'.$seminar->id.'.pdf')}}" target="_blank">
                                <i class="fa fa-certificate"></i> Сертификат <b>"{{ $seminar->title }}"</b> </a>

                            @else
                                Сертификат для семинара <b>"{{ $seminar->title }}"</b>

                                <form method="POST" action="{{ route('generateCertificate') }}">
                                    @csrf
                                        <div class="form-group mt-3">
                                            <!-- <label for="name">Имя</label> -->
                                            <input class="form-control  @error('name') is-invalid @enderror" name="name" value="" id="name" type="name" placeholder="ФИО для сертификата (до 35 символов)" autofocus required maxlength="35">
                                                @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                @enderror
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-secondary">
                                                {{ __('Выписать') }}
                                            </button>
                                        </div>
                                        <input type="hidden" name="seminar_id" value="{{ $seminar->id }}">
                                </form>
                            @endif

                        </li>

                        @endforeach

                @else
                    У Вас пока нет приобретенных курсов для которых доступны сертификаты.
                @endif
            </ul>
        </div>
    </div>

</div>

@endsection
