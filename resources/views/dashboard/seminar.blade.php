@extends('dashboard.app')

@section('content')		

@include('dashboard.includes.messages')

       <div class="box_general padding_bottom">

			    <div class="header_box version_2">
				    <h2><i class="fa fa-play-circle"></i> {{ $seminar->title ?? 'Семинар' }}</h2>
			    </div>

          <p><a href="{{ route('courseShow', ['slug'=>$seminar->course->slug]) }}" target="_blank">Курс "{{ $seminar->course->title }}"</a></p>

          <div class="row">

              <div class="x_content">

                <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">

                @if(!empty($unAvailableVideos) or !empty($availableVideos))
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Видео</a>
                  </li>
                @endif

                @if(!empty($availableFiles) or !empty($unAvailableFiles))
                  <li class="nav-item">
                    <a class="nav-link" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">Файлы</a>
                  </li>
                @endif

                </ul>


                <div class="tab-content pl-2 pt-3" id="myTabContent">

                  
                  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                    @if(!empty($availableVideos)) 
                      <h3>Для вас доступны</h3>
                      @foreach ($availableVideos as $video)
                        <p><a href="{{ route('showVideo', ['id'=>$video->id]) }}"><i class="fa fa-video-camera"></i> {{$video->title}}</a></p>
                      @endforeach
                    @endif
                    

                    @if(!empty($unAvailableVideos)) 
                      <h3>Будут доступны позже</h3>
                      @foreach ($unAvailableVideos as $video)
                        <p><i class="fa fa-video-camera"></i> {{$video->title}}</p>
                      @endforeach
                    @endif

                  </div>


                  @if(!empty($availableFiles) or !empty($unAvailableFiles))
                    <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">

                      @if(!empty($availableFiles)) 
                        <h3>Для вас доступны</h3>
                        @foreach ($availableFiles as $file)
                          <p><a href="{{ asset('uploads/'.$file->file) }}" target="_blank"><i class="fa fa-file"></i> {{$file->title}}</a></p>
                        @endforeach
                      @endif
                      

                      @if(!empty($unAvailableFiles)) 
                        <h3>Будут доступны позже</h3>
                        @foreach ($unAvailableFiles as $file)
                          <p><i class="fa fa-video-camera"></i> {{$file->title}}</p>
                        @endforeach
                      @endif

                    </div>
                  @endif

                </div>

              </div>

          </div>

	    </div>

      @if(isset($isReviewExist))

        <div class="box_general padding_bottom">

          <div class="header_box version_2">
            <h2><i class="fa fa-star"></i>Ваш отзыв</h2>                     
          </div>

          <p>Мы будем благодарны Вам за отзыв о семинаре</p>

              {{ Form::open(array('route' => array('postReview', $seminar->id))) }}
                <div class="row justify-content-center">
                  <div class="col-md-6">
                    <div class="form-group rating">
                      <input type="radio" name="rating" value="5" id="5" required><label for="5">☆</label>
                                      <input type="radio" name="rating" value="4" id="4"><label for="4">☆</label>
                                      <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label>
                                      <input type="radio" name="rating" value="2" id="2"><label for="2">☆</label>
                                      <input type="radio" name="rating" value="1" id="1"><label for="1">☆</label>
                    </div>
                </div>
              </div>

              <div class="row justify-content-center">
                <div class="col-md-6">
                  <div class="form-group">
                    <textarea name="review" style="height:100px;" class="form-control" placeholder="Ваш отзыв" required></textarea>
                  </div>
                </div>
              </div>

              <div class="row justify-content-center">
                <div class="col-md-6">
                                <button class="btn btn-secondary btn-sm" type="submit">{{ __('Send') }}</button>
                </div>
              </div>
              {{ Form::hidden('seminar_id', $seminar->id) }}
              {{ Form::close() }}

        </div>

      @endif
  

@endsection
