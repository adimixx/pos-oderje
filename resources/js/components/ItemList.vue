<template>
    <div class="row" v-bind:class="{'h-100 notFound' : getItems.length == 0 || items.length == 0}">
        <div class="w-100 text-center my-auto" v-if="getItems.length != 0 && items.length == 0">
            <span class="text-muted">
                <i class="fas fa-10x fa-not-equal"></i>
                <p class="h4 p-5">Searched product does not exist</p>
            </span>
        </div>

        <div class="w-100 text-center my-auto" v-if="getItems.length == 0">
            <span class="text-muted">
                <i class="fas fa-10x fa-box-open"></i>
                <p class="h4 p-5">No item available</p>
            </span>
        </div>
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
                getItems: [],
                items: []
            }
        },
        mounted() {
            this.$root.$on('SearchItem', (items) => {
                this.searchItem(items);
            });

            axios.get('/cashier/list')
                .then(function (response) {
                    this.getItems = response.data;
                    this.items = this.getItems;
                }.bind(this), 'json');
        }
        , methods: {
            onClickItem: function (item) {
                item.type = "PRODUCT";
                this.$root.$emit('AddItem', item);
            },
            searchItem: function (query) {
                if (query == "") {
                    this.items = this.getItems;
                } else {
                    this.items = [];
                    var regex = RegExp(query, 'gi');
                    for (var i = 0; i < this.getItems.length; i++) {
                        if (regex.test(this.getItems[i].product.name)) {
                            this.items.push(this.getItems[i]);
                        }
                    }
                }
            }
        }
    }
</script>
