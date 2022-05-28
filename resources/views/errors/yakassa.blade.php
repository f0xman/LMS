@extends('errors::minimal')

@section('title', __('Ошибка оплаты курса'))
@section('code', '503')
@section('message', __('Произошла ошибка при оплате курса'))

@extends('frontend.app')

@section('content')
	<div id="error_page">
			<div class="container">
				<div class="row justify-content-center text-center">
					<div class="col-xl-7 col-lg-9">
						<h2>Ошибка оплаты курса <i class="icon_error-triangle_alt"></i></h2>
						<p>Произошла ошибка при оплате курса</p>
                        <p><a href="/">Вернуться на главную.</a></p>
						
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /error_page -->

@endsection