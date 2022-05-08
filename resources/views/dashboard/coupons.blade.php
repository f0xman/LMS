@extends('dashboard.app')

@section('content')		

       <div class="box_general padding_bottom">

			    <div class="header_box version_2">
				    <h2><i class="fa fa-gift"></i>Купоны</h2>                     
			    </div>
      
                <div class="row">

                    <div class="col-xl-12">
                    В этом разделе скидочные купоны на покупку курсов на нашем ресурсе. Купон может быть использован один раз. Вы можете использовать его или отдать знакомому.

                        <ul class="list-group mt-3"> 
                           @if(count($coupons)>0)          
                                @foreach ($coupons as $coupon)

                                    @if($coupon->used==0)
                                        <li class="list-group-item list-group-item-success">Купон на скидку {{$coupon->percent}}%  - <span id="{{$coupon->id}}"><b>{{$coupon->code}}</b></span><button class="btn btn-info btn-sm ml-3" onclick="copyToClipboard('#{{$coupon->id}}')">Скопировать</button> </li>
                                    @else
                                        <li class="list-group-item list-group-item-light">Купон на скидку {{$coupon->percent}}%  - <b>{{$coupon->code}}</b> (уже использован)</li>
                                    @endif

                                 @endforeach
                           @else
                                У Вас пока нет купонов.
                           @endif
                       </ul>
                    </div>
                </div>



	   </div>

@endsection

