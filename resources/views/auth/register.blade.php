@extends('frontend.app')



@section('content')

<div class="container margin_120_95">
    <div class="row justify-content-center">
        <div class="col-md-8">
        @if(session()->has('toPaymentGate'))
            <div class="row justify-content-center alert alert-success">              
                Для покупки курса необходима регистрация.            
            </div>
        @endif
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Account type') }}</label>

                            <div class="col-md-6">
                                    <div id="radioBtn" class="btn-group pt-1">
                                        <a class="btn btn-primary btn-sm active" data-toggle="type" data-title="fiz">Физическое лицо</a>
                                        <a class="btn btn-primary btn-sm notActive" data-toggle="type" data-title="jur">Юридическое лицо</a>
                                    </div>
                                    <input type="hidden" name="type" id="type">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right required">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right required">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right required">{{ __('Phone') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right required">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <div class="input-group" id="show_hide_password">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" minlength="8" placeholder="Не менее 8-ми символов">

                                      <div class="input-group-append">
                                            <a class="input-group-text"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                      </div>
                                </div>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- ЮРИДИЧЕСКОЕ ЛИЦО-->   

                        <div class="form-group row jur" style="display: none">
                            <label for="jaddress" class="col-md-4 col-form-label text-md-right required">{{ __('Jur address') }}</label>

                            <div class="col-md-6">
                                <input id="jaddress" type="jaddress" class="form-control @error('jaddress') is-invalid @enderror jinput" name="jaddress" value="{{ old('jaddress') }}" autocomplete="jaddress">

                                @error('jaddress')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row jur" style="display: none">
                            <label for="inn" class="col-md-4 col-form-label text-md-right required">{{ __('INN') }}</label>

                            <div class="col-md-6">
                                <input id="inn" type="inn" class="form-control @error('inn') is-invalid @enderror jinput" name="inn" value="{{ old('inn') }}" autocomplete="inn">

                                @error('inn')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row jur" style="display: none">
                            <label for="kpp" class="col-md-4 col-form-label text-md-right required">{{ __('KPP') }}</label>

                            <div class="col-md-6">
                                <input id="kpp" type="kpp" class="form-control @error('kpp') is-invalid @enderror jinput" name="kpp" value="{{ old('kpp') }}" autocomplete="kpp">

                                @error('kpp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row jur" style="display: none">
                            <label for="rs" class="col-md-4 col-form-label text-md-right required">{{ __('RS') }}</label>

                            <div class="col-md-6">
                                <input id="rs" type="kpp" class="form-control @error('rs') is-invalid @enderror jinput" name="rs" value="{{ old('rs') }}" autocomplete="rs">

                                @error('rs')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row jur" style="display: none">
                            <label for="bank" class="col-md-4 col-form-label text-md-right required">{{ __('Bank Name') }}</label>

                            <div class="col-md-6">
                                <input id="bank" type="bank" class="form-control @error('bank') is-invalid @enderror jinput" name="bank" value="{{ old('bank') }}" autocomplete="bank">

                                @error('bank')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row jur" style="display: none">
                            <label for="bic" class="col-md-4 col-form-label text-md-right required">{{ __('BIC') }}</label>

                            <div class="col-md-6">
                                <input id="bic" type="bic" class="form-control @error('bic') is-invalid @enderror jinput" name="bic" value="{{ old('bic') }}" autocomplete="bic">

                                @error('bic')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row jur" style="display: none">
                            <label for="ks" class="col-md-4 col-form-label text-md-right required">{{ __('KS') }}</label>

                            <div class="col-md-6">
                                <input id="ks" type="ks" class="form-control @error('ks') is-invalid @enderror jinput" name="ks" value="{{ old('ks') }}" autocomplete="ks">

                                @error('ks')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row jur" style="display: none">
                            <label for="dn" class="col-md-4 col-form-label text-md-right required">{{ __('Director Name') }}</label>

                            <div class="col-md-6">
                                <input id="dn" type="dn" class="form-control @error('dn') is-invalid @enderror jinput" name="dn" value="{{ old('dn') }}" autocomplete="dn">

                                @error('dn')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- ЮРИДИЧЕСКОЕ ЛИЦО END-->   
                                             
       
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('login') }}">
                                        {{ __('Login') }}
                                    </a>
                                @endif

                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4 mt-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="terms" id="terms" checked>

                                    <label class="form-check-label" for="terms">
                                        Я согласен на обработку <a href="/personal-data/" target="_blank">персональных данных</a>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="{{ asset('assets/js/jquery.mask.js') }}"></script>
@endpush

<!-- https://igorescobar.github.io/jQuery-Mask-Plugin/docs.html -->

@endsection
