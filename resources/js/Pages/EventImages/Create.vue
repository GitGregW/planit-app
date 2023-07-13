<script setup>
    import PlannerLayout from '@/Layouts/PlannerLayout.vue';
    import Image from '@/Pages/EventImages/Image.vue';
    import { Head, Link, useForm, router } from '@inertiajs/vue3';

    router.reload({ only: ['images']})
    
    const props = defineProps({
        eventSlug: {
            type: String,
        },
        images: {
            type: Array,
        },
    });

    const form = useForm({
        src: null,
    })

    function submit() {
        router.post(route('event_images.store', props.eventSlug), form);

        // document.getElementById('imagesForm').style.display = 'none';
        // document.getElementById('schedulesForm').style.display = 'block';
    }

    function toggleFormBody(){
        document.getElementById('imagesForm').style.display == 'none' ? 
            document.getElementById('imagesForm').style.display = 'block' :
            document.getElementById('imagesForm').style.display = 'none';
    }
</script>

<template>
    <div class="border-gray-500 rounded-lg border-2 my-2 mx-6">
        <div @click="toggleFormBody()" class="flex space-between text-gray-700 font-bold text-4xl">
            <span class="text-white bg-yellow-400 rounde-tl-lg px-4 py-2">2</span>
            <span class="font-semibold my-auto text-2xl pl-3">Event Images</span>
        </div>
        <form id="imagesForm" @submit.prevent="submit" class="pb-4">
            <div class="relative w-fit mt-6 mx-auto">
                <label for="src" class="absolute -top-3 right-4 text-sm bg-yellow-400 px-1">Image</label>
                <input type="file" id="src" @input="form.src = $event.target.files[0]" v-on:change="submit()" class="w-60">
            </div>
            <div id="images" class="flex flex-wrap gap-2 py-3 justify-center mx-8 my-4 bg-white min-h-52 max-h-[30rem] overflow-auto">
                <Image v-for="(image, index) in props.images"
                    :eventSlug="eventSlug" :image="image" :index="index" />
            </div>
        </form>
    </div>
</template>