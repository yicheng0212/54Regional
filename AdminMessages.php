<div class="container mt-4">
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
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="displayEmail" name="display_email">
                                <label class="form-check-label" for="displayEmail">Email</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="displayPhone" name="display_phone">
                                <label class="form-check-label" for="displayPhone">電話</label>
                            </div>
                        <div class="form-group">
                            <label for="admin_response">留言回覆:</label>
                            <textarea class="form-control" id="admin_response" name="admin_response" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">提交</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="messageList" class="mt-4">
        <!-- 留言卡片將透過 AJAX 載入 -->
    </div>
</div>
<script>
    //加載留言
    $(document).ready(function() {
        loadMessages();
    });
    //顯示留言
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
                            html += `<span class="badge badge-success">置頂</span> `;
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

                        html += `<button onclick='editMessage(${message.id})' class='btn btn-primary mt-3'>编辑</button>`;
                        html += `<button onclick='topMessage(${message.id})' class='btn btn-success mt-3'>置頂</button>`;
                        html += `<button onclick='deleteMessage(${message.id})' class='btn btn-danger mt-3'>刪除</button>`;
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
                $('#admin_response').val(message.admin_response);

                // 显示模态框进行编辑
                $('#messageModal').modal('show');
            },
            error: function() {
                alert("加载留言时发生错误");
            }
        });
    }

    $(document).ready(function() {
        // 处理编辑留言表单提交
        $('#messageForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: './api/editMessage.php', // 直接指定编辑操作的URL
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert(response); // 显示从后端返回的消息
                    $('#messageModal').modal('hide'); // 隐藏模态框
                    loadMessages(); // 重新加载留言
                },
                error: function() {
                    alert("编辑留言时发生错误");
                }
            });
        });
    });

    // 删除留言的函数
    function deleteMessage(id) {
        if (confirm("確定要刪除這條留言嗎？")) {
            $.ajax({
                url: './api/deleteMessage.php', // 指向您的删除脚本的路径
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.message) {
                        alert(result.message); // 显示成功消息
                    } else if (result.error) {
                        alert(result.error); // 显示错误消息
                    }
                    loadMessages(); // 重新加载留言列表
                },
                error: function() {
                    alert("请求失败，请稍后再试。");
                }
            });
        }
    }

    function topMessage(id) {
        $.ajax({
            url: './api/topMessage.php',
            type: 'POST',
            data: {
                id: id,
                action: 'top'
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.message) {
                    alert(result.message);
                    loadMessages();
                } else if (result.error) {
                    alert(result.error);
                }
            },
            error: function() {
                alert("置頂留言請求失敗，請稍後再試。");
            }
        });
    }
</script>