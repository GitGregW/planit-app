<script setup>
    import { Link, router, useForm } from '@inertiajs/vue3';
    import { reactive } from 'vue'

    const props = defineProps({
        eventSlug: {
            type: String,
        },
        schedules: {
            type: Object,
        },
    });

    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    // const form = reactive({});
    const form = useForm({});
    const deleteForm = reactive({});

    const emit = defineEmits(['submittedSchedules'])

    for (const day in props.schedules){
        form[day] = {
            day: day,
            opening_time: props.schedules[day].opening_time,
            closing_time: props.schedules[day].closing_time,
            custom_date: props.schedules[day].custom_date,
            custom_repeat: props.schedules[day].custom_repeat,
            max_capacity: props.schedules[day].max_capacity
        }
    }

    function submit() {
        router.patch(route('event_schedules.update', [props.eventSlug]), form, {
            onSuccess: visit => {
                router.delete(route('event_schedules.destroy', [props.eventSlug]), {
                    data: deleteForm}
                )
            }, 
            onFinish: () => {
                return emit('submittedSchedules');
            },
            errorBag: 'createSchedules',
        })
        // document.getElementById('schedulesForm').style.display = 'none';
    }

    function openToggle(day){
        if(form[day]){
            delete form[day];
            // Form delete check
            if(props.schedules[day]){
                deleteForm[day] = { day: day }
            }
        }
        else{
            form[day] = {
                day: day,
                opening_time: props.schedules[day] ? props.schedules[day].opening_time : null,
                closing_time: props.schedules[day] ? props.schedules[day].closing_time : null,
                custom_date: props.schedules[day] ? props.schedules[day].custom_date : null,
                custom_repeat: props.schedules[day] ? props.schedules[day].custom_repeat : null,
                max_capacity: props.schedules[day] ? props.schedules[day].max_capacity : null
            };
            if(deleteForm[day]){ delete deleteForm[day]; }
        }
    }

    function toggleFormBody(){
        document.getElementById('schedulesForm').style.display == 'none' ? 
        document.getElementById('schedulesForm').style.display = 'block' :
        document.getElementById('schedulesForm').style.display = 'none';
    }
</script>

<template>
    <div class="my-2 mx-6">
        <div class="border-gray-500 rounded-lg border-2">
            <div @click="toggleFormBody()" class="flex space-between text-gray-700 font-bold text-4xl">
                <span class="text-white bg-yellow-400 rounde-tl-lg px-4 py-2">3</span>
                <span class="font-semibold my-auto text-2xl pl-3">Event Schedule</span>
            </div>
            <form id="schedulesForm" @submit.prevent="submit" class="pb-4">
                <div v-for="(day, i) in days" class="p-4 border-bottom border-2">
                    <div @click="openToggle(day)" class="flex gap-2">
                        <svg class="w-10 h-10 mx-2 fill-red-400 z-1">
                            <use :class="form[day] ? 'fill-green-400' : 'fill-red-400'"
                                :href="'/icons/' + (form[day] ? 'open' : 'closed') + '-sign.svg#Layer_1'"
                                />
                        </svg>
                        <div :class="'text-right mt-auto bg-yellow-400 px-2 w-fit col-span-2 ' +
                            (form[day] ? 'font-semibold text-lg' : 'font-normal text-md')">
                            {{ day.toUpperCase() }}
                        </div>
                    </div>
                    <div v-if="form[day]" class="flex gap-2 items-center pt-2 justify-center">
                        <input type="text" :id='"day" + i' class="w-fit" v-model="form[day].day" hidden>
                        <div class="relative w-fit">
                            <input type="time" :id='"opening_time" + i' v-model="form[day].opening_time">
                        </div>
                        <span>until</span>
                        <div class="relative w-fit">
                            <input type="time" :id='"closing_time" + i' v-model="form[day].closing_time">
                        </div>
                    </div>
                    <div v-if="$page.props.errors.createSchedules">
                        {{ $page.props.errors.createSchedules.opening_time }} and {{ $page.props.errors.createSchedules.closing_time }}
                    </div>
                </div>
                <!-- Capacity & Custom Dates for a later iteration -->
                <!-- <div class="relative w-fit">
                    <label for="max_capacity" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">MAX CAPACITY</label>
                    <input type="number" id="max_capacity" v-model="form.max_capacity" class="w-60">
                </div> -->
                <!-- <div class="relative w-fit">
                    <label for="custom_date" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">CUSTOM DATE</label>
                    <input type="datetime-local" id="custom_date" v-model="form.custom_date" class="w-60">
                </div> -->
                <div class="flex w-full justify-around">
                    <span></span>
                    <button type="submit" class="text-lg font-semibold">Update</button>
                </div>
            </form>
        </div>
    </div>
</template>