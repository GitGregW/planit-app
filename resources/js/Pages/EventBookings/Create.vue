<script setup>
    import PlannerLayout from '@/Layouts/PlannerLayout.vue';
    import Calendar from '@/Components/Calendar/Calendar.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { reactive, computed } from 'vue';

    const props = defineProps({
        event: {
            type: Object,
        },
        schedules: {
            type: Object,
        },
        monthlyEventBookings: {
            type: Object,
        }
    });

    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    const daysOpen = [];

    for(var i = 0; i < days.length; i++){
        if(props.schedules[days[i]]){
            daysOpen[days[i]] = 1;
        }
    }

    var bookingForm = reactive({
        begins_at: '',
        ends_at: '',
        price: '',
    });

    function getCalendarMonths(){
        const d = new Date();
        const startDate = new Date(d.setDate(1));
        const endDate = new Date(d.setFullYear(d.getFullYear() + 1));
        endDate.setDate(0);
        const numberOfMonths = Math.round((endDate.valueOf() - startDate.valueOf()) / 1000 / 60 / 60 / 24 / 30.416);
        var tempArr = [];
        
        for(let i = 0; i <= numberOfMonths; i++){
            tempArr.push(months[startDate.getMonth() + i]  + '-' +  new Date (startDate.getFullYear(), startDate.getMonth() + i, 0).getFullYear())
        }
        return tempArr
     }

    function getBookingForm(monthYear){
        const d = new Date(monthYear);
        const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        if(!props.schedules[days[d.getDay()]]){
            return;
        }

        bookingForm.begins_at = d;
        bookingForm.ends_at = d;
        
        if(document.getElementById('begins_at')){
            document.getElementById('begins_at').selectedIndex = 0;
        }

        document.getElementById('bookingFormWrapper').className = '-translate-y-10 ease-in duration-500 mx-4 border rounded border-gray-200 bg-gray-50 md:w-2/3 md:mx-auto lg:w-1/2 lg:px-16 lg:mt-6';
        setTimeout(() => {
            document.getElementById('bookingFormWrapper').className = 'translate-y-0 duration-300 mx-4 border rounded border-gray-200 bg-gray-50 md:w-2/3 md:mx-auto lg:w-1/2 lg:px-16 lg:mt-6';
        }, 500)
    }

    function setupBookingForm(selectedDate){
        const selectedTime = document.getElementById('begins_at').value.split(":");
        bookingForm.begins_at = new Date(selectedDate.getFullYear(),selectedDate.getMonth(),selectedDate.getDate(),selectedTime[0],selectedTime[1]);
        selectedTime[0] = Number.parseInt(selectedTime[0]) + 1;
        bookingForm.ends_at = new Date(selectedDate.getFullYear(),selectedDate.getMonth(),selectedDate.getDate(),selectedTime[0],selectedTime[1]);
    }

    function openDuration(){
        const openTime = props.schedules[days[bookingForm.begins_at.getDay()]].opening_time.split(":");
        const closeTime = props.schedules[days[bookingForm.begins_at.getDay()]].closing_time.split(":");

        const openingDate = new Date (bookingForm.begins_at.getFullYear(),bookingForm.begins_at.getMonth(),bookingForm.begins_at.getDate(),
            openTime[0], openTime[1], openTime[2]);
        const closingDate = new Date (bookingForm.begins_at.getFullYear(),bookingForm.begins_at.getMonth(),bookingForm.begins_at.getDate(),
            closeTime[0], closeTime[1], closeTime[2]);

        var i = openingDate.getHours()
        let timeList = [''];
        for(i; i < closingDate.getHours(); i++){
            timeList.push(i + ":00");
        }
        return timeList;
    }

    function submit(){
        router.post(route('event_bookings.store', props.event), bookingForm);
    }

    function toggleEvents(){
        document.querySelectorAll('[id=my-events]').forEach(myEventsEl => {
            if(myEventsEl.style.display == 'none') {
                myEventsEl.style.display = 'block'
                document.getElementById('events-toggle-button').style.marginLeft = '1em';
            }
            else {
                myEventsEl.style.display = 'none'
                document.getElementById('events-toggle-button').style.marginLeft = '1px';
            }
        });
    }

    function getTime(date){
        const d = new Date(date);
        const dMinutes = d.getMinutes() < 10 ? "0" + d.getMinutes() : d.getMinutes();
        return d.getHours() + ":" + dMinutes;
    }

    function manageDate(value){
        getBookingForm(value);
    }
