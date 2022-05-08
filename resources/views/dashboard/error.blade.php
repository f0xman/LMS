@extends('dashboard.app')

@section('content')		

       <div class="box_general padding_bottom">

			    <div class="header_box version_2">
				    <h2><i class="fa fa-play-circle"></i>Ошибка</h2>
			    </div>

          <div class="row">
            @if(isset($error))
              {{ $error }}      
            @endif 
          </div>
          
@endsection
