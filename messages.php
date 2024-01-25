<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>訪客留言</title>
    <?php include_once "link.php";?>
</head>
<body>
<?php include_once "header.php";?>
<div class="container mt-4">
    <h2>訪客留言版</h2>

    <!-- 新增留言按鈕 -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#messageModal" onclick="prepareAddMessage()">
        新增留言
    </button>

    <!-- 燈箱 (Modal) - 新增和編輯留言 -->
    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">留言</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="messageForm" enctype="multipart/form-data">
                        <input type="hidden" id="messageId" name="id">
                        <div class="form-group">
                            <label for="name">姓名:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="messageNumber">留言編號 (4位數字):</label>
                            <input type="text" class="form-control" id="messageNumber" name="message_number" pattern="\d{4}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="phone">連絡電話:</label>
                            <input type="number" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="content">留言內容:</label>
                            <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">圖片上傳:</label>
                            <input type="file" class="form-control-file" id="image" name="image">
                        </div>
                        <button type="submit" class="btn btn-primary">提交</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 留言列表 -->
    <div id="messagesList" class="mt-4">
        <!-- 留言卡片將透過 AJAX 載入 -->
    </div>
</div>
<script>
    $(document).ready(function() {
        // 讀取留言列表
        loadMessages();
    });

    // 讀取留言列表
    function loadMessages() {
        $.ajax({
            url: './api/displayMessage.php',
            type: 'GET',
            success: function(data) {
                $('#messagesList').html(data);
            }
        });
    }

    // 處理新增留言表單
    $('#messageForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var formAction = $(this).attr('action');

        $.ajax({
            url: formAction ? formAction : './api/createMessage.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                alert(data);
                $('#messageModal').modal('hide');
                loadMessages(); // 重新載入留言
                $('#messageForm').attr('action', ''); // 重置表單的 action
            }
        });
    });

    // 處理編輯留言
    function editMessage(id) {
        $.ajax({
            url: './api/getMessage.php',
            type: 'GET',
            data: { id: id },
            success: function(data) {
                var message = JSON.parse(data);

                // 填充表單數據
                $('#name').val(message.name);
                $('#messageNumber').val(message.message_number);
                $('#email').val(message.email);
                $('#phone').val(message.phone);
                $('#content').val(message.content);
                $('#displayEmail').prop('checked', message.display_email); // 假設有這個選項
                $('#displayPhone').prop('checked', message.display_phone); // 假設有這個選項
                $('#messageId').val(message.id);

                // 更新表單的提交 URL
                $('#messageForm').attr('action', './api/editMessage.php');

                // 顯示燈箱
                $('#messageModal').modal('show');
            }
        });
    }


    // 處理刪除留言
    function deleteMessage(id, messageNumber) {
        if(confirm("確定要刪除這條留言嗎？")) {
            $.ajax({
                url: './api/deleteMessage.php',
                type: 'POST',
                data: { id: id, message_number: messageNumber },
                success: function(data) {
                    alert(data);
                    loadMessages(); // 重新載入留言
                }
            });
        }
    }
    function verifyAndEditMessage(id, messageNumber) {
        $.ajax({
            url: './api/verifyMessageNumber.php',
            type: 'POST',
            data: { id: id, message_number: messageNumber },
            success: function(data) {
                if(data === "valid") {
                    editMessage(id);
                } else {
                    alert("留言編號不正確");
                }
            }
        });
    }
    //留言編號驗證
    function verifyAndDeleteMessage(id, messageNumber) {
        $.ajax({
            url: './api/verifyMessageNumber.php',
            type: 'POST',
            data: { id: id, message_number: messageNumber },
            success: function(data) {
                if(data === "valid") {
                    deleteMessage(id, messageNumber);
                } else {
                    alert("留言編號不正確");
                }
            }
        });
    }


    function prepareAddMessage() {
        $('#messageForm')[0].reset(); // 重置表單
        $('#messageForm').attr('action', './api/createMessage.php'); // 設置為新增留言的 URL
        $('#messageId').val(''); // 清空隱藏的 id 欄位
    }
</script>
</body>
</html>