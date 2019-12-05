@extends('layouts.app2')

@section('content')
    <b-modal id="modal-1" title="Checkout" size="xl" v-model="showPayment" no-close-on-esc no-close-on-backdrop
             hide-header-close>
        <template v-slot:modal-header="{ }">
            <h5>Checkout</h5>
        </template>
        <modal-payment currency="{{$priceUnit}}" v-bind:taxset="{{ $taxSetting }}"></modal-payment>
        <template v-slot:modal-footer="{cancel, ok}">
            <p></p>
        </template>
    </b-modal>

    <div class="row mx-0 h-100" id="test">
        <div class="col-7 px-0 d-flex flex-column h-100">
            <div class="row m-1">
                <div class="col-1 p-0">
                    <a href="{{route('home')}}">
                        <div class="text-center py-4 mb-2 mt-0 bg-light">
                            <p class="font-weight-bold m-0 text-dark">Menu</p>
                        </div>
                    </a>
                </div>
                <div class="col p-0">
                    <cart-reset></cart-reset>
                </div>
            </div>
            <div>
                <input class="form-control" placeholder="Search">
            </div>
            <div class="card my-1 flex-grow-1 overflow-auto">
                <div class="card-body">
                    <item-list></item-list>
                </div>
            </div>
        </div>
        <div class="col-5 pl-2 pr-0 h-100 d-flex flex-column">
            <div class="flex-grow-1 overflow-hidden">
                <div class="full d-flex flex-column h-100">
                    <div class="card-header">
                        Cart
                    </div>
                    <div class="d-flex flex-row bg-dark text-light">
                        <p class="flex-grow-1 w-75 m-0 p-2 text-left font-weight-bold">Item</p>
                        <p class="w-50 m-0 ml-5 p-2 text-center font-weight-bold">Quantity</p>
                    </div>

                    <div class="card flex-grow-1 overflow-auto">
                        <div class="card-body p-0">
                            <cart-list></cart-list>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="card">
                    <div class="card-body">
                        <cart-total currency="{{ $priceUnit }}" v-bind:taxset="{{ $taxSetting }}"></cart-total>
                        <table class="table table-bordered m-0 mt-2">
                            <tbody>
                            <tr>
                                <td class="p-0">
                                    <div class="bg-success p-4" v-b-modal.modal-1 @click="showPayment = true"><p
                                            class="text-light text-center font-weight-bold m-0"><i
                                                class="fa fa-shopping-cart"></i> Checkout</p></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

