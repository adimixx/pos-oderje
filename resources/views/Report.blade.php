@extends('layouts.app')

@section('content')
    <div class="px-5 mx-0 my-5" style="width: 100%">
        <div class="row mb-3">
            <div class="col-12">
                <div class="card mx-3">
                    <div class="card-header text-center">
                        <h4>Reporting</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <div class="row">
                                    <p class="text-primary col-2">Staff Name : </p>
                                    <p class="text-dark col-3">{{$user->name}}</p>
                                    <p class="text-primary col-3">Shift Start Time :</p>
                                    <p class="text-dark col-4">{{$onlineTime->created_at}}</p>
                                    <div class="col-6">
                                        <p class="text-primary">Total Collected : </p>
                                        <p class="text-dark">RM {{number_format(($total), 2, '.', '')}}</p>
                                    </div>
                                    <div class="col-6">
                                        <p class="text-primary">Cash left in register : </p>
                                        <p class="text-dark">RM {{number_format(($totalCash), 2, '.', '')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card mx-3 mt-4">
                    <div class="card-body text-center">
                        <p>Detailed report</p>
                        <table class="table table-hover table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th>Amount (RM)</th>
                                <th>Cash in (RM)</th>
                                <th>Cash out (RM)</th>
                                <th>Time</th>
                                <th>Payment Channel</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($collection as $col)
                                <tr>
                                    <td class="text-primary">{{number_format(($col->bill->transaction->amount/100), 2, '.', '')}}</td>
                                    <td class="text-success">{{number_format(($col->money_in/100), 2, '.', '')}}</td>
                                    <td class="text-danger">{{number_format((($col->money_in-$col->bill->transaction->amount)/100), 2, '.', '')}}</td>
                                    <td>{{substr($col->bill->bill_date, 11)}}</td>
                                    <td>{{$col->bill->transaction->type}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
