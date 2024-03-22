<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>交通資訊</title>
    <?php include "link.php";?>
</head>
<body class="bg-warning">
<div id="app" class="container">
    <?php include "header.php"; ?>
    <div class="card p-3 shadow bg-light">
        <div class="alert alert-info" role="alert">
            <h3>即時交通狀況</h3>
            <p>道路通暢，目前沒有擁堵或事故報告。</p>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h3>交通路線查詢</h3>
                <form @submit.prevent="queryRoute">
                    <div class="mb-3">
                        <label for="start" class="form-label">起點：</label>
                        <input type="text" class="form-control" v-model="start" required>
                    </div>
                    <div class="mb-3">
                        <label for="end" class="form-label">目的地：</label>
                        <input type="text" class="form-control" v-model="end" required>
                    </div>
                    <button type="submit" class="btn btn-primary">查詢路線</button>
                </form>
                <div class="mt-3">{{routeResult}}</div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h3>公共交通資訊</h3>
                <p>巴士時刻表、地鐵路線圖和票價信息。</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3>使用者反饋</h3>
                <form @submit.prevent="submitFeedback">
                    <div class="mb-3">
                        <label for="feedback" class="form-label">您的意見：</label>
                        <textarea class="form-control" v-model="feedback" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">提交反饋</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    Vue.createApp({
        data() {
            return {
                start: '',
                end: '',
                feedback: '',
                routeResult: ''
            };
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