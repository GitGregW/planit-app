<script setup>
    import { computed } from 'vue';
    import Title from '@/Components/Calendar/Title.vue';
    import DayHeaders from '@/Components/Calendar/DayHeaders.vue';
    import Day from '@/Components/Calendar/Day.vue';

    const props = defineProps({
        id: {
            type: Number,
        },
        month: {
            type: String,
        },
        events: {
            type: Object,
            default: null,
        },
        daysOpen: {
            type: Array,
            default: null,
        }
    });

    const emit = defineEmits(['manageDate'])
    // const printDate = computed((day) => {
    //     return (day >= startDay && day <= endDay)
    //         ? day - startDay + 1
    //         : 0;
    // });

    const d = new Date(props.month);
    var startDay = d.getDay();
    if(startDay == 0){ startDay = 7; }
    const endDay = new Date (d.getFullYear(), d.getMonth() + 1, 0).getDate();

    function isClosed(day){
        const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        return (props.daysOpen && !props.daysOpen[days[day - ((Math.floor( day / 7 ) * 7))]])
            ? true
            : false
    }

    function alignEvent(dateTime, i){
        const d = new Date(dateTime);
        return d.getHours() < 12 ? 'left-' + (i + 1) : 'right-' + (i + 1);
    }
</script>

<template>
    <div class="grid grid-cols-7 grid-rows-6 snap-always snap-start min-w-[80%] min-h-content py-1 [&:nth-child(1)]:ml-10 relative sm:min-w-[22em]">
        <Title :value="props.month" />
        <DayHeaders />

        <div v-if="startDay != 1" :class="'col-[1_/_span_' + (startDay - 1) + ']'"></div>
        <Day
            v-for="day in endDay"
            :id="'calendar-' + day + '-' + props.month.toLowerCase()"
            @click="emit('manageDate',
                day + '-' + props.month.toLowerCase(),
                (props.events && props.events[day]) ? true : false
            )"
            :value="day"
            :closed="isClosed((startDay - 1) + day)"
        >
            <div
                v-if="props.events"
                v-for="(event, index) in props.events[day]"
                :class="alignEvent(event.begins_at, index) + ' absolute bottom-1 h-2 w-2 bg-blue-400/80 bg-clip rounded-full'"
            ></div>
        </Day>
        <div v-if="startDay != 7" class='col-span-7 h-10'></div>
        <slot />
    </div>
</template>