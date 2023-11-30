<script setup>
    import { Link, router, usePage } from '@inertiajs/vue3';
    import { computed } from 'vue';

    const props = defineProps({
        eventBookings: {
            type: Object,
        },
        month: {
            type: String,
        },
        id: {
            type: String,
        }
    });
    const page = usePage()
    const message = computed(() => page.props.flash.message)
    const emit = defineEmits(['manageDate'])

    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December","January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const dayHeadersFull = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday","Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

    function getFullDate(){
        const d = new Date(props.eventBookings[0].begins_at);
        return (dayHeadersFull[d.getDay()] + ' ' + d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear()).toUpperCase();
    }

    function getTime(date){
        const d = new Date(date);
        const dMinutes = d.getMinutes() < 10 ? "0" + d.getMinutes() : d.getMinutes();
        return d.getHours() + ":" + dMinutes;
    }

    function toggleEventOptions(eventBooking){
        const eventBackground = document.getElementById('eventOptionsBackground' + eventBooking.id);
        const eventOptions = document.getElementById('eventOptions' + eventBooking.id);

        if(eventBackground.style.display == 'none')
        {
            eventBackground.style.display = 'block';
            eventOptions.style.display = 'flex';
            emit('manageDate', eventBooking.day + '-' + eventBooking.monthYear.toLowerCase(), true);
        }
        else
        {
            eventBackground.style.display = 'none';
            eventOptions.style.display = 'none';
        }
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
            },
            onFinish: () => {
                // 
            },
        });
    }
</script>
<template>
    <div :id="'schedule-' + props.id + '-' + props.month.toLowerCase()" class="sticky top-0 w-full px-2 py-2 text-center text-xs text-gray-700 font-semibold bg-gray-100 z-20">
        {{ getFullDate() }}
    </div>
    
    <div v-for="eventBooking in props.eventBookings" class="relative grid grid-cols-2 my-1 divide-y-2 divide-gray-300 rounded bg-white snap-always snap-start">
        <div @click="toggleEventOptions(eventBooking)" style="z-index: 10;" class="absolute flex gap-1 col-span-2 justify-between px-2 py-1 text-semibold w-full bg-white/80 z-10">
            <div class="text-gray-700">{{ getTime(eventBooking.begins_at) }}</div>
            <span class="truncate text-lg">{{ eventBooking.title }}</span>
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
</template>