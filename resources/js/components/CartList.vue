<template>
    <div class="card-body p-0">
        <div class="h-100 notFound" v-if="cartItems.length == 0">
            <div class="my-auto text-center py-5">
                <span class="text-muted">
                    <i class="fas fa-10x fa-shopping-cart"></i>
                    <p class="h6 p-5">Cart is empty</p>
                </span>
            </div>
        </div>

        <table class="table table-responsive table-hover h-100 m-0" v-if="cartItems.length != 0">
            <tbody>
            <tr v-for="cart in cartItems">
                <td class="text-left" v-on:click="onAddItem(cart)">
                    <i class="fas fa-plus fa-2x text-info pt-3"></i>
                </td>
                <td class="w-75">
                    <div class="row">
                        <img class="col-md-4 img-fluid" style="height: 90px"
                             v-bind:src="'https://app.oderje.com/images/product/'+(cart.item.product.img ? cart.item.product.img :'default.jpeg')">
                        <div class="col-md-8">
                            <p class="font-weight-bolder">{{cart.item.product.name}}</p>
                            <p class="text-primary">{{priceUnit}} {{Number(cart.item.product.price).toFixed(2)}}</p>
                        </div>
                    </div>
                </td>
                <td class="text-center w-25">
                    <div class="p-1">
                        <p class="font-weight-bolder">{{cart.quantity}}</p>
                    </div>
                    <div class="p-1">
                        <p class="font-weight-bold text-success">{{priceUnit}} {{cart.total}}</p>
                    </div>
                </td>
                <td class="text-right" v-on:click="onRemoveItem(cart)">
                    <i class="fas fa-minus fa-2x text-danger pt-3"></i>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                cartItems: [],
                priceUnit: 'RM',
                manualItem: 1
            }
        },
        mounted() {
            this.$root.$on('AddItem', (dat) => {
                this.onAddNewItem(dat);
            });
            this.$root.$on('ResetCart', () => {
                this.resetCart();
            });
            this.$root.$on('GetCartList', () => {
                this.$root.$emit('ReturnCartList', this.cartItems);
            });
        }
        , methods: {
            onAddNewItem: function (item) {
                var exist = false;
                if (item.type.toUpperCase() == "MANUAL") {
                    item.product.name = "Item #" + this.manualItem;
                    this.cartItems.push({
                        item: item,
                        quantity: item.quantity,
                        total: Number(item.product.price * 1).toFixed(2)
                    });
                    this.manualItem++;
                } else if (item.type.toUpperCase() == "PRODUCT") {
                    for (var i = 0; i < this.cartItems.length; i++) {
                        if (this.cartItems[i].item === item) {
                            var existCart = this.cartItems[i];
                            existCart.quantity++;
                            existCart.total = Number(existCart.quantity * existCart.item.product.price).toFixed(2);

                            exist = true;
                            break;
                        }
                    }
                    if (!exist) {
                        this.cartItems.push({
                            item: item,
                            quantity: 1,
                            total: Number(item.product.price * 1).toFixed(2)
                        });
                    }
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
                            if (existCart.item.type.toUpperCase() == "MANUAL") this.manualItem--;
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
