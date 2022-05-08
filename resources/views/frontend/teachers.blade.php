@extends('frontend.app')

@section('content')

@include('frontend.includes.sub_tiny')


		<div class="container margin_60_35">
			<div class="row">

            @foreach ($teachers as $teacher)

            	<div class="col-xl-4 col-lg-6 col-md-6">
					<div class="box_grid wow">
						<figure class="block-reveal">
							<div class="block-horizzontal"></div>
							<a href="{{ route('teacherShow', ['id'=>$teacher->id]) }}"><img src="{{ asset('uploads/'.$teacher->image) }}" class="img-fluid" alt=""></a>
                            <div class="preview"><span>Профайл преподавателя</span></div>
                        </figure>
						<div class="wrapper">
							<a href="{{ route('teacherShow', ['id'=>$teacher->id]) }}"><h3>{{ $teacher->name }}</h3></a>
							<p>{{ $teacher->description }}</p>
							
						</div>
						<ul>
							<li></li>
							<li><a href="{{ route('courseTeacher', ['id'=>$teacher->id]) }}">Все курсы </a></li> 
						</ul>
					</div>
				</div>
				<!-- /box_grid -->

            @endforeach

			</div>
			<!-- /row -->

			{{ $teachers->links() }}
		</div>
		<!-- /container -->


@endsection
