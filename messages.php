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
                        <!-- 編輯選項 -->
                        <div id="editOptions" style="display: none;">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="displayEmail" name="display_email" checked>
                                <label class="form-check-label" for="displayEmail">Email</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="displayPhone" name="display_phone" checked>
                                <label class="form-check-label" for="displayPhone">電話</label>
                            </div>
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
        // 加载留言列表
        loadMessages();
    });
    // 加载留言列表
    function loadMessages() {
        $.ajax({
            url: './api/displayMessage.php',
            type: 'GET',
            success: function(data) {
                $('#messagesList').html(data);
            }
        });
    }

    // 处理新增留言表单提交
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
                loadMessages(); // 重新加载留言
                $('#messageForm').attr('action', ''); // 重置表单的 action
            }
        });
    });

    // 验证并编辑留言
    function verifyAndEditMessage(id) {
        var messageNumber = prompt("请输入留言编号以进行验证:");
        if (messageNumber) {
            $.ajax({
                url: './api/verifyMessageNumber.php',
                type: 'POST',
                data: { id: id, message_number: messageNumber },
                success: function(data) {
                    if(data === "valid") {
                        editMessage(id); // 验证通过后，调用 editMessage 函数
                    } else {
                        alert("留言编号不正确");
                    }
                },
                error: function() {
                    alert("验证时发生错误");
                }
            });
        }
    }

    // 编辑留言
    function editMessage(id) {
        $.ajax({
            url: './api/getMessage.php',
            type: 'GET',
            data: { id: id },
            success: function(data) {
                var message = JSON.parse(data);

                // 填充表单数据
                $('#name').val(message.name);
                $('#messageNumber').val(message.message_number);
                $('#email').val(message.email);
                $('#phone').val(message.phone);
                $('#content').val(message.content);

                // 设置复选框默认为选中状态
                $('#displayEmail').prop('checked', message.display_email === 1);
                $('#displayPhone').prop('checked', message.display_phone === 1);

                $('#messageId').val(message.id);

                // 显示编辑选项
                $('#editOptions').show();

                // 更新表单的提交 URL
                $('#messageForm').attr('action', './api/editMessage.php');
                $('#messageModal').modal('show'); // 显示灯箱
            },
            error: function() {
                alert("加载留言时发生错误");
            }
        });
    }

    // 处理删除留言
    function verifyAndDeleteMessage(id) {
        var messageNumber = prompt("请输入留言编号以进行验证:");
        if (messageNumber) {
            $.ajax({
                url: './api/verifyMessageNumber.php',
                type: 'POST',
                data: { id: id, message_number: messageNumber },
                success: function(data) {
                    if(data === "valid") {
                        deleteMessage(id); // 验证通过后，调用 deleteMessage 函数
                    } else {
                        alert("留言编号不正确");
                    }
                },
                error: function() {
                    alert("验证时发生错误");
                }
            });
        }
    }

    // 删除留言
    function deleteMessage(id) {
        $.ajax({
            url: './api/deleteMessage.php',
            type: 'POST',
            data: { id: id },
            success: function(data) {
                alert(data); // 显示从后端返回的消息
                loadMessages(); // 重新加载留言
            },
            error: function() {
                alert("删除留言时发生错误");
            }
        });
    }


    // 准备新增留言
    function prepareAddMessage() {
        $('#messageForm')[0].reset(); // 重置表单
        $('#messageForm').attr('action', './api/createMessage.php'); // 设置为新增留言的 URL
        $('#messageId').val(''); // 清空隐藏的 id 字段
        $('#editOptions').hide(); // 隐藏编辑选项
    }
</script>
</body>
</html>