@extends('layouts.app2')

@section('content')
    <div class="row mx-0 h-100 justify-content-center" id="test">
        <div class="col-lg-6 col-md-12 my-auto">
            <form method="POST" action="{{ route('startMoney') }}">
                @csrf
                <div class="form-group text-center">
                    <label for="start">Starting Money</label>
                    <div class="d-flex flex-row">
                        <h1 class="my-auto pr-3">RM</h1>
                        <input type="number"
                               class="flex-grow-1 form-control form-control-user py-5 text-center @error('start') is-invalid @enderror"
                               id="start" placeholder="Enter Starting Money"
                               name="start"
                               value="{{ old('start') }}" required autofocus>
                    </div>

                    @error('start')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-user btn-block p-4">
                    <span class="text-light">{{ __('Save') }}</span>
                </button>
            </form>
        </div>
    </div>
@endsection
