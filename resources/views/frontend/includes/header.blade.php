		<!-- Header
		    ================================================== -->
			<header class="clearfix">


<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<div class="container">

		<a class="navbar-brand" href="/">
			<img src="/assets/images/logo.svg" alt="">
		</a>

		<a href="#" class="mobile-nav-toggle"> 
			<span></span>
		</a>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">

				<!--  <li class="drop-link"><a class="active" href="/">Главная</a></li> -->
				<li><a href="/courses/">Курсы</a></li>
				<li><a href="/teachers/">Преподаватели</a></li>
				<li><a href="/about/">О нас</a></li>
				<li><a href="/contacts/">Контакты</a></li>

			</ul>

			@auth
				<a href="{{ route('dashboard') }}" class="register-modal-opener login-button"><i class="material-icons">perm_identity</i> Личный кабинет</a>
            @endauth

            @guest
				<a href="{{ route('login') }}" class="register-modal-opener login-button"><i class="material-icons">perm_identity</i> Войти</a>
            @endguest

		</div>
	</div>
</nav>

<div class="mobile-menu">
	<nav class="mobile-nav">
		<ul class="mobile-menu-list">
			<li>
				@auth
					<a href="{{ route('dashboard') }}" class="register-modal-opener login-button"><i class="material-icons">perm_identity</i> Личный кабинет</a>
				@endauth

				@guest
					<a href="{{ route('login') }}" class="register-modal-opener login-button"><i class="material-icons">perm_identity</i> Войти</a>
				@endguest
			</li>
			<li><a href="/courses/">Курсы</a></li>
			<li><a href="/teachers/">Преподаватели</a></li>
			<li><a href="/about/">О нас</a></li>
			<li><a href="/contacts/">Контакты</a></li>
		</ul>
	</nav>
</div>

</header>
<!-- End Header -->
