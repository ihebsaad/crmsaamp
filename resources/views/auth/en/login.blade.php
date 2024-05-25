@extends('layouts.newlogin')

@section('content')

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">{{__('msg.Login')}} </h1>
                                    </div>
                                    <form class="user"   method="POST" action="{{ route('login') }}">
										{{ csrf_field() }}
						
                                        <div class="form-group">
                                            <input   class="form-control form-control-user  form-control{{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}"
                                                id="login" aria-describedby="emailHelp"
                                                placeholder="<?php echo __('msg.Your username or email address');?>"   name="login" value="{{ old('username') ?: old('email') }}" required autofocus>
                                  @if ($errors->has('username') || $errors->has('email'))
                                    <span class="invalid-feedback">
								<strong>{{ $errors->first('username') ?: $errors->first('email') }}</strong>
									</span>
                                @endif                                     
									   </div>
                                        <div class="form-group">
                                            <input type="password" name="password"  class="form-control form-control-user"
                                                id="pasword" placeholder="<?php echo __('msg.Password');?>">
												
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif												
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck"    name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="customCheck">{{__('msg.Remember me')}} 
                                                    </label>
                                            </div>
                                        </div>
                                        <button type="submit"  class="btn btn-primary btn-user btn-block">
                                            {{__('msg.Login')}}
                                        </button>
 
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="{{ route('password.request') }}">{{__('msg.Forgot your password')}} ?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="{{ route('register') }}">{{__('msg.Create an account')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
	
	
@endsection