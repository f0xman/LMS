@extends('frontend.app')

@section('content')

<div class="container">
{{ Breadcrumbs::render('teacherShow', $teacher) }}
</div>




		<!-- teachers-section 
			================================================== -->
			<section class="teachers-section">
			<div class="container">
				<div class="teachers-box">

					<div class="row">
						<div class="col-lg-6">
							<div class="profile-image">
								<div class="image-holder">
									<img src="{{ asset('uploads/'.pathinfo($teacher->image,  PATHINFO_DIRNAME).'/'.pathinfo($teacher->image, PATHINFO_FILENAME).'.'.pathinfo($teacher->image, PATHINFO_EXTENSION)) }}" alt="{{ $teacher->name }}">
								</div>
								
								<ul class="social-links">
								@if ($teacher->fb!='') <li><a href="{{ $teacher->fb }}" target="_blank"><i class="fa fa-facebook-f"></i></a></li> @endif

								@if ($teacher->vk!='') <li><a href="{{ $teacher->vk }}" target="_blank"><i class="fa fa-vk-f"></i></a></li> @endif

								@if ($teacher->insta!='') <li><a href="{{ $teacher->insta }}" target="_blank"><i class="fa fa-instagram-f"></i></a></li> @endif
								</ul>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="profile-details">
								<h1>{{ $teacher->name }}</h1>
								<p>{{ $teacher->description }}</p>
								{!! $teacher->about !!}

							</div>
						</div>
					</div>

				</div>	
			</div>
		</section>
		<!-- End teachers section -->

@endsection
