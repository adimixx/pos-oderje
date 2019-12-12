<template>
    <table class="table table-responsive table-hover h-100 m-0">
        <tbody>
        <tr v-for="cart in cartItems">
            <td class="text-left" v-on:click="onAddItem(cart)">
                <i class="fas fa-plus fa-2x text-info pt-3" ></i>
            </td>
            <td class="text-center">
                    <p class="font-weight-bolder">{{priceUnit}} {{cart.totalPrice}}</p>
            </td>
            <td class="text-center">
                <p class="font-weight-light">{{cart.priceUnit}} {{cart.operation}} {{cart.operationNumber}}</p>
            </td>
            <td class="text-right"  v-on:click="onRemoveItem(cart)">
                <i class="fas fa-minus fa-2x text-danger pt-3"></i>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        data() {
            return {
                cartItems: [],
                priceUnit: 'RM'
            }
        },
        mounted() {
            this.$root.$on('amountPush', (dat1,dat2) => {
                this.onAddNewItem(dat1,dat2);
            });
        }
        , methods: {
            onAddNewItem: function (operation,num) {
                var exist = false;
                if (operation!=null && num != null) {
                    this.cartItems.push({priceUnit: num, totalPrice: num});
                }

                this.$root.$emit('ItemChanged', this.cartItems);
            }, onAddItem: function (item) {
                for (var i = 0; i < this.cartItems.length; i++) {
                    if (this.cartItems[i] === item) {
                        var existCart = this.cartItems[i];
                        existCart.quantity++;
                        existCart.total = Number(existCart.quantity * existCart.item.product.price).toFixed(2);

                        this.$root.$emit('ItemChanged', this.cartItems);
                        break;
                    }
                }
            },
            onRemoveItem: function (item) {
                for (var i = 0; i < this.cartItems.length; i++) {
                    if (this.cartItems[i] === item) {
                        var existCart = this.cartItems[i];
                        existCart.quantity--;
                        if (existCart.quantity === 0) {
                            this.cartItems.splice(i, 1);
                        } else {
                            existCart.total = Number(existCart.quantity * existCart.item.product.price).toFixed(2);
                        }
                        this.$root.$emit('ItemChanged', this.cartItems);
                        break;
                    }
                }
            },
            getCartList: function () {
                return this.cartItems;
            },
            resetCart: function () {
                while (this.cartItems.length > 0) {
                    this.cartItems.pop();
                }
                this.$root.$emit('ItemChanged', this.cartItems);
            }
        }
    }
</script>
