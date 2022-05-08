@extends('dashboard.app')

@section('content')		

<div class="box_general padding_bottom">

	<div class="header_box version_2">
		<h2><i class="fa fa-comment"></i>Комментарии и вопросы</h2>
	</div>

    <div class="row">


        @if (Auth::user()->role=='teacher')
            Здесь буду комментарии и вопросы к курсам
        @else
            Этот раздел вам не доступен
        @endif


    </div>

</div>


@endsection
