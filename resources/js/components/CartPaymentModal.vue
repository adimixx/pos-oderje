<template>
    <div>
        <div class="row">
            <div class="col-4" v-if="viewItem && !paidPayment">
                <p class="text-dark text-center">Total : </p>
                <p class="font-weight-bolder text-primary text-center">{{currency}} {{totalPrice}}</p>

                <div class="text-center">
                    <p class="font-weight-bolder text-success">Payment Method</p>

                    <div class="bg-success p-4 my-2" v-on:click="selectedMethod(1)"><p class="text-light"><i
                        class="fa fa-money-bill fa-2x-"></i> Cash</p></div>
                    <div class="bg-primary p-4" v-on:click="selectedMethod(2)"><p class="text-light"><i
                        class="fa fa-money-bill fa-2x-"></i> Oderje App</p></div>

                    <div class="bg-danger p-4 my-2" @click="cancelModal"><p class="text-light"><i
                        class="fas fa-ban fa-1x"></i> Cancel Payment</p></div>
                </div>
            </div>

            <div class="col" v-if="method===1">
                <div class="col-12 text-center" v-if="paidPayment">
                    <p class="text-secondary">Total</p>
                    <h3 class="text-primary">{{currency}} {{totalPrice}}</h3>
                </div>

                <div class="col-12 text-center">
                    <p class="text-secondary">Paid Amount</p>
                    <h3 class="text-primary">{{currency}} {{inputCash}}</h3>
                    <div v-if="!paidAmount">
                        <p class="text-danger">Insufficient amount inserted. Balance : {{currency}}
                            {{UnpaidBalance}}</p>
                    </div>
                </div>

                <div class="col-12 text-center" v-if="paidPayment">
                    <p class="text-secondary">Balance</p>
                    <h3 class="text-primary">{{currency}} {{paidBalance}}</h3>
                </div>

                <div class="col-4 offset-4 bg-success my-4 py-3 text-center" @click="doneModal" v-if="paidPayment">
                    <p class="text-light"><i class="fas fa-check fa-1x"></i> Done</p>
                </div>

                <div class="row my-2 mx-1" v-if="!paidPayment">
                    <div class="text-center col-3 bg-primary p-4" v-for="item in cash.cash"
                         v-on:click="moneyPress(item)">
                        <p class="text-dark">{{currency}} {{item}}</p>
                    </div>

                    <div class="text-center col-3 bg-secondary p-4" v-for="item in cash.syiling"
                         v-on:click="moneyPress(item*0.01)">
                        <p class="text-light">{{item}} sen</p>
                    </div>

                    <div class="text-center col-3 bg-danger p-4" v-on:click="moneyPress(false)">
                        <p class="text-light">RESET</p>
                    </div>
                    <div class="text-center col-3 bg-success p-4" v-on:click="moneyPress(true)">
                        <p class="text-light">PAY</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center" v-if="!viewItem">
            <p class="text-danger">There are no items in cart</p>
            <div class="bg-danger p-4 my-2" @click="cancelModal"><p class="text-light"><i
                class="fas fa-ban fa-2x-"></i> OK</p></div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            currency: String, taxset: Number
        },
        mounted() {
            console.log('modal mounted');
            this.$root.$on('ReturnCartList', (item) => {
                this.cartList = item;
                this.calculateTotal();
            });
            this.$root.$emit('GetCartList');
        },
        data() {
            return {
                method: 0,
                totalPrice: 0,
                cartList: [],
                viewItem: false,
                paidAmount: true,
                UnpaidBalance: 0,
                paidPayment: false,
                paidBalance: 0,
                inputCash: Number(0).toFixed(2),
                inputCashNumeric: 0,
                cash: {cash: [1, 5, 10, 20, 50, 100], syiling: [5, 10, 20, 50]}
            }
        },
        methods: {
            calculateTotal: function () {
                if (this.cartList.length <= 0) {
                    this.viewItem = false;
                } else {
                    this.totalPrice = 0;

                    for (var i = 0; i < this.cartList.length; i++) {
                        this.totalPrice += (this.cartList[i].quantity * this.cartList[i].item.product.price);
                    }

                    this.totalPrice = Number(this.totalPrice + (this.totalPrice * this.taxset)).toFixed(2);
                    this.viewItem = true;
                }
            },
            selectedMethod: function (method) {
                this.method = Number(method);
            },
            moneyPress: function (item) {
                if (item === true) {
                    this.UnpaidBalance = this.inputCashNumeric - Number(this.totalPrice);

                    if (this.UnpaidBalance < 0) {
                        this.UnpaidBalance = Number(this.UnpaidBalance * -1).toFixed(2);
                        this.paidAmount = false;
                    } else {
                        axios.post('/cashier/save',{
                            order: this.cartList,
                            moneyin: this.inputCashNumeric
                        }).then(function (data) {
                            console.log(data);
                        }).catch(function (data) {
                            console.log(data);
                        });

                        this.paidAmount = true;
                        this.paidPayment = true;

                        this.paidBalance = Number(this.UnpaidBalance).toFixed(2);
                    }
                } else if (item === false) {
                    this.inputCashNumeric = 0;
                } else {
                    this.inputCashNumeric += Number(item);
                }

                this.inputCash = Number(this.inputCashNumeric).toFixed(2);

            },
            cancelModal: function () {
                this.$root.$emit('bv::toggle::modal', 'modal-1', '#btnToggle');
            },
            doneModal: function () {
                this.$root.$emit('ResetCart');
                this.cancelModal();
            }

        }
    }
</script>
