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
            <Calendar @update-date-range="updateDateRange"></Calendar>
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
            <div v-else>
                <p>房間已滿</p>
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
    document.addEventListener('DOMContentLoaded', () => {
        const { createApp, ref, reactive, toRefs, computed, watch } = Vue;

        const Calendar = {
            emits: ['updateDateRange'],
            setup(props, { emit }) {
                const state = reactive({
                    currentYear: new Date().getFullYear(),
                    currentMonth: new Date().getMonth(),
                    days: [],
                    dateRanges: [],
                });

                const today = new Date();
                today.setHours(0, 0, 0, 0);

                const calculateDays = () => {
                    state.days = [];
                    const firstDayOfMonth = new Date(state.currentYear, state.currentMonth, 1).getDay();
                    const daysInMonth = new Date(state.currentYear, state.currentMonth + 1, 0).getDate();
                    const emptyStartDays = firstDayOfMonth === 0 ? 6 : firstDayOfMonth - 1;

                    for (let i = 0; i < emptyStartDays; i++) {
                        state.days.push({ day: null, date: null });
                    }

                    for (let day = 1; day <= daysInMonth; day++) {
                        const date = new Date(state.currentYear, state.currentMonth, day);
                        const dateString = `${state.currentYear}-${state.currentMonth + 1}-${day}`;
                        const isSelected = state.dateRanges.some(range => date >= new Date(range.startDate) && date <= new Date(range.endDate));
                        state.days.push({
                            day,
                            date: dateString,
                            isSelectable: date >= today,
                            isSelected,
                        });
                    }

                    const totalCells = emptyStartDays + daysInMonth;
                    const emptyEndDays = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);

                    for (let i = 0; i < emptyEndDays; i++) {
                        state.days.push({ day: null, date: null });
                    }
                };

                const selectDate = (date) => {
                    if (state.dateRanges.length === 0 || state.dateRanges[state.dateRanges.length - 1].endDate) {
                        state.dateRanges.push({ startDate: date, endDate: null });
                    } else {
                        const currentRange = state.dateRanges[state.dateRanges.length - 1];
                        if (new Date(date) < new Date(currentRange.startDate)) {
                            currentRange.endDate = currentRange.startDate;
                            currentRange.startDate = date;
                        } else {
                            currentRange.endDate = date;
                        }
                        emit('updateDateRange', { startDate: currentRange.startDate, endDate: currentRange.endDate });
                    }
                    calculateDays();
                };

                const changeMonth = (direction) => {
                    if (direction === 'prev') {
                        state.currentMonth = state.currentMonth === 0 ? 11 : state.currentMonth - 1;
                        state.currentYear = state.currentMonth === 11 ? state.currentYear - 1 : state.currentYear;
                    } else if (direction === 'next') {
                        state.currentMonth = state.currentMonth === 11 ? 0 : state.currentMonth + 1;
                        state.currentYear = state.currentMonth === 0 ? state.currentYear + 1 : state.currentYear;
                    }
                    calculateDays();
                };

                calculateDays();

                return { ...toRefs(state), selectDate, changeMonth };
            },
            template: `
<div>
    <div class="mb-3 text-center">
        <button @click="changeMonth('prev')" class="btn btn-info">&lt; 上個月</button>
        <span class="mx-2">{{ currentYear }}年 {{ currentMonth + 1 }}月</span>
        <button @click="changeMonth('next')" class="btn btn-info">下個月 &gt;</button>
    </div>
    <div class="row text-center">
        <div class="col-12">
            <div class="d-flex flex-wrap border-bottom">
                <div class="p-2 flex-fill" style="width: 14.28%;">日</div>
                <div class="p-2 flex-fill" style="width: 14.28%;">一</div>
                <div class="p-2 flex-fill" style="width: 14.28%;">二</div>
                <div class="p-2 flex-fill" style="width: 14.28%;">三</div>
                <div class="p-2 flex-fill" style="width: 14.28%;">四</div>
                <div class="p-2 flex-fill" style="width: 14.28%;">五</div>
                <div class="p-2 flex-fill" style="width: 14.28%;">六</div>
            </div>
            <div class="d-flex flex-wrap">
                <div v-for="day in days" :key="day.date" class="p-2 flex-fill" style="width: 14.28%;">
                    <button v-if="day.day" @click="day.isSelectable ? selectDate(day.date) : null" class="btn w-100" :class="{ 'btn-primary': day.isSelected, 'btn-outline-secondary': !day.isSelected, 'btn-disabled': !day.isSelectable }" :disabled="!day.isSelectable">
                        {{ day.day }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
`,
        };

        createApp({
            components: { Calendar },
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
                    availableRooms.value.forEach(room => room.selected = false);
                    availableRooms.value.filter(room => room.available)
                        .slice(0, selectedRoomCount.value)
                        .forEach(room => room.selected = true);
                };

                const toggleRoomSelection = room => {
                    if (room.available) room.selected = !room.selected;
                };

                const updateDateRange = ({ startDate, endDate }) => {
                    if (startDate && endDate) {
                        selectedDates.value = [startDate, endDate];
                        fetchAvailableRooms(startDate, endDate);
                        calculateTotalPrice();
                    }
                };

                const fetchAvailableRooms = (startDate, endDate) => {
                    $.ajax({
                        url: './api/getAvailableRooms.php',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({ checkInDate: startDate, checkOutDate: endDate }),
                        success: function(response) {
                            if (response.rooms && response.rooms.length > 0) {
                                availableRooms.value = response.rooms.map(room => ({
                                    roomNumber: room.roomNumber,
                                    available: room.available
                                }));
                                maxRoomCount.value = availableRooms.value.filter(room => room.available).length;
                            } else {
                                availableRooms.value = [];
                                maxRoomCount.value = 0;
                            }
                        },
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
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(bookingData),
                        success: (response) => {
                            alert(response.error ? `預定失敗: ${response.error}` : response.message);
                            if (!response.error && response.success) location.href = "index.php";
                        },
                    });
                };

                watch(selectedDates, (newDates) => {
                    if (newDates[0] && newDates[1]) {
                        calculateTotalPrice();
                    }
                });

                watch([selectedDates, selectedRooms], () => {
                    if (selectedDates.value[0] && selectedDates.value[1] && selectedRooms.value.length > 0) {
                        calculateTotalPrice();
                    }
                }, { deep: true });

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
    });
</script>
</body>
</html>