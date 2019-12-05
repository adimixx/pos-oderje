<template>
    <table class="table table-borderless table-sm m-0">
        <tbody>
        <tr>
            <th scope="row" class="text-left">Subtotal</th>
            <td class="text-right">{{currency}} {{subtotal}}</td>
        </tr>
        <tr>
            <th scope="row" class="pl-3 text-muted text-left">Tax (6%)</th>
            <td class="text-right text-muted">{{currency}} {{tax}}</td>
        </tr>
        <tr>
            <th scope="row" class="text-left font-weight-bolder">Total</th>
            <td class="text-right font-weight-bolder">{{currency}} {{total}}</td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: {
            currency:String,
            taxset: Number
        },
        data() {
            return {
                subtotal: Number(0).toFixed(2),
                tax: Number(0).toFixed(2),
                total: Number(0).toFixed(2)
            }
        },
        mounted() {
            console.log('cartTotal mounted');
            this.$root.$on('ItemChanged', (items) => {
                this.ItemChanged(items);
            });
        }
        , methods: {
            ItemChanged: function (items) {
                this.total = 0;
                this.tax = 0;
                this.subtotal = 0;
                for (var i = 0; i < items.length; i++) {
                    this.subtotal += Number(items[i].item.product.price * items[i].quantity);
                }

                if (this.subtotal !== 0){
                    this.tax = this.subtotal * this.taxset;
                    this.total = this.subtotal + this.tax;
                }
                this.subtotal = Number(this.subtotal).toFixed(2);
                this.tax = Number(this.tax).toFixed(2);
                this.total = Number(this.total).toFixed(2);
            }
        }
    }
</script>
