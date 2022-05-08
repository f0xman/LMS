       @if (count($seminarsNotAvailable)>0) 
           <div class="box_general padding_bottom">

			    <div class="header_box version_2">
				    <h2><i class="fa fa-play-circle"></i>Неоплаченные семинары</h2>
			    </div>

                <div class="row">

                @foreach ($seminarsNotAvailable as $order)
				    <div class="col-xl-4 col-lg-6 col-md-6">
					    <div class="box_grid wow">
						    <figure class="block-reveal">
							    <div class="block-horizzontal"></div>
							    <a href="{{ route('courseShow', ['slug'=>$order->seminar->slug]) }}" target="_blank"><img src="{{ asset('uploads/'.$order->seminar->image) }}" class="img-fluid" alt="{{ $order->seminar->title }}"></a>	

                                    <div class="price">{{ $order->seminar->price }}  рублей</div>                     

						    </figure>
						    <div class="wrapper">
							    <a href="{{ route('courseShow', ['slug'=>$order->seminar->slug]) }}" target="_blank"><h3>{{ $order->seminar->title }}</h3></a>
						    </div>
						    <ul>
							    <li>
                                <form method="POST" action="{{ route('delete') }}" id="delete">
                                    @csrf
                                    <a href="#nogo" onclick="if(confirm('Удалить?')) document.getElementById('delete').submit()"><i class="fa fa-trash-o"></i> удалить</a>
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                </form>
                                </li>
							    <li>
                                <form method="POST" action="{{ route('order') }}" id="buy_{{ $order->id }}">
                                    @csrf
                                    <a href="#nogo" onclick="document.getElementById('buy_{{ $order->id }}').submit()">
                                    Оплатить
                                    </a>
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <input type="hidden" name="seminar_id" value="{{ $order->seminar_id }}">
                                </form>
                                </li>
						    </ul>
					    </div>
				    </div>
				    <!-- /box_grid -->
                @endforeach
                

                </div>

		    </div>
       @endif

           <div class="box_general padding_bottom">

			    <div class="header_box version_2">
				    <h2><i class="fa fa-play-circle"></i>Доступные семинары</h2>
			    </div>

                <div class="row">

       @if (count($seminarsAvailable)>0)

                @foreach ($seminarsAvailable as $order) 

				<?php 
					$until = date("d.m.Y", strtotime($order->open_to)); 
					$isAccesExpire = (strtotime(now()) > strtotime($until)) ? true : false; //Срок доступа истек	
					$availability = ($isAccesExpire) ? 'Доступ к семинару истек '.$until : 'Доступен до: '.$until ;						
				?>

				    <div class="col-xl-4 col-lg-6 col-md-6">
					    <div class="box_grid"> 

						@if ($isAccesExpire)

						<figure class="block-reveal">
							    <div class="block-horizzontal"></div>
									<img src="{{ asset('uploads/'.$order->seminar->image) }}" class="img-fluid" alt="{{ $order->seminar->title }}">
						    </figure>
						    <div class="wrapper">
							    <h3>{{ $order->seminar->title }}</h3>
								{{ $availability }}
						    </div>							
						    <ul>
							    <li></li>
							    <li>
								<form method="POST" action="{{ route('order') }}" id="buy_{{ $order->id }}">
                                    @csrf
                                    <a href="#nogo" onclick="document.getElementById('buy_{{ $order->id }}').submit()">
                                    Продлить на месяц за 500 рублей
                                    </a>
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <input type="hidden" name="seminar_id" value="{{ $order->seminar_id }}">
                                </form>
								</li>
						    </ul>

						@else

						<figure class="block-reveal">
							    <div class="block-horizzontal"></div>
							    <a href="{{ route('showSeminar', ['id' => $order->seminar->id]) }}">
									<img src="{{ asset('uploads/'.$order->seminar->image) }}" class="img-fluid" alt="{{ $order->seminar->title }}">
								</a>	
						    </figure>
						    <div class="wrapper">
							    <a href="{{ route('showSeminar', ['id' => $order->seminar->id]) }}"><h3>{{ $order->seminar->title }}</h3></a>
								{{ $availability }}
						    </div>
							
						    <ul>
							    <li></li>
							    <li><a href="{{ route('showSeminar', ['id' => $order->seminar->id]) }}">Перейти к семинару</a></li>
						    </ul>
							
						@endif

					    </div>
				    </div>
				    <!-- /box_grid -->
                @endforeach
       @else

            У вас пока нет приобретенных семинаров. Выбрать семинар можно &nbsp; <a href="{{ route('courses') }}">здесь</a>.
       
       @endif

                </div>

		    </div>
