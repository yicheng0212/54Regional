<div id="app">

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
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" v-model="formData.displayEmail" checked>
                                <label class="form-check-label" for="displayEmail">顯示Email</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" v-model="formData.displayPhone" checked>
                                <label class="form-check-label" for="displayPhone">顯示電話</label>
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
                <div v-if="message.is_top == 1" class="badge badge-success">置頂</div>
                <p class='card-text'>留言內容: {{ message.content }}</p>
                <img v-if="message.image_path" :src="message.image_path" class="img-fluid" alt="留言圖片">
                <p class='card-text'>
                    <small class='text-muted'>
                        發布於 {{ message.created_at }}
                        <span v-if="message.updated_at && message.updated_at !== message.created_at">，編輯於 {{ message.updated_at }}</span>
                    </small>
                </p>
                <p v-if="message.displayEmail == 1" class='card-text'>Email: {{ message.email }}</p>
                <p v-if="message.displayPhone == 1" class='card-text'>電話: {{ message.phone }}</p>

                <button @click="prepareEditOrDelete(message, 'edit')" class='btn btn-primary m-1'>編輯</button>
                <button @click="prepareEditOrDelete(message, 'delete')" class='btn btn-danger m-1'>刪除</button>
                <button @click="topMessage(message.id)" class='btn btn-warning m-1'>置頂</button>
            </div>
            <div class='card-body bg-light' v-else>
                <h4 class='card-title'>{{ message.name }}</h4>
                <p class='card-text'>{{ message.content }}</p>
                <button @click="prepareEditOrDelete(message, 'delete')" class='btn btn-danger m-1'>刪除</button>
                <p class='card-text'><small class='text-muted'>發布於 {{ message.created_at }}，刪除於 {{ message.deleted_at }}</small></p>
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
                        displayEmail: false,
                        displayPhone: false,
                        image: null,
                    },
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
                    });
                },
                prepareEditOrDelete(message, action) {
                    if (action === 'edit') {
                        this.formData = {
                            ...message
                        };
                        $('#messageModal').modal('show');
                    } else if (action === 'delete') {
                        this.deleteMessage(message.id);
                    }
                },
                deleteMessage(id) {
                    if (confirm('確定要刪除這條留言嗎？')) {
                        $.ajax({
                            url: './api/deleteMessage.php',
                            type: 'POST',
                            data: { id },
                            success: () => {
                                alert('留言已刪除');
                                this.loadMessages();
                            },
                        });
                    }
                },
                topMessage(id) {
                    $.ajax({
                        url: './api/topMessage.php',
                        type: 'POST',
                        data: { id },
                        success: () => {
                            alert('留言已置頂');
                            this.loadMessages();
                        },
                    });
                },
                handleSubmit() {
                    const data = {
                        ...this.formData,
                        image: this.formData.image ? this.formData.image : ''
                    };
                    $.ajax({
                        url: './api/editMessage.php',
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            alert('留言已提交');
                            $('#messageModal').modal('hide');
                            this.loadMessages();
                        }.bind(this)
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
                        displayEmail: false,
                        displayPhone: false,
                        image: null,
                    };
                },
                handleFileUpload(e) {
                    this.formData.image = e.target.files[0]?.name || '';
                },
            },
            mounted() {
                this.loadMessages();
            }
        }).mount('#app');
    </script>