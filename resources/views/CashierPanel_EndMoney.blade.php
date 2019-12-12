@extends('layouts.app2')

@section('content')
    <div class="row mx-0 h-100 justify-content-center" id="test">
        <div class="col-lg-4 col-md-3 my-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <p class="text-primary">Total Collected : </p>
                            <p class="text-dark">RM {{number_format(($total), 2, '.', '')}}</p>
                        </div>
                        <div class="col-12">
                            <p class="text-primary">Calculated cash left in register : </p>
                            <p class="text-dark">RM {{number_format(($totalCash), 2, '.', '')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-9 my-auto">
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <div class="form-group text-center">
                    <label for="start" class="mb-4 h5 text-primary">Total Cash inside counter</label>
                    @error('start')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                    <start-money></start-money>

                    <div>
                        <a class="btn btn-lg btn-success btn-user btn-block p-4 mt-4" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            <span class="text-light">{{ __('Save and Logout') }}</span>
                        </a>
                    </div>
                    <a href="{{route('home')}}" class="btn btn-lg btn-danger btn-user btn-block p-4 mt-4">
                        <span class="text-light">{{ __('Cancel') }}</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
