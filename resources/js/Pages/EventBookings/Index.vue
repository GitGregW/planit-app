<script setup>
    import PlannerLayout from '@/Layouts/PlannerLayout.vue';
    import Calendar from '@/Components/Calendar/Calendar.vue';
    import Schedule from '@/Components/Calendar/Schedule.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { reactive } from 'vue';

    const props = defineProps({
        monthlyEventBookings: {
            type: Object,
        },
        startDate: {
            type: String,
        },
        endDate: {
            type: String,
        }
    });

    defineEmits(['selectedDate'])
    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    const calendarForm = reactive({
        startDate: props.startDate,
        endDate: props.endDate
     });

     function getCalendarMonths(){
        const startDate = new Date(props.startDate);
        const endDate = new Date(props.endDate);
        const numberOfMonths = Math.round((endDate.valueOf() - startDate.valueOf()) / 1000 / 60 / 60 / 24 / 30.416);
        var tempArr = [];
        
        for(let i = 0; i <= numberOfMonths; i++){
            tempArr.push(months[startDate.getMonth() + i]  + '-' +  new Date (startDate.getFullYear(), startDate.getMonth() + i, 0).getFullYear())
        }
        return tempArr
     }

    function manageDate(date, hasEvent = false){
        highlightDate(date, 'calendar');
        if(hasEvent)
        {
            highlightDate(date, 'schedule');
            document.getElementById('schedule-' + date).scrollIntoView();
        }
    }

    function highlightDate(date, component){
        const d = new Date(date);
        var componentClass = document.getElementById(component + '-' + date).className;

        document.getElementById(component + '-' + date).className = componentClass + " bg-yellow-100";
        setTimeout(() => {
            document.getElementById(component + '-' + date).className = componentClass;
        }, 3000);
    }

    function submit(){
        router.get(route('event_bookings.index'), {
            startDate: calendarForm.startDate,
            endDate: calendarForm.endDate
        });
    }
</script>

<template>
    <Head monthYear="Event Bookings" />
    <PlannerLayout>
        <div class="relative">
            <div class="bg-gradient-to-b from-yellow-300 via-yellow-200 via-15% via-yellow-200 via-40% to-yellow-200 border-b-2 border-white">
                <div class="py-2 font-bold text-center font-custom">My Calendar</div>
                <!-- Calendar Form -->
                <form @submit.prevent="submit" class="grid grid-cols-2 gap-1 px-2 pt-6 pb-2 w-fit mx-auto md:gap-x-3">
                    <div class="relative">
                        <label for="month-from" class="absolute left-1 -bottom-4 z-20 px-2 bg-white/85 font-bold text-lg">From</label>
                    </div>
                    <div class="relative">
                        <label for="month-to" class="absolute left-1 -bottom-4 z-20 px-2 bg-white/85 font-bold text-lg">To</label>
                    </div>
                    <input
                        id="month-from"
                        class="relative rounded w-fit px-1"
                        v-model="calendarForm.startDate"
                        type="month"
                    />
                    
                    <input
                        id="month-to"
                        class="relative rounded w-fit px-1"
                        v-model="calendarForm.endDate"
                        type="month"
                    />
                    
                    <span></span>
                    <button type="submit" class="text-sm w-fit font-semibold py-1 px-2 ml-4 bg-white border border-gray-700 rounded-lg">
                        Search
                    </button>
                </form>
            </div>

            <div class="flex gap-3 scroll-pl-2 snap-x snap-mandatory overflow-x-auto w-screen pt-2 bg-gray-100">
                <Calendar
                    v-for="(month, index) in getCalendarMonths()"
                    :id="index"
                    :month="month"
                    :events="props.monthlyEventBookings[month]"
                    @manage-date="manageDate"
                >
                    <div class="col-span-7 h-64 overflow-y-auto snap-y snap-mandatory scroll-pt-7 pb-32">
                        <Schedule
                            v-for="(eventBookings, date) in props.monthlyEventBookings[month]"
                            :id="date"
                            :eventBookings="eventBookings"
                            :month="month"
                            @manage-date="manageDate"
                        />
                        <div v-if="!props.monthlyEventBookings[month]" class="w-full px-2 py-1 text-center text-xs text-gray-700 font-semibold">
                            Nothing Scheduled!
                        </div>
                    </div>
                </Calendar>
            </div>
        </div>
    </PlannerLayout>
</template>