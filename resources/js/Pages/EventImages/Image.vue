<script setup>
    import { useForm, router } from '@inertiajs/vue3';

    const props = defineProps({
        eventSlug: {
            type: String,
        },
        image: {
            type: String,
        },
        index: {
            type: Number,
        },
    });

    const form = useForm({
        src: null,
    })

    function removeImage(index) {
        router.delete(route('event_images.destroy', [props.eventSlug, index]), form)
        document.getElementById('image' + index).style.display = 'none';
    }
</script>

<template>
    <div :style="'background-image: url(' + '\'' + '/event_images/' + image + '\'' + ');'" :id="'image' + index" :class="'bg-cover bg-center relative w-4/5 h-48 rounded-sm hover:bg-contain'">
        <span class="absolute top-2 right-2 bg-white opacity-80 rounded-lg border-red-600 border-2 hover:opacity-100">
            <svg @click="removeImage(index)" class="inline stroke-red-600 fill-none stroke-8 w-6 h-6 hover:cursor-pointer">
                <use href="/icons/feather-sprite.svg#x" />
            </svg>
        </span>
    </div>
</template>