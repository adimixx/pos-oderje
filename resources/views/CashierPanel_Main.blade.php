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

    <div class="row mx-0 h-100" id="cashierSide">
        <div class="col-1 py-5 px-0 d-flex align-items-start flex-column text-center bg-dark">
            <div class="w-100 py-3 border border-light border-left-0 border-right-0" @click="changeURL('{{route('home')}}')">
                <span class="text-primary">
                    <i class="fas fa-3x fa-bars"></i>
                </span>
                <h6 class="text-light"><small>Main Menu</small></h6>
            </div>

            <div class="w-100 py-3 border border-light border-left-0 border-right-0" @click="changePosPanel(1)">
                <span class="text-primary">
                <i class="fas fa-3x fa-cubes"></i>
                </span>
                <h6 class="text-light"><small>Product</small></h6>
            </div>
            <div class="w-100 py-3 border border-light border-left-0 border-right-0" @click="changePosPanel(2)">
                <span class="text-primary">
                <i class="fas fa-3x fa-calculator"></i>
                </span>
                <h6 class="text-light"><small>Manual</small></h6>
            </div>

            <div class="w-100 mt-auto py-3 border border-danger border-left-0 border-right-0 bg-danger" onclick="document.getElementById('logout-form').submit();">
                <span class="text-light">
                <i class="fas fa-3x fa-sign-out-alt"></i>
                </span>
                <h6 class="text-light"><small>Sign Out</small></h6>

                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                      style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

        <div class="col-6 p-0 d-flex align-items-start flex-column">
                <cart-reset></cart-reset>
            <div class="text-center w-100 p-0">
                <input class="form-control" placeholder="Search">
            </div>
            <div class="card my-0 flex-grow-1 overflow-auto w-100">
                <div class="card-body">
                    <item-list v-show="panelProduct"></item-list>
                    <cash-register v-show="panelCalc"></cash-register>
                </div>
            </div>
        </div>

        <div class="col-5 p-0 d-flex flex-column">
            <div class="flex-grow-1 overflow-hidden">
                <div class="full d-flex flex-column h-100">
                    <div class="card-header text-center py-4">
                        <span class="py-2"><p class="h4">Cart</p></span>
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

