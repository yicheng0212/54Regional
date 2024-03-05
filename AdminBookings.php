<div id="app">
    <div class="table-responsive">
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th>訂房編號</th>
                <th>房間編號</th>
                <th>姓名</th>
                <th>電話</th>
                <th>E-mail</th>
                <th>入住期間</th>
                <th>備註</th>
                <th>總金額</th>
                <th>需付訂金</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="booking in bookings" :key="booking.bookingNumber">
                <td>{{ booking.bookingNumber }}</td>
                <td>{{ booking.roomNumber }}</td>
                <td>{{ booking.name }}</td>
                <td>{{ booking.phone }}</td>
                <td>{{ booking.email }}</td>
                <td>{{ booking.checkInDate }}～<br>{{ booking.checkOutDate }}</td>
                <td>{{ booking.remarks }}</td>
                <td>{{ booking.totalPrice }}</td>
                <td>{{ booking.deposit }}</td>
                <td>
                    <button class="btn btn-outline-secondary btn-sm m-1" @click="showEditModal(booking)">編輯</button>
                    <button class="btn btn-outline-danger btn-sm m-1" @click="deleteBooking(booking.id)">刪除</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- 編輯訂房訂單模態框 -->
    <div class="modal fade" id="editBookingModal" tabindex="-1" aria-labelledby="editBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBookingModalLabel">編輯訂房訊息</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- 编辑表单 -->
                    <form>
                        <input type="hidden" v-model="editingBooking.id">
                        <div class="form-group">
                            <label>訂房編號</label>
                            <input type="text" class="form-control" v-model="editingBooking.bookingNumber" readonly>
                        </div>
                        <div class="form-group">
                            <label for="edit-check-in-date">入住第一晚的日期</label>
                            <input type="date" class="form-control" id="edit-check-in-date" v-model="editingBooking.checkInDate">
                        </div>
                        <div class="form-group">
                            <label for="edit-check-out-date">入住最後一晚的日期</label>
                            <input type="date" class="form-control" id="edit-check-out-date" v-model="editingBooking.checkOutDate">
                        </div>
                        <div class="form-group">
                            <label>房間編號</label>
                            <input type="text" class="form-control" v-model="editingBooking.roomNumber">
                        </div>
                        <div class="form-group">
                            <label>姓名</label>
                            <input type="text" class="form-control" v-model="editingBooking.name">
                        </div>
                        <div class="form-group">
                            <label>電話</label>
                            <input type="text" class="form-control" v-model="editingBooking.phone">
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="email" class="form-control" v-model="editingBooking.email">
                        </div>
                        <div class="form-group">
                            <label>備註</label>
                            <textarea class="form-control" v-model="editingBooking.remarks"></textarea>
                        </div>
                        <div class="form-group">
                                <label>總金額</label>
                                <input type="number" class="form-control" v-model="editingBooking.totalPrice" readonly>
                            </div>
                            <div class="form-group">
                                <label>需付訂金</label>
                                <input type="number" class="form-control" v-model="editingBooking.deposit" readonly>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                    <button type="button" class="btn btn-primary" @click="saveBooking">保存更改</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const { createApp } = Vue;

    createApp({
        data() {
            return {
                bookings: [],
                editingBooking: {
                    id: '',
                    bookingNumber: '',
                    checkInDate: '',
                    checkOutDate: '',
                    roomNumber: '',
                    name: '',
                    phone: '',
                    email: '',
                    remarks: '',
                    totalPrice: 0,
                    deposit: 0,
                },
            };
        },
        mounted() {
            this.fetchBookings();
        },
        methods: {
            fetchBookings() {
                $.ajax({
                    url: './api/getAllBookings.php',
                    method: 'GET',
                    success: (data) => {
                        this.bookings = data;
                    },
                    error: (error) => {
                        console.error("Error fetching bookings:", error);
                    }
                });
            },
            showEditModal(booking) {
                this.editingBooking = { ...booking };
                $('#editBookingModal').modal('show');
            },
            saveBooking() {
                $.ajax({
                    url: './api/updateBooking.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(this.editingBooking),
                    success: (response) => {
                        $('#editBookingModal').modal('hide');
                        this.fetchBookings();
                        alert('訂單更新成功！');
                    },
                    error: (error) => {
                        console.error("Error updating booking:", error);
                        alert('訂單更新失敗！');
                    }
                });
            },

            deleteBooking(id) {
                const confirmed = confirm('確定要刪除這個訂單嗎？');
                if (confirmed) {
                    $.ajax({
                        url: './api/deleteBooking.php',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({ id }),
                        success: (response) => {
                            this.fetchBookings();
                            alert('訂單刪除成功！');
                        },
                        error: (error) => {
                            console.error("Error deleting booking:", error);
                            alert('訂單刪除失敗！');
                        }
                    });
                }
            }
        }
    }).mount('#app');
</script>