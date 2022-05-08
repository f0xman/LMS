@extends('dashboard.app')

@section('content')

    <div class="box_general padding_bottom">

        <div class="header_box version_2">
            <h2><i class="fa fa-play-circle"></i>
            <a href="{{ route('showSeminar', ['id'=>$video->seminar_id]) }}">{{{ $seminar->title ?? '' }}}</a>
            <i class="fa fa-long-arrow-right"></i>{{ $video->title ?? 'Ошибка' }}</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-8">
                @if(Auth::user()->player_off==0)
                    <div id="player" data-plyr-provider="youtube" data-plyr-embed-id="{!! $video->video !!}"></div>
                @else
                    <div class="video-container">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/{!! $video->video !!}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                @endif
            </div>
        </div>

        @if(!empty($quizzes))

            <div class="header_box version_2 pt-3">
                <h2><i class="fa fa-question-circle"></i>Проверка знаний</h2>
            </div>

            @foreach ($quizzes as $quiz)
                <p>
                    <a href="{{ route('showQuiz', ['id'=>$quiz->id]) }}"><i class="fa fa-arrow-right"></i> {{$quiz->title}}</a> 
                    @if(!empty($quiz->quiz_passed))
                        <small class="text-success">тест пройден!</small>
                    @endif
                </p>
            @endforeach

        @endif

    </div>

    <style type="text/css">
        .plyr__video-embed iframe {
            top: -50%;
            height: 200%;
        }
    </style>

    @include('dashboard.includes.comments')

@endsection

@push('css')
    <link href="{{ asset('assets/vendor/plyr/plyr.css') }}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ asset('assets/vendor/plyr/plyr.js') }}"></script>
    <script>
        const player = new Plyr('#player');
    </script>
@endpush
