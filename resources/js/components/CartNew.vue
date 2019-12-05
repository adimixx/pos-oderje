<template>
    <div id="newOrder" class="text-center py-4 my-0 mx-2">
        <div class="py-2" v-on:click="onClickNew">
            <p class="font-weight-bold m-0">Reset Cart</p>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('on click mounted');
        }
        , methods: {
            onClickNew: function () {
                this.$root.$emit('ResetCart');
            }
        }
    }
</script>
