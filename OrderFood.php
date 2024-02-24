<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>訪客訂餐</title>
    <?php include "link.php";?>
</head>
<body class="bg-warning">
<div id="app" class="container">
    <?php include 'header.php';?>
    <div class="card shadow">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">訪客訂餐</h2>

            <div class="mb-4">
                <h3>菜單</h3>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center" v-for="item in menu">
                        {{ item.name }} - ${{ item.price }}
                        <button class="btn btn-primary btn-sm" @click="addToOrder(item)">加入訂單</button>
                    </li>
                </ul>
            </div>

            <div class="mb-4">
                <h3>訂單</h3>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center" v-for="item in order">
                        {{ item.name }} - ${{ item.price }} x {{ item.quantity }}
                    </li>
                </ul>
                <p class="mt-3">總價： ${{ total.toFixed(2) }}</p>
            </div>

            <div class="text-center">
                <button class="btn btn-success" @click="confirmOrder">確定訂單</button>
            </div>
        </div>
    </div>
</div>
<script>
    Vue.createApp({
        data() {
            return {
                menu: [{ name: "菜品1", price: 10 }, { name: "菜品2", price: 12 }],
                order: [],
            };
        },
        computed: {
            total() {
                return this.order.reduce((total, item) => total + item.price * item.quantity, 0);
            },
        },
        methods: {
            addToOrder(item) {
                let existing = this.order.find(i => i.name === item.name);
                existing ? existing.quantity++ : this.order.push({...item, quantity: 1});
            },
            confirmOrder() {
                alert(`訂單已確認！總價：$${this.total.toFixed(2)}`);
                this.order = [];
            },
        },
    }).mount('#app');
</script>
</body>
</html>