@extends('dashboard.app')

@section('content')		

        <div class="box_general padding_bottom">


        @if(gettype($support)=='object')

			<div class="header_box version_2">
				<h2><i class="fa fa-comment"></i><a href="{{ route('support') }}">Помощь и поддержка</a>
                <i class="fa fa-long-arrow-right"></i>Тема "{{ $support->title ?? '' }}"</h2>
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

                    {{ Form::open(array('route' => array('postQuestion', $support->root_id))) }}
                    <div class="row justify-content-center"> 

                    </div>
                    <div class="row justify-content-center">

                                <div class="col-md-6">
							        <div class="form-group">
                                        <label>Сообщение</label>
								        <textarea name="message" style="height:100px;" class="form-control" placeholder="Ваше сообщение" required></textarea>
							        </div>
						        </div>
                    </div>
                    <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <button class="btn btn-secondary btn-sm" type="submit">Отправить</button>
						        </div>
                    </div>
                    {{ Form::hidden('support_id', $support->root_id) }}
                    {{ Form::close() }}

                @endif


                <div class="list_general mt-4">
                    @if (count($support) > 0)
                                            
				                <ul>
                                    @foreach ($support as $ticket)
                                        @if ($ticket->admin==1)
                                            <li class="support">
                                            <span>{{ $ticket->created_at }}</span>
                                            <figure><img src="{{ asset('assets/img/support.png') }}" alt=""></figure>
						                    <h4>Служба поддержки</h4>
                                        @else
                                            <li>
                                            <span>{{ $ticket->created_at }}</span>
                                            <figure><img src="{{ asset('assets/img/user.png') }}" alt=""></figure>
						                    <h4>Вы</h4>                                           
                                        @endif   
                                            <p>{{ $ticket->message }}</p>
					                        </li>
                                    @endforeach
                                </ul>
                        
                    @endif
                </div>

       @elseif(gettype($support)=='string')
            {{  $support }}
       @endif


	   </div>





@endsection
