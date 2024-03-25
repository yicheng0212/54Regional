<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>訪客訂房</title>
    <?php include "link.php";?>
</head>
<body class="bg-warning">
<div id="app" class="container">
    <?php include "header.php";?>
    <div class="card p-3 shadow bg-light">
        <div v-if="step === 1">
            <h3>選擇日期</h3>
            <label for="startDate" class="form-label">入住日期:</label>
            <input type="date" id="startDate" class="form-control" v-model="selectedDates[0]" @change="updateDateRange">
            <label for="endDate" class="form-label">退房日期:</label>
            <input type="date" id="endDate" class="form-control" v-model="selectedDates[1]" @change="updateDateRange">
            <button class="btn btn-primary mt-3" @click="gotoStep(2)">下一步</button>
        </div>

        <div v-if="step === 2">
            <h3>選擇房間數量</h3>
            <div class="mt-3">
                <label for="roomCount" class="form-label">房間數量:</label>
                <select id="roomCount" class="form-select" v-model="selectedRoomCount">
                    <option v-for="count in maxRoomCount" :value="count">{{ count }}</option>
                </select>
            </div>


            <h3>選擇房間</h3>
            <button class="btn btn-secondary mt-3" @click="autoSelectRooms">自動選擇房間</button>
            <div v-if="availableRooms.length" class="d-flex flex-wrap">
                <div v-for="room in availableRooms" :key="room.roomNumber" class="card m-2" style="width: 18rem;"
                     :class="{
                'bg-success': room.selected && room.available,
                'bg-danger': !room.available,
                'bg-light': !room.selected && room.available
             }">
                    <div class="card-body" @click="room.available && toggleRoomSelection(room)">
                        <h5 class="card-title">{{ room.roomNumber }}</h5>
                        <p class="card-text">{{ room.available ? '可預訂' : '已訂' }}</p>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary mt-3" @click="gotoStep(3)">下一步</button>
        </div>



        <div v-if="step === 3">
            <h3>確認訂房資料</h3>
            <p>您選擇了 {{ selectedRooms.length }} 間房</p>
            <p>房間號碼: {{ selectedRoomNumbers }}</p>
            <p>入住日期: {{ selectedDates[0] }}</p>
            <p>退房日期: {{ selectedDates[1] }}</p>
            <p>總價: {{ totalPrice }}元</p>
            <p>定金: {{ deposit }}元（總價30%） </p>
            <button class="btn btn-primary" @click="gotoStep(4)">填寫聯絡資訊</button>
        </div>

        <div v-if="step === 4">
            <h3>填寫聯絡資訊</h3>
            <input type="text" v-model="name" placeholder="姓名" class="form-control mb-2" required>
            <input type="email" v-model="email" placeholder="email" class="form-control mb-2" required>
            <input type="tel" v-model="phone" placeholder="電話" class="form-control mb-2" required>
            <textarea v-model="remarks" placeholder="備註" class="form-control mb-2"></textarea>
            <button class="btn btn-success" @click="submitBooking">確認預定</button>
        </div>
    </div>
</div>

<script>
    const { createApp, ref, reactive, toRefs, computed} = Vue;


    createApp({
        setup() {
            const step = ref(1);
            const selectedDates = ref([]);
            const availableRooms = ref([]);
            const selectedRoom = ref('');
            const name = ref('');
            const email = ref('');
            const phone = ref('');
            const remarks = ref('');
            const totalPrice = ref(0);
            const deposit = ref(0);
            const selectedRoomCount = ref(1);
            const maxRoomCount = ref(0);
            const pricePerNight = 5000;

            const selectedRooms = computed(() => availableRooms.value.filter(room => room.selected));

            const selectedRoomNumbers = computed(() =>
                selectedRooms.value.map(room => `${room.roomNumber}`).join(", ")
            );

            const gotoStep = newStep => { step.value = newStep; };

            const autoSelectRooms = () => {
                let count = 0;
                availableRooms.value.forEach(room => {
                    room.selected = room.available && count < selectedRoomCount.value;
                    if (room.selected) count++;
                });
            };

            const toggleRoomSelection = room => {
                if (room.available) room.selected = !room.selected;
            };

            const updateDateRange = () => {
                if (selectedDates.value[0] && selectedDates.value[1]) {
                    fetchAvailableRooms(selectedDates.value[0], selectedDates.value[1]);
                    calculateTotalPrice();
                }
            };

            const fetchAvailableRooms = (startDate, endDate) => {
                $.ajax({
                    url: './api/getAvailableRooms.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ checkInDate: startDate, checkOutDate: endDate }),
                    success: response => {
                        availableRooms.value = response.rooms ? response.rooms.map(room => ({
                            roomNumber: room.roomNumber,
                            available: room.available
                        })) : [];
                        maxRoomCount.value = availableRooms.value.filter(room => room.available).length;
                    }
                });
            };
            const calculateTotalPrice = () => {
                const start = new Date(selectedDates.value[0]);
                const end = new Date(selectedDates.value[1]);
                const diffDays = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                totalPrice.value = diffDays * pricePerNight * selectedRooms.value.length;
                deposit.value = totalPrice.value * 0.3;
            };

            const submitBooking = () => {
                const bookingData = {
                    selectedRooms: selectedRoomNumbers.value,
                    selectedDates: selectedDates.value,
                    name: name.value,
                    email: email.value,
                    phone: phone.value,
                    remarks: remarks.value,
                    totalPrice: totalPrice.value,
                    deposit: deposit.value,
                };

                $.ajax({
                    url: './api/createBooking.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(bookingData),
                    success: function(response) {
                        alert(response.error ? `預定失敗: ${response.error}` : response.message);
                        if (!response.error && response.success) {
                            location.href = "index.php";
                        }
                    }
                });
            };

            return {
                step,
                selectedDates,
                availableRooms,
                selectedRoom,
                name,
                email,
                phone,
                remarks,
                totalPrice,
                deposit,
                selectedRoomCount,
                maxRoomCount,
                selectedRooms,
                selectedRoomNumbers,
                autoSelectRooms,
                toggleRoomSelection,
                updateDateRange,
                fetchAvailableRooms,
                submitBooking,
                gotoStep,
            };
        },
    }).mount('#app');
</script>
</body>
</html>