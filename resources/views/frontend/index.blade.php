@extends('frontend.app')

@section('content')

		<!-- feature-section 
		================================================== -->
		<section class="feature-section">
			<div class="container">
				<div class="feature-box">
					<div class="row">
						<div class="col-lg-4 col-md-6">
							<div class="feature-post">
								<div class="icon-holder">
									<i class="fa fa-umbrella"></i>
								</div>
								<div class="feature-content">
									<h2>
										Online Learn Courses Management
									</h2>
									<p>Analyzing negative materials about your brand and addressing them with sentiment analysis and press.</p>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6">
							<div class="feature-post">
								<div class="icon-holder color2">
									<i class="fa fa-id-card-o"></i>
								</div>
								<div class="feature-content">
									<h2>
										Learn from the masters of the field online
									</h2>
									<p>Analyzing negative materials about your brand and addressing them with sentiment analysis and press.</p>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6">
							<div class="feature-post">
								<div class="icon-holder color3">
									<i class="fa fa-handshake-o"></i>
								</div>
								<div class="feature-content">
									<h2>
										An Introduction-Skills For Learners
									</h2>
									<p>Analyzing negative materials about your brand and addressing them with sentiment analysis and press.</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End feature section -->


		
		<!-- popular-courses-section 
		================================================== -->
		<section class="popular-courses-section">
			<div class="container">
				<div class="title-section">
					<div class="left-part">
						<h1>Популярные курсы</h1>
					</div>
					<div class="right-part">
						<a class="button-one" href="/courses/">Все курсы</a>
					</div>
				</div>
				<div class="popular-courses-box">
					<div class="row">
						@foreach ($courses as $course)
							<div class="col-lg-3 col-md-6">
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
											<!-- <div class="course-price">
												<span>£244</span> 
											</div>-->
										</div>
									</div>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</section>
		<!-- End popular-courses section -->



		<!-- teachers-section 
		================================================== -->
		<section class="teachers-section">
			<div class="container">
			<div class="title-section">
					<div class="left-part">
						<h1>Преподаватели</h1>
					</div>
					<div class="right-part">
						<a class="button-one" href="#">View All Courses</a>
					</div>
				</div>

				<div class="teachers-box">
					<div class="row">
						<div class="col-lg-3 col-md-6">
							<div class="teacher-post">
								<a href="single-teacher.html">
									<img src="upload/teachers/teacher6.jpg" alt="">
									<div class="hover-post">
										<h2>Michael Arnet</h2>
										<span>Photographer</span>
									</div>
								</a>
							</div>
						</div>
						<div class="col-lg-3 col-md-6">
							<div class="teacher-post">
								<a href="single-teacher.html">
									<img src="upload/teachers/teacher4.jpg" alt="">
									<div class="hover-post">
										<h2>Leslie Williams</h2>
										<span>Math</span>
									</div>
								</a>
							</div>
						</div>
						<div class="col-lg-3 col-md-6">
							<div class="teacher-post">
								<a href="single-teacher.html">
									<img src="upload/teachers/teacher7.jpg" alt="">
									<div class="hover-post">
										<h2>John Maddix</h2>
										<span>Chemical Engineering</span>
									</div>
								</a>
							</div>
						</div>
						<div class="col-lg-3 col-md-6">
							<div class="teacher-post">
								<a href="single-teacher.html">
									<img src="upload/teachers/teacher5.jpg" alt="">
									<div class="hover-post">
										<h2>Linda Castello</h2>
										<span>Teacher Training</span>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>	
			</div>
		</section>
		<!-- End teachers section -->

@endsection
