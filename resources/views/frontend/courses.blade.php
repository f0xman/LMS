@extends('frontend.app')

@section('content')

<div class="container">
{{ Breadcrumbs::render('courses') }}
</div>

        <!-- courses-section 
        ================================================== -->

        <section class="courses-section">
			<div class="container">
				<div class="row">

					<ul class="nav mb-2">
						@if ($archived)
							<li class="nav-item">				
								<a class="nav-link active" aria-current="page" href="/courses">Актуальные курсы</a>
							</li>
							<li class="nav-item">
							<a class="nav-link disabled font-weight-bold" href="#" tabindex="-1" aria-disabled="true">Завершенные курсы</a>
							</li>
						@else
							<li class="nav-item">
								<a class="nav-link disabled font-weight-bold" href="#" tabindex="-1" aria-disabled="true">Актуальные курсы</a>
							</li>
							<li class="nav-item">
								<a class="nav-link active" aria-current="page" href="/courses/archived">Завершенные курсы</a>
							</li>
						@endif
					</ul>

					<div class="col-lg-12">

						<div class="row">

						@if (count($courses)>0) 
							@foreach ($courses as $course)

							<div class="col-lg-4 col-md-6 col-sm-6">
								<div class="course-post">
									<div class="course-thumbnail-holder">
										<a href="{{ route('courseShow', ['slug'=>$course->slug]) }}">
											<img src="{{ asset('uploads/'.$course->image) }}" alt="{{ $course->title }}">
										</a>
									</div>
									<div class="course-content-holder">
										<div class="course-content-main">
											<h2 class="course-title">
											<a href="{{ route('courseShow', ['slug'=>$course->slug]) }}">{{ $course->title }}</a>
											</h2>
											<div class="course-rating-teacher">
												<?php
													$start = date('d.m.Y', strtotime($course->start));
													$end = date('d.m.Y', strtotime($course->end));
												?>
												<i class="material-icons">perm_contact_calendar</i> 
												<span>{{ $start }} - {{ $end }}</span>
											</div>
										</div>
										<div class="course-content-bottom">
											<div class="course-students">
												<i class="material-icons">school</i>
												<span>
												@if (count($course->seminars) > 0)
														{{ count($course->seminars) }}
														{{trans_choice('plural.seminars', count($course->seminars))}}
												@endif
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>

							@endforeach

							{{ $courses->links() }}

						@else
						<div class="col-12 d-flex justify-content-center">Нет курсов</div>
						@endif


                        </div>
					</div>


				</div>
						
			</div>
		</section>
		<!-- End courses section -->

@endsection
