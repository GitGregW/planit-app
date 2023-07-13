<script setup>
    import { router } from '@inertiajs/vue3';
    import { reactive } from 'vue'

    const props = defineProps({
        i: {
            type: Number,
        },
        day: {
            type: String,
        },
    });
    
    const form = reactive({
        [props.i]: {
            day: props.day,
            opening_time: null,
            closing_time: null,
            custom_date: null,
            custom_repeat: null,
            max_capacity: null
        }
    })
</script>

<template>
    <div class="relative w-fit">
        <div class="text-right">{{ props.day }}</div>
        <input type="text" :id='"day" + i' class="w-fit" v-model="form[i].day" hidden>
    </div>
    <div class="relative w-fit">
        <input type="time" :id='"opening_time" + i' v-model="form[i].opening_time">
    </div>
    <div class="relative w-fit">
        <input type="time" :id='"closing_time" + i' v-model="form[i].closing_time">
    </div>
</template>