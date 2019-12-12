<template>
    <div class="w-100 h-100">
        <div class="m-5 py-4 bg-dark text-right">
            <p class="text-light h3 m-3">{{calculatorDisplay}}</p>
        </div>

        <div class="d-flex flex-row m-5">
            <div class="row justify-content-center flex-grow-1 ml-0 mr-2">
                <div class="pl-0 pr-1 pb-1" v-for="num in number" v-bind:class="num==='0' ? 'col-8' : 'col-4' "
                     v-on:click="onTap(num)">
                    <div class="bg-dark text-center moneyNumber py-3 h3 m-0 border border-primary rounded m-1">
                        <span class="text-light my-3">{{num}}</span>
                    </div>
                </div>

                <div class="pl-0 pr-1 pb-1 col-4" v-on:click="onReset()">
                    <div class="bg-danger text-center moneyNumber py-3 h5 m-0 border border-dark rounded m-1">
                        <span class="text-light my-3">RESET</span>
                    </div>
                </div>

            </div>
            <div class="justify-content-center d-flex flex-column w-25">
                <div
                    class="flex-grow-1 bg-primary text-center moneyNumber py-5 h3 m-0 border border-primary rounded m-1"
                    v-for="op in operation"
                    v-on:click="onTap(op)">
                    <p class="text-light h3 my-3">{{op}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                number: ["7", "8", "9", "4", "5", "6", "1", "2", "3", "0"],
                operation: ["ADD", "QTY"],
                priceInsertedArray: [],
                quantityInsertedArray: [],
                priceCalculated: 0,
                quantity: 1,
                isQuantity: false,
                calculatorDisplay: "0.00",
                item: ""
            }
        },
        mounted() {

        }
        , methods: {
            onTap(input) {
                if ((input.toUpperCase() == "ADD" || input.toUpperCase() == "QTY") && this.priceCalculated == 0) {
                    alert("Please Insert an amount");
                    return;
                } else if (input.toUpperCase() == "ADD") {
                    this.item = {
                        product:{
                            img: "default.jpeg",
                            price: this.priceCalculated
                        },
                        quantity: this.quantity,
                        type:"MANUAL"
                    };
                    this.$root.$emit('AddItem', this.item);
                    this.onReset();
                } else if (input.toUpperCase() == "QTY" && this.isQuantity == false) {
                    this.isQuantity = true;
                } else if (this.isQuantity) {
                    this.quantityInsertedArray.push(input);
                    this.quantity = Number(this.quantityInsertedArray.join(""));
                } else {
                    this.priceInsertedArray.push(input);
                }
                this.calculate();
            },
            calculate() {
                this.priceCalculated = Number(this.priceInsertedArray.join("")) / 100;
                this.calculatorDisplay = Number(this.priceCalculated).toFixed(2);
                if (this.isQuantity) {
                    this.calculatorDisplay += " x ";
                }
                if (this.quantity > 1) {
                    this.calculatorDisplay += this.quantity;
                }
            },
            onReset() {
                this.priceInsertedArray = [];
                this.quantityInsertedArray = [];
                this.priceCalculated = 0;
                this.calculatorDisplay = "0.00";
                this.quantity = 1;
                this.isQuantity = false;
            }
        }
    }
</script>

