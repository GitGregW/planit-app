<script setup>
    import PlannerLayout from '@/Layouts/PlannerLayout.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { reactive, computed } from 'vue';

    const props = defineProps({
        event: {
            type: Object,
        },
        schedules: {
            type: Object,
        },
    });

    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const dayHeaders = ["Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"];
    const dayCheck = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    const d = new Date();
    
    let calendars = [];
    let j=0;
    for(let i = 0; i < 12; i++){
        let startDay = new Date((d.getFullYear() + j), d.getMonth() + i, 1).getDay();
        calendars[i] = {
            title: months[d.getMonth() + i]  + ' ' +  (d.getFullYear() + j),
            startDay: startDay,
            endDate: new Date ((d.getFullYear() + j), d.getMonth() + i + 1, 0).getDate() + (startDay - 1),
        }
        if(d.getMonth() + i == 11){ j=1; }
    }
    // let calendars = ({
    //     0: {
    //         title: months[d.getMonth()]  + ' ' +  d.getFullYear(),
    //         startDay: new Date(d.getFullYear(), d.getMonth(), 1).getDay(),
    //     },
    //     1: {
    //         title: months[d.getMonth() + 1]  + ' ' +  d.getFullYear(),
    //         startDay: new Date(d.getFullYear(), d.getMonth() + 1, 1).getDay(),
    //     },
    //     2: {
    //         title: months[d.getMonth() + 2]  + ' ' +  d.getFullYear(),
    //         startDay: new Date(d.getFullYear(), d.getMonth() + 2, 1).getDay(),
    //     },
    // });
    // calendars[0].endDate = new Date (d.getFullYear(), d.getMonth(), 0).getDate() + (calendars[0].startDay - 1);
    // calendars[1].endDate = new Date (d.getFullYear(), d.getMonth() + 1, 0).getDate() + (calendars[1].startDay - 1);
    // calendars[2].endDate = new Date (d.getFullYear(), d.getMonth() + 2, 0).getDate() + (calendars[2].startDay - 1);
    const today = d.getDate() + (calendars[0].startDay - 1);

    function eventClosed(calendarItem){
        return !props.schedules[dayCheck[calendarItem - ((Math.floor( calendarItem / 7 ) * 7))]] ? true : false;
    }

    // const form = useForm('CreateEventBooking', {})
    var bookingForm = reactive({
        begins_at: '',
        ends_at: '',
        price: '',
    });

    function getBookingForm(day, monthYear){
        if(document.getElementById('begins_at')){ document.getElementById('begins_at').selectedIndex = 0; }
        bookingForm.begins_at = new Date(day + ' ' + monthYear);
        bookingForm.ends_at = new Date(day + ' ' + monthYear);
    }

    // const setBookingFormDateTime = computed(function(){
    //     const selectedTime = document.getElementById('begins_at').value.split(":");
    //     bookingForm.begins_at.setHours(selectedTime[0],selectedTime[1]);
    // });
    function setBookingFormDateTime(selectedDate){
        const selectedTime = document.getElementById('begins_at').value.split(":");
        bookingForm.begins_at = new Date(selectedDate.getFullYear(),selectedDate.getMonth(),selectedDate.getDate(),selectedTime[0],selectedTime[1]);
        selectedTime[0] = Number.parseInt(selectedTime[0]) + 1;
        bookingForm.ends_at = new Date(selectedDate.getFullYear(),selectedDate.getMonth(),selectedDate.getDate(),selectedTime[0],selectedTime[1]);
    }

    function openDuration(){
        const openTime = props.schedules[dayCheck[bookingForm.begins_at.getDay()]].opening_time.split(":");
        const closeTime = props.schedules[dayCheck[bookingForm.begins_at.getDay()]].closing_time.split(":");

        const openingDate = new Date (bookingForm.begins_at.getFullYear(),bookingForm.begins_at.getMonth(),bookingForm.begins_at.getDate(),
            openTime[0], openTime[1], openTime[2]);
        const closingDate = new Date (bookingForm.begins_at.getFullYear(),bookingForm.begins_at.getMonth(),bookingForm.begins_at.getDate(),
            closeTime[0], closeTime[1], closeTime[2]);

        var i = openingDate.getHours()
        // let timeList = ['-Select-'];
        let timeList = [''];
        for(i; i < closingDate.getHours(); i++){
            timeList.push(i + ":00");
        }
        return timeList;
    }

    function submit() {
        router.post(route('event_bookings.store', props.event), bookingForm);
    }
</script>

<template>
    <Head :title="props.event.title + ' Booking'" />
    <PlannerLayout>
        <div :style="'background-image: url(/event_images/' + props.event['src'] + ');'"
            class="relative bg-cover bg-center w-screen h-36 overflow-clip">
            <div class="absolute -bottom-2 bg-white/80 text-xl font-semibold px-3 py-2">{{ props.event['title'] }}</div>
        </div>

        <div class="text-xl font-bold mx-8 mt-4">Diary</div>

        <div class="flex flex-col flex-wrap gap-3 overflow-x-auto w-screen h-96">
            <div v-for="(calendar, index) in calendars" class="grid grid-cols-7 w-full my-auto [&:nth-child(1)]:ml-10">
                <div class="col-span-7 text-lg font-semibold py-1">{{ calendar.title}} </div>
                <!-- <div class="col-span-2 text-sm text-center px-3 my-auto">prev</div>
                <div class="col-span-2 text-sm text-center px-3 my-auto">next</div> -->
                <div v-for="dayHeader in dayHeaders" class="text-grey-700 py-1 border-y-2 border-yellow-500">{{ dayHeader }}</div>
        
                <div v-for="calendarItem in 42" class="">
                    <div v-if="(index == 0 && calendarItem < today)
                        || (calendarItem < calendar.startDay || calendarItem >= calendar.endDate)"
                        :id="'square' + index + calendarItem"
                        class="h-10 bg-gray-300 row-span-2"></div>
                    <div v-else-if="eventClosed(calendarItem)" class="grid grid-rows-2 h-10 bg-gray-200">
                        <span class="text-center my-auto">{{ calendarItem - calendar.startDay + 1 }}</span>
                        <span class="text-sm text-gray-800 font-semibold italic">Closed</span>
                    </div>
                    <div v-else @click="getBookingForm(calendarItem - calendar.startDay + 1, calendar.title)"
                        class="h-10 text-center my-auto">
                        {{ calendarItem - calendar.startDay + 1 }}
                    </div>
                </div>
            </div>
        </div>

        <div v-if="bookingForm.begins_at" class="px-12">
            <div class="text-xl font-bold">
                {{ bookingForm.begins_at.getDate() + " " + months[bookingForm.begins_at.getMonth()] + " " + bookingForm.begins_at.getFullYear() }}
                <span class="text-lg text-800-gray px-3"> Availability</span>
            </div>
            <form @submit.prevent="submit">
                <label for="begins_at" class="mr-3">Book Time Slot</label>
                <select v-on:change="setBookingFormDateTime(bookingForm.begins_at)" id="begins_at" class="my-2" @keydown.prevent>
                    <option v-for="duration in openDuration()" datatype="time" :value="duration">
                        {{ duration }}
                    </option>
                </select>
                <button type="submit" class="text-lg font-semibold ml-3 py-1 px-3 bg-white border border-gray-700">Confirm Booking</button>
            </form>
        </div>
    </PlannerLayout>
</template>