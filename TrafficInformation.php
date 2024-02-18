<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>交通資訊</title>
    <?php include "link.php";?>
</head>
<body class="bg-warning">
<div class="container">
<?php include "header.php";?>
    <div class="card p-3 shadow bg-light">
    <!-- 即時交通狀況 -->
    <div class="alert alert-info">
        <h3>即時交通狀況</h3>
        <p>道路通暢，目前沒有擁堵或事故報告。</p>
    </div>

    <!-- 交通路線查詢 -->
    <div class="card">
        <div class="card-body">
            <h3>交通路線查詢</h3>
            <form id="routeForm">
                <div class="form-group">
                    <label for="start">起點：</label>
                    <input type="text" class="form-control" id="start" required>
                </div>
                <div class="form-group">
                    <label for="end">目的地：</label>
                    <input type="text" class="form-control" id="end" required>
                </div>
                <button type="submit" class="btn btn-primary">查詢路線</button>
            </form>
            <div id="routeResult"></div>
        </div>
    </div>

    <!-- 公共交通資訊 -->
    <div class="card mt-3">
        <div class="card-body">
            <h3>公共交通資訊</h3>
            <p>巴士時刻表、地鐵路線圖和票價信息。</p>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <h3>使用者反饋</h3>
            <form id="feedbackForm">
                <div class="form-group">
                    <label for="feedback">您的意見：</label>
                    <textarea class="form-control" id="feedback" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">提交反饋</button>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    $("#routeForm").submit(function(event) {
        event.preventDefault();

        var start = $("#start").val();
        var end = $("#end").val();
        var result = "從 " + start + " 到 " + end + " 的最佳路線為...";
        $("#routeResult").html(result);
    });
    $("#feedbackForm").submit(function(event) {
        event.preventDefault();

        var feedback = $("#feedback").val();
        alert("感謝您的反饋：" + feedback);
    });
</script>
</body>
</html>