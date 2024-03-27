<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>交通資訊</title>
    <?php include "link.php"; ?>
</head>
<body class="bg-warning">
<div id="app" class="container">
    <?php include "header.php"; ?>
    <div class="card p-3 shadow bg-light">
        <h3>即時交通狀況</h3>
        <p>道路通暢，目前沒有擁堵或事故報告。</p>

        <h3>交通路線查詢</h3>
        <form @submit.prevent="queryRoute">
            <label for="start" class="form-label">起點：</label>
            <input type="text" class="form-control" v-model="start" required>
            <label for="end" class="form-label">目的地：</label>
            <input type="text" class="form-control" v-model="end" required>
            <button type="submit" class="btn btn-primary mt-3">查詢路線</button>
        </form>
        <div>{{routeResult}}</div>

        <h3>使用者反饋</h3>
        <form @submit.prevent="submitFeedback">
            <label for="feedback" class="form-label">您的意見：</label>
            <textarea class="form-control" v-model="feedback" rows="3" required></textarea>
            <button type="submit" class="btn btn-primary mt-3">提交反饋</button>
        </form>
    </div>
</div>
<script>
    Vue.createApp({
        data() {
            return { start: '', end: '', feedback: '', routeResult: '' };
        },
        methods: {
            queryRoute() {
                this.routeResult = `從 ${this.start} 到 ${this.end} 的最佳路線為...`;
            },
            submitFeedback() {
                alert(`感謝您的反饋：${this.feedback}`);
                this.feedback = '';
            }
        }
    }).mount('#app');
</script>
</body>
</html>