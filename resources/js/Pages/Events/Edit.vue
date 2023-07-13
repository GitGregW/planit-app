<script setup>
    import PlannerLayout from '@/Layouts/PlannerLayout.vue';
    import EventImageCreate from '@/Pages/EventImages/Create.vue';
    import EventScheduleCreate from '@/Pages/EventSchedules/Create.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { reactive, onMounted } from 'vue'

    const props = defineProps({
        event: {
            type: Object,
        },
        images: {
            type: Array,
        },
        schedules: {
            type: Object,
        },
    });

    router.reload({ only: ['event'] })

    onMounted(() => {
        if(!eventCanBeActive()){
            if(props.event.is_active){
                document.getElementById('activeText').innerHTML = "ACTIVE";
                document.getElementById('activeLabel').setAttribute('class', 'text-sm p-3 mr-2 border-b-4 border-green-500');
            } else {
                document.getElementById('activeText').innerHTML = "INACTIVE";
                document.getElementById('activeLabel').setAttribute('class', 'text-sm p-3 mr-2 border-b-4 border-grey-500');
            }
        }
    })

    const form = reactive({
        title: props.event.title,
        description: props.event.description,
        address_line_1: props.event.address_line_1,
        address_line_2: props.event.address_line_2,
        city: props.event.city,
        county: props.event.county,
        postcode: props.event.postcode,
        contact_mobile: props.event.contact_mobile,
        contact_landline: props.event.contact_landline,
    })

    function eventCanBeActive(){
        let $errorVal = '';
        if(!props.images.length){ $errorVal = ' [Image]'; }
        if(!Object.keys(props.schedules).length){ $errorVal += ' [Schedule]'; }
        return $errorVal;
    }

    function submit() {
        router.patch(route('events.update', props.event.slug), form);
        // document.getElementById('detailsForm').style.display = 'none';
        // document.getElementById('imageForm').style.display = 'block';
    }

    const eventActiveForm = reactive({'is_active': props.event.is_active});
    function submitActive() {
        router.patch(route('events.update', props.event.slug), eventActiveForm, {
            onBefore: () => {
                if( confirm('Are you sure you wish to ' + (eventActiveForm.is_active ? 'REMOVE from' : 'GO') + ' live?') ){
                    eventActiveForm.is_active = !eventActiveForm.is_active;
                }
                else{
                    document.getElementById('active').checked = eventActiveForm.is_active
                    return false;
                }
            },
            onSucess: () => {
                if(eventActiveForm.is_active){
                    document.getElementById('activeText').innerHTML = "ACTIVE";
                    document.getElementById('activeLabel').setAttribute('class', 'text-sm p-3 mr-2 border-b-4 border-green-500');
                } else {
                    document.getElementById('activeText').innerHTML = "INACTIVE";
                    document.getElementById('activeLabel').setAttribute('class', 'text-sm p-3 mr-2 border-b-4 border-grey-500');
                }
            },
        });
    }

    function cancelEvent() {
        router.delete(route('events.destroy', props.event.slug), {
            onBefore: () => {
                if( !confirm('Are you sure you would like to delete this event? This action is irreversible.') ){
                    return false;
                }
            },
        });
    }

    function toggleFormBody(){
        document.getElementById('detailsForm').style.display == 'none' ? 
            document.getElementById('detailsForm').style.display = 'grid' :
            document.getElementById('detailsForm').style.display = 'none';
    }
</script>

