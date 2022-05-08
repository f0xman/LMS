                @if ($errors->any())
                    <div class="row justify-content-center">
                            <div class="col-md-5 alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    {!! $error !!}
                                @endforeach
                            </div>
                    </div>
                @endif

                @if (session('error'))
                        <div class="row justify-content-center">
                            <div class="col-md-5 alert alert-danger">
                                {!! session('error') !!}
                            </div>
                        </div>
                 @endif

                @if(session()->get('success'))
                  <div class="row justify-content-center">
                        <div class="col-md-5 alert alert-success">
                        {!! session()->get('success') !!}  
                        </div>
                    </div>
                @endif
