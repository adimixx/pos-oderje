<template>
    <div class="row">
        <div class="card col-xl-3 col-4" v-for="item in items" v-on:click="onClickItem(item)">
            <div class="card-body text-center">
                <img class="img-fluid" style="height: 90px"
                     v-bind:src="'https://app.oderje.com/images/product/'+ (item.product.img ? item.product.img : 'default.jpeg') ">
                <p class="card-text">{{item.product.name}}</p>
            </div>
        </div>
    </div>
</template>

<script>
    import cartList from './CartList';

    export default {
        data() {
            return {
                items: []
            }
        },
        mounted() {
            axios.get('/cashier/list')
                .then(function (response) {
                    this.items = response.data;
                }.bind(this), 'json');
        }
        , methods: {
            onClickItem: function (item) {
                this.$root.$emit('AddItem', item);
            }
        }
    }
</script>
