@extends('frontend.app')

@section('content')

@include('frontend.includes.sub_tiny')

<div class="container">
{{ Breadcrumbs::render('teacherShow', $teacher) }}
</div>

		<div class="container margin_60_35">
			<div class="row">
				<aside class="col-lg-3" id="sidebar">
					<div class="profile">
						<figure><img src="{{ asset('uploads/'.pathinfo($teacher->image,  PATHINFO_DIRNAME).'/'.pathinfo($teacher->image, PATHINFO_FILENAME).'-small.'.pathinfo($teacher->image, PATHINFO_EXTENSION)) }}" alt="{{ $teacher->name }}" class="rounded-circle"></figure>
						<ul class="social_teacher">

							@if ($teacher->fb!='') <li><a href="{{ $teacher->fb }}" target="_blank"><i class="icon-facebook"></i></a></li> @endif

							@if ($teacher->vk!='') <li><a href="{{ $teacher->vk }}" target="_blank"><i class="icon-vkontakte"></i></a></li> @endif

							@if ($teacher->insta!='') <li><a href="{{ $teacher->insta }}" target="_blank"><i class="icon-instagramm"></i></a></li> @endif

						</ul>
						<ul>
							<li>Курсов <span class="float-right">{{ count($teacher->courses) }}</span></li>
						</ul>
					</div>
				</aside>
				<!--/aside -->

				<div class="col-lg-9">
					<div class="box_teacher">
						<div class="indent_title_in">
							<i class="pe-7s-user"></i>
							<h3>{{ $teacher->name }}</h3>
							<p>{{ $teacher->description }}</p>
						</div>
						<div class="wrapper_indent">
							{!! $teacher->about !!}
						</div>
						<!--wrapper_indent -->

						<hr class="styled_2">
						<div class="indent_title_in">
							<i class="pe-7s-display1"></i>
							<h3>Мои курсы</h3>
						</div>

                        @if (count($teacher->courses) > 0)

                            <div class="wrapper_indent">
							        <table class="table table-responsive table-striped add_bottom_30">
									    <tbody>
                                            @foreach ($teacher->courses as $course)

                                                 @if ($course->off==0)
                                                     <tr>
											            <td><i class="arrow_triangle-right"></i>

                                                           @if($course->url != "")
                                                                <a href="{{ $course->url }}" target="_blank">{{ $course->title }}</a>
                                                            @else
                                                                <a href="{{ route('courseShow', ['slug'=>$course->slug]) }}">{{ $course->title }}</a>
                                                            @endif

                                                        </td>
											         </tr>
                                                 @endif

										    @endforeach
									    </tbody>
								    </table>
						    </div>
						    <!--wrapper_indent -->

                        @endif



					</div>
				</div>
				<!-- /col -->
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->

@endsection
