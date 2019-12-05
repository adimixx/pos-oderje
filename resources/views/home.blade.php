@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <a href="{{route('cashier')}}">
                    <div class="card">
                        <div class="card-body text-center">
                            <div>
                                <i class="fas fa-cash-register fa-10x text-primary"></i>
                                <p class="font-weight-bold text-center text-dark cardMain">POS</p>
                            </div>

                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4">
                <a href="{{route('report')}}">
                    <div class="card">
                        <div class="card-body text-center">
                            <div>
                                <i class="fas fa-clipboard fa-10x text-primary"></i>
                                <p class="font-weight-bold text-center text-dark  cardMain">REPORT</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4">
                <a href="{{route('conf')}}">
                    <div class="card">
                        <div class="card-body text-center">
                            <div>
                                <i class="fas fa-sliders-h fa-10x text-primary"></i>
                                <p class="font-weight-bold text-center text-dark cardMain">CONF</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
@endsection