</script>

<template>
    <Head :title="props.event.title + ' Booking'" />
    <PlannerLayout>
        <!-- Page Header -->
        <div :style="'background-image: url(/event_images/' + props.event['src'] + ');'"
            class="relative bg-cover bg-center w-screen h-36 overflow-clip">
            <div class="absolute -bottom-2 bg-white/80 text-xl font-semibold px-3 py-2">
                {{ props.event['title'] }}
            </div>
        </div>

        <!-- Calendar Header -->
        <div class="flex justify-between mt-1 mx-4">
            <div class="font-semibold">Availability</div>
            <div class="text-xs text-blue-500 font-semibold mt-auto" @click="toggleEvents">
                <span class="text-wrap pr-2">My Schedule</span>
                <div class="relative inline-block h-3 w-6 rounded-full bg-white border border-blue-500">
                    <div id="events-toggle-button" class="absolute left-[1px] top-[1px] h-2 w-2 ml-3 transition-[margin] duration-500 bg-blue-500 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Calendar -->
        <div class="flex gap-3 scroll-pl-2 snap-x snap-mandatory overflow-x-auto w-screen bg-gray-100">
            <Calendar
                v-for="(month, index) in getCalendarMonths()"
                :id="index"
                :month="month"
                :events="monthlyEventBookings[month]"
                :daysOpen="daysOpen"
                @manage-date="manageDate"
            >
            </Calendar>
        </div>

        <!-- Booking Form -->
        <div id="bookingFormWrapper" class="mx-4 border rounded border-gray-200 bg-gray-50 md:w-2/3 md:mx-auto lg:w-1/2 lg:px-16 lg:mt-6">
            <div v-if="!bookingForm.begins_at" class="text-center font-semibold px-3 py-4 text-lg">
                Choose a date from the above calendar.
            </div>
            <div v-else class="flex flex-col gap-3 px-8 py-4 text-right">
                <div class="text-2xl font-bold text-center">
                    {{ bookingForm.begins_at.getDate() + " " + months[bookingForm.begins_at.getMonth()] + " " + bookingForm.begins_at.getFullYear() }}
                </div>

                <div v-if="monthlyEventBookings[months[bookingForm.begins_at.getMonth()] + '-' + bookingForm.begins_at.getFullYear()] && monthlyEventBookings[months[bookingForm.begins_at.getMonth()] + '-' + bookingForm.begins_at.getFullYear()][bookingForm.begins_at.getDate()]"
                    class="bg-blue-200 -mx-6 px-4 md:mx-0 lg:mx-4">
                    <p class="text-blue-900 semibold text-left">Also happening on this day:</p>
                    <div v-for="eventBooking in monthlyEventBookings[months[bookingForm.begins_at.getMonth()] + '-' + bookingForm.begins_at.getFullYear()][bookingForm.begins_at.getDate()]"
                        :id="'my-event' + eventBooking.id"
                        class="flex justify-between gap-2 whitespace-nowrap px-3 py-2 my-1 text-sm bg-white border border-blue-400 rounded-lg z-40"
                        >
                        <span class="text-xs text-gray-700">
                            {{ getTime(eventBooking.begins_at) }}
                        </span>
                        <span class="font-semibold">
                            {{ eventBooking.title }}
                        </span>
                    </div>
                </div>

                <form @submit.prevent="submit">
                    <div class="grid grid-cols-2 place-items-center gap-2">
                        <label for="begins_at" class="">
                            <span class="semibold">{{ props.event.title }} </span>
                            <span class="block sm:inline">Time Slot</span>
                        </label>
                        <select v-on:change="setupBookingForm(bookingForm.begins_at)" id="begins_at" class="my-2 w-24" @keydown.prevent>
                            <option v-for="duration in openDuration()" datatype="time" :value="duration">
                                {{ duration }}
                            </option>
                        </select>
                        <button type="submit" class="col-span-2 w-fit mx-auto text-lg font-semibold py-1 px-3 bg-white border border-gray-700">
                            Confirm Booking
                        </button>
                    </div>

                    
                </form>

            </div>
        </div>
        
    </PlannerLayout>
</template>