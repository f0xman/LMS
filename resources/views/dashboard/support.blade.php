@extends('dashboard.app')

@section('content')		

        <div class="box_general padding_bottom">

			<div class="header_box version_2">
				<h2><i class="fa fa-comment"></i>Помощь и поддержка</h2>
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

                    {{ Form::open(array('route' => array('postSupport'))) }}
                    <div class="row justify-content-center">

                                <div class="col-md-6">
							        <div class="form-group">
                                        <label>Тема</label>
								        <input name="title" type="text" class="form-control" placeholder="Тема обращения" required>
							        </div>
						        </div>
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
                    {{ Form::close() }}

                @endif
           

                {{-- Список обращений --}}


                    @if (count($support) > 0)
                                            
			            <div class="table-responsive mt-5">
                            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Тема</th>
                                  <th>Последнее сообщение</th>
                                </tr>
                              </thead>
                              <tbody>      
                                    @foreach ($support as $ticket)
                                        <tr>
                                          <td style="width: 5%">{{ $ticket->id }}</td>
                                          <td style="width: 75%">
                                               @if (isset($ticket->supportmessages->first()->unread) and $ticket->supportmessages->first()->unread==1)
                                                <a href="{{ route('showSupport', ['id'=>$ticket->id]) }}" class="font-weight-bold">{{ $ticket->title }}</a> <i class="read">есть ответ</i>
                                              @else
                                                <a href="{{ route('showSupport', ['id'=>$ticket->id]) }}">{{ $ticket->title }}</a></i>
                                              @endif
                                          </td>
                                          <td style="width: 20%">{{ $ticket->supportmessages->first()->created_at ?? $ticket->created_at }}</td>
                                        </tr>
                                    @endforeach
                           
                              </tbody>
                            </table>
                       </div>

                    @endif


	   </div>





@endsection
