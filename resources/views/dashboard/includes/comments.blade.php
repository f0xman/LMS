        <div class="box_general padding_bottom">

			<div class="header_box version_2">
				<h2><i class="fa fa-comment"></i>Комментарии</h2>
			</div>


                @if ($errors->any())
                    <div class="row justify-content-center">
                            <div class="col-md-5 alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach
                            </div>
                    </div>
                @endif


                @if(session()->get('success'))
                  <div class="row justify-content-center">
                    <div class="col-md-5 alert alert-success">
                      {{ session()->get('success') }}  
                    </div>
                    </div>
                @else

                    {{ Form::open(array('route' => array('postComment', $video->id))) }}
                    <div class="row justify-content-center">

                                <div class="col-md-6">
							        <div class="form-group">
								        <textarea name="comment" style="height:100px;" class="form-control" placeholder="Ваш комментарий или вопрос" required></textarea>
							        </div>
						        </div>
                    </div>
                    <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <button class="btn btn-secondary btn-sm" type="submit">Отправить</button>
						        </div>
                    </div>
                    {{ Form::hidden('video_id', $video->id) }}
                    {{ Form::close() }}

                @endif


                <div class="list_general mt-4">
                    @if (count($video->comments) > 0)
                        
				                <ul>
                                    @foreach ($video->comments as $comment)
                                        <li>
						                    <span>{{ $comment->created_at->format('d/m/Y') }}</span>
						                    <figure><img src="{{ asset('assets/img/user.png') }}" alt=""></figure>
						                    <h4>{{ $comment->user->name ?? 'User-'.$comment->user_id  }}</h4>
						                    <p>{{ $comment->comment }}</p>
					                    </li>
                                    @endforeach
                                </ul>
                        
                    @else
                        Комменатариев пока нет.
                    @endif
                </div>

	   </div>


