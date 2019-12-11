<template>
    <div>
        <div class="flex-row mb-3 text-center">
            <h1 class="my-auto pr-3"><span class="text-dark">RM</span> <span class="text-primary">{{ convert }}</span>
            </h1>
            <input type="hidden" v-model="convertNum" name="start" required>
        </div>

        <div class="row justify-content-center">
            <div class="bg-dark text-center moneyNumber py-3 h3 m-0 border border-primary rounded m-1"
                 v-for="num in number" v-bind:class="num==='0' ? 'col-6' : 'col-3' " v-on:click="onNumberClick(num)">
                <span class="text-light my-3">{{num}}</span>
            </div>
            <div class="bg-danger text-center moneyNumber py-3 h5 m-0 border border-dark rounded m-1 col-3"
                 v-on:click="onReset()">
                <span class="text-light my-3">RESET</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                number: ["7", "8", "9", "4", "5", "6", "1", "2", "3", "0"],
                inserted: [],
                convert: "0.00",
                convertNum: 0
            }
        },
        mounted() {

        }
        , methods: {
            onNumberClick(number) {
                console.log(this.inserted);
                this.inserted.push(number);
                this.calculate();
            },
            onReset() {
                this.inserted = [];
                this.convert = "0.00";
                this.convertNum = 0;
            },
            calculate() {
                this.convertNum = this.inserted.join("");
                this.convert = Number(Number(this.convertNum) / 100).toFixed(2);
            }
        }
    }
</script>
