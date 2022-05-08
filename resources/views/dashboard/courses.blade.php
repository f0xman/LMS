@extends('dashboard.app')

@section('content')		

<div class="box_general padding_bottom">

	<div class="header_box version_2">
		<h2><i class="fa fa-comment"></i>Все мои курсы</h2>
	</div>

    <div class="row">


        @if (Auth::user()->role=='teacher')

            @if (count($courses)>0)

                <div class="accordion">
                    @foreach ($courses as $course)
							  <div class="card">
                                <div class="card-header" id="headingOne">
                                  <h2 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#{{ $course->id }}" aria-expanded="true" aria-controls="collapseOne">
                                      {{ $course->title }}
                                    </button>
                                  </h2>
                                </div>

                                <div id="{{ $course->id }}" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExample">
                                  <div class="card-body">

                                  Уроки:
                                  @if(!empty($course->lessons))
                                      <ul>
                                            @foreach ($course->lessons as $lesson)
                                                <li>
                                                <a href="#nogo" data-toggle="modal" data-target="#exampleModal" data-href="/dashboard/teacherlesson/{{$lesson->id}}" class="showLesson">
                                                <i class="fa fa-video-camera"></i> {{$lesson->title}}</a>
                                                </li>
                                            @endforeach
                                      </ul>
                                  @endif

                                  </div>
                                </div>
                              </div>                   
                    @endforeach
                </div>
				<!-- /accordion -->

            @else if
                У вас нет курсов.
            @endif


        @else
            Этот раздел вам не доступен
        @endif


    </div>

</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Урок курса</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>

@endsection
