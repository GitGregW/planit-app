<script setup>
    import PlannerLayout from '@/Layouts/PlannerLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';

    const props = defineProps({
        event: {
            type: Object,
        },
        images: {
            type: Array,
        }
    });
</script>

<template>
    <Head src="Event - Images" />
    <PlannerLayout>
        <div>Event Images</div>
        <div class="flex gap-3 p-3 justify-center">
            <div v-for="image in props.images"
                :class="'bg-[url(/events/' + image + ')] bg-cover bg-center relative w-36 h-36 rounded-sm hover:bg-contain'">
                <span class="absolute top-1 right-1">
                    <svg class="inline stroke-red-600 fill-none stroke-8 w-6 h-6">
                        <use href="/icons/feather-sprite.svg#x" />
                    </svg>
                </span>
            </div>
        </div>
    </PlannerLayout>
</template>