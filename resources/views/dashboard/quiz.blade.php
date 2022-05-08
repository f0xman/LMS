@extends('dashboard.app')

@section('content')

@include('dashboard.includes.messages')

    <div class="box_general padding_bottom">

    <div class="header_box version_2">
        <h2><i class="fa fa-play-circle"></i>
        <a href="{{ route('showVideo', ['id'=>$quiz->video->id]) }}"> {{ $quiz->video->title ?? 'Ошибка' }} </a>
        <i class="fa fa-long-arrow-right"></i>{{ $quiz->title ?? 'Ошибка' }}</h2>
    </div>

    @if(!empty($questions))

    <form method="POST" action="{{ route('postAnswers') }}">
        @csrf
        <ol>
            @foreach ($questions as $question)
                <li class="pb-3">
                <p>{{ $question['question'] }}</p> 
                    @foreach ($question['answers'] as $answer)
                        <input type="radio" name="answers[{{ $question['id'] }}]" value="{{ $answer }}" required> {{ $answer }}<br>  
                    @endforeach
                </li>
            @endforeach
        </ol>
        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
        <input type="hidden" name="order_id" value="{{ $question['order_id'] }}">
        <button class="btn btn-secondary btn-sm" type="submit">Отправить ответы</button>
    </form>

    @endif

    </div>

@endsection

