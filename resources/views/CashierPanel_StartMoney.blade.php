@extends('layouts.app2')

@section('content')
    <div class="row mx-0 h-100 justify-content-center" id="test">
        <div class="col-lg-3 col-md-12 mt-5 pt-5">
            <div class="card mt-5">
                <div class="card-body">
                    <div class="card-img-top text-center py-3">
                        <img src="/assets/img/logo-oderje.png" class="w-75">
                    </div>
                    <p class="text-primary">Welcome back</p>
                    <p class="h4 text-dark">{{auth()->user()->name}}</p>

                    <a class="btn btn-lg btn-success btn-user btn-block my-4" @click="changeURL('{{route('home')}}')">
                        <span class="text-light">{{ __('Home') }}</span>
                    </a>
                    <a class="btn btn-lg btn-danger btn-user btn-block my-4" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <span class="text-light">{{ __('Logout') }}</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                          style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 my-auto">
            <form method="POST" action="{{ route('startMoney') }}">
                @csrf

                <div class="form-group text-center">
                    <label for="start" class="mb-4 h5 text-primary">Total Cash inside counter</label>
                    @error('start')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror

                    <start-money></start-money>

                    <button type="submit" class="btn btn-primary btn-user btn-block p-4 mt-4">
                        <span class="text-light">{{ __('Save') }}</span>
                    </button>
                </div>
        </div>
        </form>
    </div>
@endsection
