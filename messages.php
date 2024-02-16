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
    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal">留言</h5>
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
                            <input type="text" class="form-control" id="messageNumber" name="messageNumber" pattern="\d{4}" required>
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
                                <input type="checkbox" class="form-check-input" id="displayEmail" name="display_email">
                                <label class="form-check-label" for="displayEmail">Email</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="displayPhone" name="display_phone">
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
    <div id="messageList" class="mt-4">
        <!-- 留言卡片將透過 AJAX 載入 -->
    </div>
</div>
<script>
    $(document).ready(function() {
        loadMessages();
    });
    function loadMessages() {
        $.ajax({
            url: './api/displayMessage.php',
            type: 'GET',
            dataType: 'json',
            success: function(messages) {
                let html = '';
                messages.forEach(function(message) {
                    html += `<div class='card mb-3'><div class='card-body'>`;
                    if (message.deleted_at === null) {
                        // 正常顯示留言
                        html += `<h5 class='card-title'>${message.name}</h5>`;
                        if(message.is_top === "1") {
                            html += `<span class="badge badge-success">置顶</span> `;
                        }
                        html += `<p class='card-text'>留言內容:${message.content.replace(/\n/g, "<br>")}</p>`;

                        if (message.image_path) {
                            html += `<img src='${message.image_path}' class='card-img-top mb-3' style='max-width: 300px; height: auto;' alt='圖片'>`;
                        }

                        if (message.display_email === "1") {
                            html += `<p class='card-text'>Email: ${message.email}</p>`;
                        }
                        if (message.display_phone === "1") {
                            html += `<p class='card-text'>電話: ${message.phone}</p>`;
                        }

                        html += `<p class='card-text'><small class='text-muted'>發佈於 ${message.created_at}`;
                        if (message.updated_at !== message.created_at) {
                            html += `，修改於 ${message.updated_at}`;
                        }
                        html += `</small></p>`;

                        // 檢查並顯示管理者回覆
                        if (message.admin_response) {
                            html += `<div class='alert alert-secondary mt-3'>管理者回覆: ${message.admin_response}</div>`;
                        }

                        html += `<button onclick='verifyAndEditMessage(${message.id})' class='btn btn-primary'>編輯</button> `;
                        html += `<button onclick='verifyAndDeleteMessage(${message.id})' class='btn btn-danger'>刪除</button>`;
                    } else {
                        // 顯示已刪除的留言
                        html += `<h5 class='card-title'>${message.name}</h5>`;
                        html += `<p class='card-text'>此留言已被刪除。</p>`;
                        html += `<p class='card-text'><small class='text-muted'>發佈於 ${message.created_at}，刪除於 ${message.deleted_at}</small></p>`;
                    }
                    html += `</div></div>`;
                });
                $('#messageList').html(html);
            },
            error: function() {
                alert('無法加載留言');
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
                data: { id: id, messageNumber: messageNumber },
                success: function(response) {
                    var data = JSON.parse(response);
                    if(data.status === "valid") {
                        editMessage(data.id); // 使用从后端获取的 id
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
                $('#messageNumber').val(message.messageNumber);
                $('#email').val(message.email);
                $('#phone').val(message.phone);
                $('#content').val(message.content);


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
                data: { id: id, messageNumber: messageNumber },
                success: function(response) {
                    var data = JSON.parse(response);
                    if(data.status === "valid") {
                        deleteMessage(data.id); // 使用从后端获取的 id
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
            url: './api/editMessage.php',
            type: 'POST',
            data: { id: id, delete: 1 },  // 添加 delete 字段
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