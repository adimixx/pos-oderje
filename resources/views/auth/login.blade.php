@extends('layouts.login_bg')

@section('content')
    <div id="login">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-8 col-md-12 my-auto">
                    <div class="card">
                        <div class="card-body">
                            @if($machineDetails === null)
                                <div class="bg-danger text-center p-3 mb-4">
                                    <h5 class="text-light">This machine has not been configured</h5>
                                    <p class="text-light my-0">Please login with Business / Merchant Admin
                                        credentials</p>
                                </div>
                            @endif

                            <div id="login-logo"></div>

                            @if($machineDetails !== null)
                                <div class="bg-success text-center">
                                    <span
                                        class="text-light m-2"><b>{{$machineOwner}}</b> : {{$machineDetails->name}}</span>
                                </div>
                            @endif

                            <form method="POST" class="user m-3" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="text"
                                           class="form-control form-control-user p-5 text-center @error('username') is-invalid @enderror"
                                           id="username" placeholder="Username" name="username"
                                           value="{{ old('username') }}" required autocomplete="username" autofocus>
                                </div>

                                <div class="form-group">
                                    <input type="password"
                                           class="form-control form-control-user p-5 text-center @error('password') is-invalid @enderror"
                                           id="password" placeholder="Password" name="password" required
                                           autocomplete="password">
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block p-4">
                                    <span class="text-light">{{ __('Login') }}</span>
                                </button>
                            </form>

                            @error('username')
                            <div class="text-center"><span
                                    class="text-danger"><strong>{{ $message }}</strong></span></div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
