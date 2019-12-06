@extends('layouts.app2')

@section('content')
    <div class="row mx-0 h-100" id="test">
        <div class="col-12">
            <form method="POST" action="{{ route('startMoney') }}">
                @csrf
                <div class="form-group">
                    <label for="start">Start Money</label>
                    <input type="number"
                           class="form-control form-control-user py-5 text-center @error('start') is-invalid @enderror"
                           id="start" placeholder="Enter Starting Money"
                           name="start"
                           value="{{ old('start') }}" required autofocus>

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
