@extends('layouts.app')

@section('content')
    <div class="px-5 mx-0 my-5" style="width: 100%">
        <div class="row mb-3">
            <div class="col-12">
                <div class="card mx-3">
                    <div class="card-header text-center">
                        <h4>Current Device Configuration</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @isset($currentSetting)
                                <div class="col-lg-6">
                                    <div class="text-left">
                                        <h5 class="font-weight-bold text-primary">Business Profile</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <p class="font-weight-bold text-secondary">Business Name</p>
                                        </div>
                                        <div class="col-lg-8">
                                            <p>{{$currentSetting['business']->b_name}}</p>
                                        </div>
                                        <div class="col-lg-4">
                                            <p class="font-weight-bold text-secondary">Register No.</p>
                                        </div>
                                        <div class="col-lg-8">
                                            <p>{{$currentSetting['business']->b_reg_no}}</p>
                                        </div>
                                        <div class="col-lg-4">
                                            <p class="font-weight-bold text-secondary">Phone No.</p>
                                        </div>
                                        <div class="col-lg-8">
                                            <p>{{$currentSetting['business']->b_phone_no}}</p>
                                        </div>
                                    </div>
                                </div>
                                @isset($currentSetting['merchant'])
                                    <div class="col-lg-6">
                                        <div class="text-left">
                                            <h5 class="font-weight-bold text-primary">Merchant Profile</h5>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <p class="font-weight-bold text-secondary">Merchant Name</p>
                                            </div>
                                            <div class="col-lg-8">
                                                <p>{{$currentSetting['merchant']->m_name}}</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="font-weight-bold text-secondary">Register No.</p>
                                            </div>
                                            <div class="col-lg-8">
                                                <p>{{$currentSetting['merchant']->m_reg_no}}</p>
                                            </div>
                                            <div class="col-lg-4">
                                                <p class="font-weight-bold text-secondary">Phone No.</p>
                                            </div>
                                            <div class="col-lg-8">
                                                <p>{{$currentSetting['merchant']->m_phone}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endisset
                                <div class="col-lg-6 @isset($currentSetting['merchant']) offset-lg-6 @endisset">
                                    <div class="text-left">
                                        <h5 class="font-weight-bold text-primary">Machine Type</h5>
                                    </div>
                                    <p class="font-weight-bold text-secondary">{{  $currentSetting['machine']['description'] }}</p>
                                </div>
                            @else
                                <div class="col-lg-12 text-center">
                                    <p class="text-danger">This device has not been set up yet</p>
                                </div>
                            @endif


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mx-3">
                    <form method="POST" action="{{ route('confPOST') }}">
                        @csrf
                        <div class="card-header text-center">
                            <h4>Configuration</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="machine" class="col-form-label-lg font-weight-bold text-primary">Machine
                                        Type</label>
                                </div>
                                <div class="col-lg-9">
                                    <select id="machine" name="machine" class="form-control form-control-lg">
                                        @foreach($machineType as $in1)
                                            <option value="{{$in1->id}}"
                                                    @isset($currentSetting['machine']) @if($currentSetting['machine']['id'] == $in1->id)  selected @endif @endisset>{{$in1->description}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <label for="business" class="col-form-label-lg font-weight-bold text-primary">Business
                                        Profile</label>
                                </div>
                                <div class="col-lg-9">
                                    <p id="business" class="form-control-lg">{{$business->b_name}}</p>
                                </div>

                                @if(count($merchant) !== 0)
                                    <div class="col-lg-3">
                                        <label for="merchant" class="col-form-label-lg font-weight-bold text-primary">Merchant
                                            Profile</label>
                                    </div>
                                    <div class="col-lg-9">
                                        <select id="merchant" name="merchant" class="form-control form-control-lg">
                                            @foreach($merchant as $in2)
                                                <option value="{{$in2['m_id']}}"
                                                        @isset($currentSetting['merchant']) @if($currentSetting['merchant']->m_id == $in2['m_id'])  selected @endif @endisset>{{$in2->description}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>

                            <div class="row m-3">
                                <div class="col-lg-4 offset-lg-4 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
