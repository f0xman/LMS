@extends('dashboard.app')

@section('content')

@include('dashboard.includes.messages')		

           <div class="box_general padding_bottom">

			    <div class="header_box version_2">
				    <h2><i class="fa fa-play-circle"></i>Покупка курса</h2>
			    </div>

                <div class="row">                

                    @if($result=='success')
                        <p>Спасибо за приобретение курса.</p>
                        &nbsp; Курс всегда доступен по &nbsp; <b><a href="{{ route('showCourse', ['id'=>$course_id]) }}">ссылке</a></b>

                    @else
                        Оплата за курс не прошла.
                    @endif

                </div>

		    </div>
 

@endsection
