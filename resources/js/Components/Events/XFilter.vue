<script setup>
    import { useInfiniteScroll } from '@/Composables/useInfiniteScroll.js';
    import { Link } from '@inertiajs/vue3';
    import { ref } from 'vue';

    defineProps({
        name: String,
        events: Object
    });

    const landmark = ref(null);
    const { items, loadMoreItems, canLoadMoreItems } = useInfiniteScroll('filteredEvents', landmark);
</script>

<template>
    <div class="pb-3">
        
        <div class="flex justify-between w-full mb-3 py-3 px-4">
            <div class="text-2xl font-semibold font-custom">
                {{ name }}
            </div>
            <Link :href="route('events.index')" class="text-lg underline text-gray-700 px-2">
                View all
            </Link>
        </div>

        <div class="flex flex-nowrap overflow-x-scroll py-3 px-8 gap-2">
            <Link
                v-for="event in events.data"
                :href="route('events.show', [event.slug])"
                class="relative block w-48"
            >
                <div :style="'background-image: url(' + '\'' + '/event_images/' + event.src + '\'' + ');'"
                        class="bg-cover bg-bottom h-60 w-48"
                ></div>
                <h1 class="absolute bottom-2 left-2 text-white text-2xl font-bold w-40 overflow-hidden">
                    {{ event.title.toUpperCase() }}
                </h1>
            </Link>

            <!-- <Link v-if="!canLoadMoreItems" @click="scrollTop">Scroll to start</Link> -->
            <div ref="landmark"></div>
        </div>
    </div>
</template>