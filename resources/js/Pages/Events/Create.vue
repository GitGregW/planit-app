<script setup>
    import PlannerLayout from '@/Layouts/PlannerLayout.vue';
    import { Head, Link, router, useForm } from '@inertiajs/vue3';

    const form = useForm('CreateEvent', {
        title: null,
        description: null,
        address_line_1: null,
        address_line_2: null,
        city: null,
        county: null,
        postcode: null,
        contact_mobile: null,
        contact_landline: null,
    })

    function submit() {
        router.post(route('events.store'), form);
        document.getElementById('detailsForm').style.display = 'none';
    }

    function toggleFormBody(){
        document.getElementById('detailsForm').style.display == 'none' ? 
            document.getElementById('detailsForm').style.display = 'grid' :
            document.getElementById('detailsForm').style.display = 'none';
    }
</script>

<template>
    <Head title="Create" />
    <PlannerLayout>
        <div class="relative border-gray-500 rounded-lg border-2 my-2 mx-6">
            <div @click="toggleFormBody()" class="flex space-between text-gray-700 font-bold text-4xl">
                <span class="text-white bg-yellow-400 rounde-tl-lg px-4 py-2">1</span>
                <span class="font-semibold my-auto text-2xl pl-3">Event Details</span>
            </div>
    
            <form id="detailsForm" @submit.prevent="submit" class="grid gap-6 pt-10 pl-4 pr-8 justify-items-end">
                <svg class="absolute top-8 -right-36 fill-gray-100 stroke-yellow-400 stroke-2 w-72 h-72 z-0">
                    <use href="/icons/planet-earth.svg#Layer_1" />
                </svg>
                <div class="relative">
                    <label for="title" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">TITLE</label>
                    <div v-show="$page.props.errors.title" class="bg-white rounded-sm mt-1 py-1 px-2 text-red-400">{{ $page.props.errors.title }}</div>
                    <input type="text" id="title" v-model="form.title" class="w-60 float-right">
                </div>
                <div class="relative w-full">
                    <label for="description" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">DESCRIPTION</label>
                    <div v-show="$page.props.errors.description" class="bg-white rounded-sm mt-1 py-1 px-2 text-red-400">{{ $page.props.errors.description }}</div>
                    <textarea id="description" v-model="form.description" rows="5" class="w-full mx-2" />
                </div>
                <div class="relative">
                    <label for="address_line_1" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">ADDRESS LINE 1</label>
                    <div v-show="$page.props.errors.address_line_1" class="bg-white rounded-sm mt-1 py-1 px-2 text-red-400">{{ $page.props.errors.address_line_1 }}</div>
                    <input type="text" id="address_line_1" v-model="form.address_line_1" class="w-60 float-right">
                </div>
                <div class="relative">
                    <label for="address_line_2" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">ADDRESS LINE 2</label>
                    <div v-show="$page.props.errors.address_line_2" class="bg-white rounded-sm mt-1 py-1 px-2 text-red-400">{{ $page.props.errors.address_line_2 }}</div>
                    <input type="text" id="address_line_2" v-model="form.address_line_2" class="w-60 float-right">
                </div>
                <div class="relative">
                    <label for="city" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">CITY</label>
                    <div v-show="$page.props.errors.city" class="bg-white rounded-sm mt-1 py-1 px-2 text-red-400">{{ $page.props.errors.city }}</div>
                    <input type="text" id="city" v-model="form.city" class="w-40 float-right">
                </div>
                <div class="relative">
                    <label for="county" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">COUNTY</label>
                    <div v-show="$page.props.errors.county" class="bg-white rounded-sm mt-1 py-1 px-2 text-red-400">{{ $page.props.errors.county }}</div>
                    <input type="text" id="county" v-model="form.county" class="w-40 float-right">
                </div>
                <div class="relative">
                    <label for="postcode" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">POSTCODE</label>
                    <div v-show="$page.props.errors.postcode" class="bg-white rounded-sm mt-1 py-1 px-2 text-red-400">{{ $page.props.errors.postcode }}</div>
                    <input type="text" id="postcode" v-model="form.postcode" class="w-40 float-right">
                </div>
                <div class="relative">
                    <label for="contact_mobile" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">MOBILE</label>
                    <div v-show="$page.props.errors.contact_mobile" class="bg-white rounded-sm mt-1 py-1 px-2 text-red-400">{{ $page.props.errors.contact_mobile }}</div>
                    <input type="text" id="contact_mobile" v-model="form.contact_mobile" class="w-60 float-right">
                </div>
                <div class="relative">
                    <label for="contact_landline" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">LANDLINE</label>
                    <div v-show="$page.props.errors.contact_landline" class="bg-white rounded-sm mt-1 py-1 px-2 text-red-400">{{ $page.props.errors.contact_landline }}</div>
                    <input type="text" id="contact_landline" v-model="form.contact_landline" class="w-60 float-right">
                </div>
                <div>
                    <div class="flex w-full py-2 -mx-4 justify-around">
                        <Link
                            :href="route('dashboard')"
                            class="text-sm my-auto"
                        >cancel</Link>
                        <button type="submit" class="text-lg font-semibold">Next</button>
                    </div>
                    <div v-show="Object.keys($page.props.errors).length !== 0" class="bg-white rounded-md p-1 mb-2 text-red-400">Please check the errors above.</div>
                </div>
            </form>
        </div>
    </PlannerLayout>
</template>