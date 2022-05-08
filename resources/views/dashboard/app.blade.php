<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('dashboard.includes.meta')
</head>

<body class="fixed-nav sticky-footer" id="page-top">


        @if (Auth::user()->role=='teacher')
            @include('dashboard.includes.navigation_teacher')
        @else
            @include('dashboard.includes.navigation')
        @endif
	
	  <div class="content-wrapper">
           <div class="container-fluid">

            @yield('content')	

	      </div>
	      <!-- /.container-fluid-->
   	  </div>
      <!-- /.container-wrapper-->

	
	  @include('dashboard.includes.footer')

    @stack('js')
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery.easing.min.js') }}"></script>

    <!-- Page level plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendor/dataTables.bootstrap4.js') }}"></script>

    <!-- Custom scripts for dashboard-->
    <script src="{{ asset('assets/js/admin.js') }}"></script>

    <!-- Custom scripts for all pages
    <script src="{{ asset('assets/js/custom.js') }}"></script>-->
    <script src="{{ asset('assets/js/admin-datatables.js') }}"></script>

</body>
</html>