<template>
    <Head :title="props.event.title + ' edit'" />
    <PlannerLayout>
        <div class="py-8"></div>
        <div class="ml-4 p-2 text-grey-700">
            <Link :href="route('events.index')" class="text-blue-900 underline">Events</Link>
            > <Link :href="route('events.show', props.event.slug)" class="text-blue-900 underline">{{ props.event.title }}</Link>
            > Edit
        </div>
        <div style="z-index:3;" class="fixed top-0 w-full">
            <div class="relative border-b-2 border-gray-500 bg-white">
                <div class="grid grid-cols-2 justify-between text-gray-700 font-bold text-4xl">
                    <div class="text-black bg-yellow-400 px-4 py-2">
                        <svg class="inline stroke-black fill-none stroke-2 w-8 h-8">
                            <use href="/icons/feather-sprite.svg#alert-circle" />
                        </svg>
                        <span class="font-semibold my-auto text-2xl pl-3">Go Live</span>
                    </div>
                    <div v-if="eventCanBeActive()" class="text-sm font-normal text-red-900 px-3 py-2 border-4 border-yellow-400 rouned-sm">Missing at least one: 
                        <span class="font-semibold">{{ eventCanBeActive() }}</span>
                    </div>
                    <form v-else @submit.prevent="submitActive" class="text-right">
                        <label id="activeLabel" for="active" class="text-sm p-3 mr-2 border-b-4">
                            <span id="activeText">INACTIVE</span>
                            <input v-on:change="submitActive()" type="checkbox" id="active" :checked="eventActiveForm.is_active" class="ml-2">
                        </label>
                    </form>
                </div>
            </div>
        </div>
        <div class="my-2 mx-6">
            <div class="relative border-gray-500 rounded-lg border-2 overflow-clip">
                <div v-on:change="toggleFormBody()" class="flex space-between text-gray-700 font-bold text-4xl">
                    <span class="text-white bg-yellow-400 rounded-tl-lg px-4 py-2">1</span>
                    <span class="font-semibold my-auto text-2xl pl-3">Event Details</span>
                </div>
        
                <form id="detailsForm" @submit.prevent="submit" class="grid gap-6 pt-10 pr-8 justify-items-end">
                    <svg class="absolute top-8 -right-36 fill-gray-100 stroke-yellow-400 stroke-2 w-72 h-72 z-1"  style="z-index:0;">
                        <use href="/icons/planet-earth.svg#Layer_1" />
                    </svg>
                    <div class="relative">
                        <label for="title" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">TITLE</label>
                        <input type="text" id="title" v-model="form.title" class="w-60">
                    </div>
                    <div class="relative w-full">
                        <label for="description" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">DESCRIPTION</label>
                        <textarea id="description" v-model="form.description" class="w-full mx-4" />
                    </div>
                    <div class="relative">
                        <label for="address_line_1" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">ADDRESS LINE 1</label>
                        <input type="text" id="address_line_1" v-model="form.address_line_1" class="w-60">
                    </div>
                    <div class="relative">
                        <label for="address_line_2" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">ADDRESS LINE 2</label>
                        <input type="text" id="address_line_2" v-model="form.address_line_2" class="w-60">
                    </div>
                    <div class="relative">
                        <label for="city" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">CITY</label>
                        <input type="text" id="city" v-model="form.city" class="w-40">
                    </div>
                    <div class="relative">
                        <label for="county" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">COUNTY</label>
                        <input type="text" id="county" v-model="form.county" class="w-40">
                    </div>
                    <div class="relative">
                        <label for="postcode" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">POSTCODE</label>
                        <input type="text" id="postcode" v-model="form.postcode" class="w-40">
                    </div>
                    <div class="relative">
                        <label for="contact_mobile" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">MOBILE</label>
                        <input type="text" id="contact_mobile" v-model="form.contact_mobile" class="w-60">
                    </div>
                    <div class="relative">
                        <label for="contact_landline" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">LANDLINE</label>
                        <input type="text" id="contact_landline" v-model="form.contact_landline" class="w-60">
                    </div>
                    <div class="flex w-full justify-around">
                        <span></span>
                        <button type="submit" class="text-lg font-semibold">Update</button>
                    </div>
                </form>
            </div>
        </div>
        <EventImageCreate :eventSlug="props.event.slug" :images="props.images" />
        <EventScheduleCreate @submittedSchedules="" :eventSlug="props.event.slug" :schedules="props.schedules" />

        <form class="text-center my-4"
            @submit.prevent="cancelEvent"
            @hover="document.getElementById('removeEventWarning').style.display = 'block'"
            >
            <button type="submit" class="mx-auto text-md text-semibold text-red-700 underline border-gray-400 border-2 py-2 px-8 bg-white">
                Remove Event
            </button>
            <span id="removeEventWarning" class="hidden text-red-700">
                <svg class="inline stroke-red-700 fill-none stroke-2 w-4 h-4">
                    <use href="/icons/feather-sprite.svg#alert-circle" />
                </svg>
                The event including any bookings will be deleted.
            </span>
        </form>
        <div class="text-center my-4">
            <Link class="mx-auto text-lg text-semibold text-gray-700 border-gray-400 border-2 py-2 px-8 bg-white"
                :href="route('events.show', props.event.slug)">
                View Event
            </Link>
        </div>
    </PlannerLayout>
</template>