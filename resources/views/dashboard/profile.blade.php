@extends('dashboard.app')

@section('content')

@include('dashboard.includes.messages')


       <div class="row">
            <div class="col-md-6">
				<div class="box_general padding_bottom">
					<div class="header_box version_2">
						<h2><i class="fa fa-user"></i>Профиль</h2>
					</div>

                    <form method="POST" action="{{ route('saveProfile') }}">
                        @csrf
					        <div class="form-group">
						        <label for="name">Имя</label>
						        <input class="form-control  @error('name') is-invalid @enderror" name="name" value="{{ $profile->name }}" id="name" type="name" autofocus required>
                                    @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
					        </div>

                            <div class="form-group">
						        <label for="email">E-mail</label>
						        <input class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $profile->email }}" id="email" type="email" autofocus required>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
					        </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-secondary">
                                    {{ __('Save') }}
                                </button>
					        </div>
                    </form>

				</div>
			</div>

			<div class="col-md-6">
				<div class="box_general padding_bottom">
					<div class="header_box version_2">
						<h2><i class="fa fa-lock"></i>Сменить пароль</h2>
					</div>
                    <form method="POST" action="{{ route('savePassword') }}">
                        @csrf
					        <div class="form-group">
						        <label for="password">Текущий пароль</label>
                                    <div class="input-group" id="show_hide_password">
						                <input class="form-control @error('password') is-invalid @enderror" name="password" id="password" type="password" autofocus required>
                                            <div class="input-group-append">
                                                <a class="input-group-text"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
					        </div>
					        <div class="form-group">
						        <label for="new_password">Новый пароль</label>
                                    <div class="input-group" id="show_hide_newpassword">
						                <input class="form-control @error('new_password') is-invalid @enderror" name="new_password"  id="new_password" type="password" placeholder="Не менее 8-ми символов" autofocus required>
                                            <div class="input-group-append">
                                                <a class="input-group-text"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                            </div>
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
					        </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-secondary">
                                    {{ __('Change') }}
                                </button>
					        </div>
                    </form>
				</div>
			</div>

		</div>
		<!-- /row-->

 

@endsection
