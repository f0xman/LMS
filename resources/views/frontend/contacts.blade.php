@extends('frontend.app')

@section('content')



		<!-- map section -->
		<div id="map d-flex justify-content-center">

		<div style="position:relative;overflow:hidden;">
			<a href="https://yandex.ru/maps/2/saint-petersburg/?utm_medium=mapframe&utm_source=maps" style="color:#eee;font-size:12px;position:absolute;top:0px;">Санкт‑Петербург</a>
			<a href="https://yandex.ru/maps/2/saint-petersburg/house/zheleznovodskaya_ulitsa_32/Z0kYdAJgSk0GQFtjfXV0c3xkbA==/?ll=30.250381%2C59.952078&source=wizgeo&utm_medium=mapframe&utm_source=maps&z=16" style="color:#eee;font-size:12px;position:absolute;top:14px;">Железноводская улица, 32 на карте Санкт-Петербурга, ближайшее метро Приморская — Яндекс.Карты</a>
			<iframe src="https://yandex.ru/map-widget/v1/-/CCQ~JDQzlA" width="100%" height="400" frameborder="0" allowfullscreen="true" style="position:relative;"></iframe>
		</div>

		</div>
		<!-- end map section -->

				<!-- contact-info-section 
			================================================== -->
			<section class="contact-info-section">
			<div class="container">
				<div class="contact-info-box">
					<div class="row">

					<div class="col-lg-4 col-md-6">
							<div class="info-post">
								<div class="icon">
									<i class="fa fa-phone"></i>
								</div>
								<div class="info-content">
									<p>
									+7 (812) 385-58-40<br>
									Пн - Сб с 10:00 до 18:00
									</p>
								</div>
							</div>
						</div>

						<div class="col-lg-4 col-md-6">
							<div class="info-post">
								<div class="icon">
									<i class="fa fa-envelope-o"></i>
								</div>
								<div class="info-content">
									<p>
										E-Mail: <a href="mailto:info@mclass.pro">info@mclass.pro</a>
									</p>
								</div>
							</div>
						</div>

						<div class="col-lg-4 col-md-6">
							<div class="info-post">
								<div class="icon">
									<i class="fa fa-map-marker"></i>
								</div>
								<div class="info-content">
									<p>
										Санкт-Петербург
										<br />Железноводская 32
									</p>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</section>
		<!-- End contact-info section -->


		<!-- about-section 
			================================================== -->
			<section class="about-section">
			<div class="container">
				<div class="about-article">
					<div class="row">


					<div class="col-lg-12">
							<div class="article-content">

								<h2>Реквизиты</h2>

								<p>ИП Шлапацкий Никита Анатольевич</p>

								Номер счёта: 40802810332210000076<br />
								ИНН: 471206226111<br />
								Банк: ФИЛИАЛ «САНКТ-ПЕТЕРБУРГСКИЙ» АО «АЛЬФА-БАНК»<br />
								БИК: 044030786<br />
								Кор. счёт: 30101810600000000786<br />

							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End about section -->


@endsection
