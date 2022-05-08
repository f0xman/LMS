<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('frontend.includes.meta')

</head>

<body>
	
    <div id="container">
        @include('frontend.includes.header')
	
	<main>@yield('content')</main>
	<!--/main-->
	
	    @include('frontend.includes.footer')

	</div>
	<!-- page -->

    
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/query.countTo.js') }}"></script>
    <script src="{{ asset('assets/js/popper.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    @stack('js')

</body>
</html>
