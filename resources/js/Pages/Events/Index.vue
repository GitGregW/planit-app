<script setup>
    import { useInfiniteScroll } from '@/Composables/useInfiniteScroll.js';
    import PlannerLayout from '@/Layouts/PlannerLayout.vue';
    import Card from '@/Components/Events/Card.vue';
    import { Head, Link, router, usePage } from '@inertiajs/vue3';
    import { onMounted, onBeforeUnmount, reactive, ref } from 'vue';

    const props = defineProps({
        events: {
            type: Object,
        },
    });

    const landmark = ref(null);
    const { items, reset, canLoadMoreItems } = useInfiniteScroll('events', landmark, {rootMargin: '0px 0px 480px 0px'});
    const scrollTop = (() => window.scrollTo(0,0));
    const initialUrl = usePage().url;

    router.reload({
        only: ['events'],
        data: {},
        onSuccess: () => {
            window.history.replaceState({}, '', initialUrl);
            reset();
        }
    });

    function scrollToTop(){
        window.scrollTo(0,0);
    }
</script>
<template>
    <Head title="Events" />
    <PlannerLayout>
        <div v-if="$page.props.auth.user.user_group_id == 2" class="flex justify-between w-full mb-3 py-3 px-4 text-gray-800 w-full mb-3 py-3 px-4 bg-yellow-400 font-custom bg-gradient-to-t from-yellow-500 via-yellow-400 via-15% via-yellow-400 via-40% via-yellow-400 via-80% to-yellow-200">
            <Link :href="route('events.create')" class="text-sm bg-white px-2 border border-gray-200 border-2 rounded">
                <svg class="inline stroke-black fill-none w-4 h-4">
                    <use href="/icons/feather-sprite.svg#plus" />
                </svg>
                 New Event
            </Link>
            <div class="text-2xl font-custom">Your Events</div>
        </div>
        <div v-else class="text-2xl text-gray-800 w-full mb-3 py-3 px-4 bg-yellow-400 font-custom bg-gradient-to-t from-yellow-500 via-yellow-400 via-15% via-yellow-400 via-40% via-yellow-400 via-80% to-yellow-200">Events</div>
        
        <div class="grid grid-cols-1 gap-y-2 pb-10 mx-2 md:mx-0 md:px-2 md:grid-cols-2 xl:grid-cols-3 xl:px-4 md:gap-2 xl:gap-x-4 xl:px-16 xl:py-4">
            <Link v-for="(event, index) in items"
                :href="route('events.show', [event.slug])"
                class="grid grid-cols-auto relative border border-gray-300 shadow transition-[margin] duration-700 lg:grid-cols-4"
            >
                <Card :event="event" />
            </Link>
        </div>
        <Link v-if="!canLoadMoreItems" @click="scrollTop">No more results. Scroll Top.</Link>
        <div ref="landmark"></div>

        <div @click="scrollToTop()" class="bg-yellow-400 h-16 w-full text-gray-700 text-sm text-right pr-4 pt-4">
            <span class="group">Scroll to Top
                <svg class="inline stroke-gray-700 fill-none stroke-2 w-6 h-6 ml-2 group-hover:animate-bounce">
                    <use href="/icons/feather-sprite.svg#arrow-up-circle" />
                </svg>
            </span>
        </div>
    </PlannerLayout>
</template>