/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';

Vue.use(BootstrapVue);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component('cart-list', require('./components/CartList.vue').default);
Vue.component('cart-total', require('./components/CartTotal.vue').default);
Vue.component('cart-reset', require('./components/CartNew.vue').default);
Vue.component('modal-payment', require('./components/CartPaymentModal.vue').default);

Vue.component('cash-register', require('./components/CashRegisterCalculator.vue').default);


Vue.component('item-list', require('./components/ItemList.vue').default);

Vue.component('start-money', require('./components/StartMoney.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data: {
        showPayment: false,
        panelProduct: true,
        panelCalc:false,
        searchBox: ""
    },
    methods: {
        changeURL: function (url) {
            window.location = url;
        },
        changePosPanel: function (panelid) {
            if (panelid == 1) {
                this.panelProduct = true;
                this.panelCalc = false;
            } else if (panelid == 2) {
                this.panelProduct = false;
                this.panelCalc = true;
            }
        }
    },watch:{
        searchBox: function (val) {
            this.$root.$emit('SearchItem', val);
        }
    }
});



