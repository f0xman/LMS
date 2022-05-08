@extends('frontend.app')

@section('content')
	<div id="error_page">
			<div class="container">
				<div class="row justify-content-center text-center">
					<div class="col-xl-7 col-lg-9">
						<h2> <i class="icon_error-triangle_alt"></i></h2>
						<p>Извините. Ошибка сервера 500.</p>
                        <p><a href="/">Вернуться на главную.</a></p>
						
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /error_page -->

@endsection
