<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>訪客訂餐</title>
    <?php include "link.php";?>
</head>
<body>
<?php include "header.php";?>
<div id="app" class="container mt-4">
    <h2 class="mb-4">訪客訂餐</h2>

    <div>
        <h2>菜單</h2>
        <ul class="list-group">
            <li class="list-group-item" v-for="item in menu">
                {{ item.name }} - ${{ item.price }}
                <button class="btn btn-primary btn-sm float-right" @click="addToOrder(item)">加入訂單</button>
            </li>
        </ul>
    </div>

    <!-- 訂單 -->
    <div class="mt-4">
        <h2>訂單</h2>
        <ul class="list-group">
            <li class="list-group-item" v-for="item in order">
                {{ item.name }} - ${{ item.price }} x {{ item.quantity }}
            </li>
        </ul>
        <p class="mt-2">總價： ${{ total }}</p>
    </div>

    <!-- 最後確認訂單 -->
    <div class="mt-4">
        <h2>確認訂單</h2>
        <button class="btn btn-success" @click="confirmOrder">確定訂單</button>
    </div>
</div>
<script>
    Vue.createApp({
        data() {
            return {
                menu: [
                    { name: "菜品1", price: 10 },
                    { name: "菜品2", price: 12 },
                ],
                order: [],
            };
        },
        computed: {
            total() {
                return this.order.reduce((acc, item) => acc + item.price * item.quantity, 0);
            },
        },
        methods: {
            addToOrder(item) {
                const existingItem = this.order.find(orderItem => orderItem.name === item.name);
                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    this.order.push({ ...item, quantity: 1 });
                }
            },
            confirmOrder() {
                alert('訂單已確認！總價：$' + this.total);
                this.order = [];
            },
        },
    }).mount('#app');
</script>
</body>
</html>
