@extends('layouts.login_bg')


@section('content')
    <div class="row w-100 m-1 justify-content-center">
        <div class="col-xl-6 col-md-12 h-100 w-100 p-1 m-1">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    @if($machineDetails === null)
                    <div class="bg-danger m-4 p-4 text-center">
                        <h5 class="text-light">This machine has not been configured</h5>
                        <p class="text-light my-0">Please login with Business / Merchant Admin credentials</p>
                    </div>
                    @endif
                    <div>
                        <div class="col-lg-12">
                            <div class="p-5 pt-1">
                                <div class="login-image m-4 p-2"></div>
                                @if($machineDetails !== null)
                                <div class="bg-success text-center m-4 p-2">
                                    <p class="text-light m-2"><b>{{$machineOwner}}</b> : {{$machineDetails->name}}</p>
                                </div>
                                @endif
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Sign In</h1>
                                </div>
                                <form method="POST" class="user" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text"
                                               class="form-control form-control-user py-5 text-center @error('username') is-invalid @enderror"
                                               id="username" placeholder="Username" name="username"
                                               value="{{ old('username') }}" required autocomplete="username" autofocus>

                                        @error('username')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input type="password"
                                               class="form-control form-control-user py-5 text-center @error('password') is-invalid @enderror"
                                               id="password" placeholder="Password" name="password" required
                                               autocomplete="password">


                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block p-4">
                                        <span class="text-light">{{ __('Login') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
