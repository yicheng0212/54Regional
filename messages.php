<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>訪客留言</title>
    <?php include_once "link.php";?>
</head>
<body class="bg-warning">
<div id="app" class="container">
    <?php include_once "header.php"; ?>

    <div class="d-flex align-items-center justify-content-center">
        <h2 class="mb-0">訪客留言列表</h2>
        <button type="button" class="btn btn-primary ml-2" @click="showAddMessageModal">
            新增留言
        </button>
    </div>

    <!-- Modal for Adding and Editing Messages -->
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
                    <form @submit.prevent="handleSubmit">
                        <input type="hidden" v-model="formData.id">
                        <div class="form-group">
                            <label for="name">姓名:</label>
                            <input type="text" class="form-control" v-model="formData.name" required>
                        </div>
                        <div class="form-group">
                            <label for="messageNumber">留言編號 (4位數字):</label>
                            <input type="text" class="form-control" v-model="formData.messageNumber" pattern="\d{4}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" v-model="formData.email">
                        </div>
                        <div class="form-group">
                            <label for="phone">聯絡電話:</label>
                            <input type="number" class="form-control" v-model="formData.phone">
                        </div>
                        <div class="form-group">
                            <label for="content">留言內容:</label>
                            <textarea class="form-control" v-model="formData.content" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">圖片上傳:</label>
                            <input type="file" class="form-control-file" ref="image" @change="handleFileUpload">
                        </div>
                        <div id="editOptions" v-if="editMode">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" v-model="formData.displayEmail">
                                <label class="form-check-label" for="displayEmail">顯示Email</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" v-model="formData.displayPhone">
                                <label class="form-check-label" for="displayPhone">顯示電話</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">提交</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Message List -->
    <div id="messageList" class="mt-4">
        <div class='card mb-3' v-for="message in messages" :key="message.id">
            <div class='card-body bg-light' v-if="message.deleted_at === null">
                <h4 class='card-title'>{{ message.name }}</h4>
                <div v-if="message.is_top == 1" class="badge badge-success">置顶</div>
                <p class='card-text'>留言内容: {{ message.content }}</p>
                <img v-if="message.image_path" :src="message.image_path" class="img-fluid" alt="留言图片">
                <p class='card-text'>
                    <small class='text-muted'>
                        发布于 {{ message.created_at }}
                        <span v-if="message.updated_at && message.updated_at != message.created_at">，编辑于 {{ message.updated_at }}</span>
                    </small>
                </p>
                <p v-if="message.displayEmail == 1" class='card-text'>Email: {{ message.email }}</p>
                <p v-if="message.displayPhone == 1" class='card-text'>电话: {{ message.phone }}</p>
                <button @click="prepareEditOrDelete(message, 'edit')" class='btn btn-primary m-1'>编辑</button>
                <button @click="prepareEditOrDelete(message, 'delete')" class='btn btn-danger m-1'>删除</button>
            </div>
            <div class='card-body bg-light' v-else>
                <h4 class='card-title'>{{ message.name }}</h4>
                <p class='card-text'>此留言已被删除。</p>
                <p class='card-text'>
                    <small class='text-muted'>
                        发布于 {{ message.created_at }}，删除于 {{ message.deleted_at }}
                    </small>
                </p>
            </div>
        </div>
    </div>


    <script>
        Vue.createApp({
            data() {
                return {
                    messages: [],
                    formData: {
                        id: '',
                        name: '',
                        messageNumber: '',
                        email: '',
                        phone: '',
                        content: '',
                        image: null,
                        displayEmail: false,
                        displayPhone: false,
                    },
                    editMode: false,
                };
            },
            methods: {
                loadMessages() {
                    $.ajax({
                        url: './api/displayMessage.php',
                        type: 'GET',
                        dataType: 'json',
                        success: (messages) => {
                            this.messages = messages;
                        },
                        error: () => {
                            alert('无法加载留言');
                        }
                    });
                },
                showAddMessageModal() {
                    this.editMode = false;
                    this.resetForm();
                    $('#messageModal').modal('show');
                },
                prepareEditOrDelete(message, action) {
                    const messageNumber = prompt("请输入留言编号以进行验证:");
                    if (messageNumber) {
                        $.ajax({
                            url: './api/verifyMessageNumber.php',
                            type: 'POST',
                            data: { id: message.id, messageNumber: messageNumber },
                            success: (response) => {
                                if (response.status === "valid") {
                                    if (action === 'edit') {
                                        this.fetchMessageDetails(response.id);
                                    } else if (action === 'delete') {
                                        this.deleteMessage(response.id);
                                    }
                                } else {
                                    alert("留言编号不正确");
                                }
                            },
                            error: () => {
                                alert('验证留言编号时发生错误');
                            }
                        });
                    }
                },
                fetchMessageDetails(id) {
                    $.ajax({
                        url: `./api/getMessage.php?id=${id}`,
                        type: 'GET',
                        success: (message) => {
                            this.editMode = true;
                            this.formData = { ...message, image: null, displayEmail: message.display_email === '1', displayPhone: message.display_phone === '1' };
                            $('#messageModal').modal('show');
                        },
                        error: () => {
                            alert('加载留言详情时发生错误');
                        }
                    });
                },
                deleteMessage(id) {
                    $.ajax({
                        url: './api/editMessage.php',
                        type: 'POST',
                        data: { id: id, delete: '1' },
                        success: () => {
                            alert('留言已删除');
                            this.loadMessages();
                        },
                        error: () => {
                            alert('删除留言时发生错误');
                        }
                    });
                },
                handleSubmit() {
                    const data = {
                        ...this.formData,
                        image: this.formData.image ? this.formData.image : ''
                    };

                    const url = this.editMode ? './api/editMessage.php' : './api/createMessage.php';

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            alert('留言已提交');
                            $('#messageModal').modal('hide');
                            this.loadMessages();
                        }.bind(this),
                        error: function() {
                            alert('提交留言时发生错误');
                        }
                    });
                },
                resetForm() {
                    this.formData = {
                        id: '',
                        name: '',
                        messageNumber: '',
                        email: '',
                        phone: '',
                        content: '',
                        image: null,
                        displayEmail: false,
                        displayPhone: false,
                    };
                },
                handleFileUpload(event) {
                    this.formData.image = event.target.files.length > 0 ? event.target.files[0].name : '';
                },
            },
            mounted() {
                this.loadMessages();
            },
        }).mount('#app');
    </script>
</body>
</html>