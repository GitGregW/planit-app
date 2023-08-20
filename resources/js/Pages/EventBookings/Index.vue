<script setup>
    import PlannerLayout from '@/Layouts/PlannerLayout.vue';
    import { Head, Link, router, usePage } from '@inertiajs/vue3';
    // import { reactive } from 'vue';

    const props = defineProps({
        groupedEventBookings: {
            type: Object,
        },
    });

    const page = usePage()

    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const dayHeaders = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday","Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    const d = new Date();

    let calendars = [];
    let j=0;
    for(let i = 0; i<12; i++){
        let startDay = new Date((d.getFullYear() + j), d.getMonth() + i, 1).getDay();
        calendars[i] = {
            monthYear: months[d.getMonth() + i]  + '-' +  (d.getFullYear() + j),
            startDay: startDay,
            endDate: new Date ((d.getFullYear() + j), d.getMonth() + i + 1, 0).getDate(),
        }
        if(d.getMonth() + i == 11){ j=1; }
    }
    const today = d.getDate() + (calendars[0].startDay - 1);

    function getDay(day, startDay){
        return dayHeaders[(day + startDay - 1) - (((Math.floor( (day-1) / 7 ) * 7)))];
    }

    function getTime(eventBooking){
        const d = new Date(eventBooking);
        const dMinutes = d.getMinutes() < 10 ? "0" + d.getMinutes() : d.getMinutes();
        return d.getHours() + ":" + dMinutes;
    }

    function toggleEventOptions(bookingId){
        const eventBackground = document.getElementById('eventOptionsBackground' + bookingId);
        const eventOptions = document.getElementById('eventOptions' + bookingId);

        eventBackground.style.display == 'none' ? eventBackground.style.display = 'block' : eventBackground.style.display = 'none';
        eventOptions.style.display == 'none' ? eventOptions.style.display = 'flex' : eventOptions.style.display = 'none';
    }

    function cancelEvent(eventBooking) {
        router.delete(route('event_bookings.destroy', eventBooking.id), {
            preserveScroll: true,
            onBefore: () => {
                if( !confirm('Are you sure you wish to cancel ' + eventBooking.monthYear + ' at ' + getTime(eventBooking.begins_at) + '?') ){
                    return false;
                }
            },
            onSuccess: () => {
                page.props.flash.message = "Cancelled " + eventBooking.monthYear + " at " + getTime(eventBooking.begins_at);
                animateFlash();
            },
            onFinish: () => {
                // 
            },
        });
    }

    function sleep(ms){
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    function animateFlash(){
        let flashElement = document.getElementById('flashMessage');
        flashElement.style.display = 'block';
        sleep(200).then(() => {
            flashElement.style.margin = '2rem 2rem';
        });
        sleep(5000).then(() => {
            flashElement.style.margin = '-8rem 2rem';
        });
        sleep(6000).then(() => {
            flashElement.style.display = 'none';
        });
    }

    function toggleCalendar(calendar){
        document.getElementById(calendar).style.display == 'none'
            ? document.getElementById(calendar).style.display = 'grid'
            : document.getElementById(calendar).style.display = 'none';
    }
</script>

<template>
    <Head monthYear="Event Bookings" />
    <PlannerLayout>
        <div class="relative">
            <div id="flashMessage" style="z-index: 49;" class="fixed -my-40 w-4/5 mx-8 py-1 px-3 font-bold text-lg bg-white border-4 border-red-300 rounded-sm transition-[margin] duration-700">
                {{ $page.props.flash.message }}
            </div>
            <div v-for="(calendar, index) in calendars" class="border-b-2 border-gray-700">
                <div @click="toggleCalendar('calendar' + index)" class="text-xl font-bold p-2 pb-3 bg-yellow-400/50">
                    {{ calendar.monthYear }}
                    <span class="text-lg text-800-gray px-3"> Schedule</span>
                </div>
                <div v-if="!groupedEventBookings[calendar.monthYear]" :id="'calendar' + index" class="grid divide-y-2 divide-gray-300 p-2" :style="(index > 2 ? 'display: none;' : 'display: grid;')">
                    <div v-for="day in parseInt(calendar.endDate)" class="">
                        <div class="font-semibold text-gray-700 py-1 ">{{ day }} <span class="pl-3 text-sm italic">{{ getDay(day, calendar.startDay) }}</span></div>
                    </div>
                </div>
                <div v-else :id="'calendar' + index" class="grid divide-y-2 divide-gray-300 p-2" :style="(index > 2 ? 'display: none;' : 'display: grid;')">
                    <div v-for="day in parseInt(calendar.endDate)" class="">
                        <div class="font-semibold text-gray-700 py-1 ">{{ day }} <span class="pl-3 text-sm italic">{{ getDay(day, calendar.startDay) }}</span></div>
                        <div class="border-none">
                            <div v-for="eventBooking in groupedEventBookings[calendar.monthYear][day]" :id="'booking' + eventBooking.id" class="relative grid grid-cols-2 mb-2 rounded bg-white">
    
                                <div @click="toggleEventOptions(eventBooking.id)" style="z-index: 1;" class="absolute flex col-span-2 justify-between px-2 py-1 text-semibold w-full bg-white/80">
                                    <div class="">{{ getTime(eventBooking.begins_at) }}</div>
                                    <span class="text-xl">{{ eventBooking.title }}</span>
                                </div>
                                <span class="h-10"></span>
                                <Link :href="route('events.show', [eventBooking.slug])" :id="'eventOptionsBackground' + eventBooking.id" :style="'background-image: url(/event_images/' + eventBooking.src + ');display: none;'"
                                    class="relative row-span-2 bg-cover bg-center h-auto overflow-clip">
                                    <span class="absolute bottom-0 right-0 text-white bg-black/30 py-1 px-3">
                                        View
                                        <svg class="inline stroke-white fill-none stroke-2 w-8 h-8">
                                            <use href="/icons/feather-sprite.svg#chevron-right" />
                                        </svg>
                                    </span>
                                </Link>
                                <div :id="'eventOptions' + eventBooking.id" class="flex flex-col justify-around gap-1 mx-auto text-right py-2" style="display: none;">
                                    <span>£{{ eventBooking.price ? eventBooking.price : '0.00' }}</span>
                                    <button type="button" @click="cancelEvent(eventBooking)" class="py-1 px-4 text-gray-700 border border-red-700 rounded focus:text-black">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PlannerLayout>
</template>