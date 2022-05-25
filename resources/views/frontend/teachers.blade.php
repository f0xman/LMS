@extends('frontend.app')

@section('content')

<div class="container">
{{ Breadcrumbs::render('teachers') }}
</div>


		<!-- teachers-section 
			================================================== -->
			<section class="teachers-section">
			<div class="container">
				<div class="teachers-box">
					<div class="row">

					@foreach ($teachers as $teacher)

					<div class="col-lg-3 col-md-6">
							<div class="teacher-post">
								<a href="{{ route('teacherShow', ['id'=>$teacher->id]) }}">
									<img src="{{ asset('uploads/'.$teacher->image) }}" alt="{{ $teacher->name }}">
									<div class="hover-post">
										<h2>{{ $teacher->name }}</h2>
										<span>{{ $teacher->description }}</span>
									</div>
								</a>
							</div>	
					</div>


					<!-- /box_grid -->

					@endforeach		

					{{ $teachers->links() }}
					
					</div>
				</div>	
			</div>
			
		</section>
		<!-- End teachers section -->


		


@endsection
