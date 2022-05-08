@extends('frontend.app')

@section('content')

<div class="container">
{{ Breadcrumbs::render('courseShow', $course) }}
</div>
		<!-- page-banner-section 
			================================================== -->
         <section class="page-banner-section">
			<div class="container">
				<h1>{{ $course->title }}</h1>
			</div>
		</section>
		<!-- End page-banner-section -->

		<!-- single-course-section 
			================================================== -->
		<section class="single-course-section">
			<div class="container">
				<div class="row">
					<div class="col-lg-6">

						<div class="single-course-box">

							<!-- single top part -->
							<div class="product-single-top-part">
								<div class="product-info-before-gallery">
									<div class="course-rating before-gallery-unit">
										<div class="star-rating has-ratings">
                                            <i class="fa fa-calendar text-muted"></i>
                                            <span class="votes-number">Дата проведения: </span>
											<span class="rating">{{ $course->start }}</span>
                                            -
                                            <span class="rating">{{ $course->end }}</span>
										</div>
									</div>
								</div>

							</div>

							<!-- single course content -->
							<div class="single-course-content">
								<h2>Описание курса</h2>
								<p>{!! $course->about !!}</p>
							</div>
							<!-- end single course content -->
						</div>

					</div>

					<div class="col-lg-6">
						<div class="sidebar">
							<div class="widget course-widget">
								
                            	<!-- course section -->
								<div class="course-section">
									<h2> <i class="fa fa-graduation-cap"></i> Семинары курса</h2>
									<div class="panel-group">
                                        @foreach ($seminars as $seminar)
                                            <div class="course-panel-heading">
                                                <div class="panel-heading-left">
                                                    <!--<div class="course-lesson-icon">
                                                        <i class="fa fa-play-circle-o"></i>
                                                    </div>-->
                                                    <div class="title">										
									
  														<a data-toggle="collapse" href="#collapseExample-{{ $seminar->id }}" class="text-dark">
                                                        	<h4>{{ $seminar->title }}</h4>
														</a>
														<p>Стоимость: <span class="text-dark h6">{{ $seminar->price }} рублей</span></p>
                                                        <p>Дата: {{ $seminar->date }}</p>
                                                        <p>Преподаватель: <a href="{{ route('teacherShow', ['id'=>$seminar->teacher->id]) }}" target="_blank">{{ $seminar->teacher->name }}</a> </p>

                                                    </div>
                                                </div>
                                                <div class="panel-heading-right">

												@if (in_array($seminar->id, $purchased))

													<a href="{{ route('dashboard') }}" class="preview-button-inactive"><i class="fa fa-arrow-right"></i> Куплен</a>
												
												@elseif	(!$isFinished)

													{{ Form::open(array('route' => 'order')) }}															
													<button class="btn btn_1 video-lesson-preview preview-button" type="submit"><i class="fa fa-shopping-cart"></i>Купить</button>
													{{ Form::hidden('seminar_id', $seminar->id) }}
													{{ Form::close() }}  

												@endif
                                                 
                                                </div>
                                            </div>

											<div class="collapse" id="collapseExample-{{ $seminar->id }}">
												<div class="card card-body panel-content-inner">
												{!! $seminar->about !!}
												</div>
											</div>

                                            <!--<div class="panel-content">
                                                <div class="panel-content-inner">
                                                {!! $seminar->about !!}
                                                </div>
                                            </div>-->

                                        @endforeach
									</div>
								</div>
								<!-- end course section -->

							</div>

						</div>
					</div>

				</div>
						
			</div>
		</section>
		<!-- End single-course section -->

@endsection
