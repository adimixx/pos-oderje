@extends('layouts.app2')

@section('content')
    <div class="row mx-0 h-100 justify-content-center" id="test">
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
