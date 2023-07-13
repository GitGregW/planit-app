<script setup>
import PlannerLayout from '@/Layouts/PlannerLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
        recentEvents: {
            type: Object,
        },
        activeEvents: {
            type: Object,
        },
        inactiveEvents: {
            type: Object,
        },
        recentEventsCount: {
            type: Number,
        },
        activeEventsCount: {
            type: Number,
        },
        inactiveEventsCount: {
            type: Number,
        },
    });
</script>

<template>
    <Head title="Dashboard" />

    <PlannerLayout class="bg-yellow-400">
        <div class="pb-3 bg-white">
            <div class="flex justify-between w-full mb-3 py-3 px-4 bg-white">
                <div class="text-2xl font-semibold">Your Recent Events</div>
                <Link :href="route('events.index')" class="text-lg underline text-gray-700 px-2">View all</Link>
            </div>
            <div class="flex flex-nowrap overflow-x-scroll py-1 px-8 my-2 gap-4">
                <Link v-for="event in recentEvents" :href="route('events.show', [event.slug])">
                    <div :style="'background-image: url(' + '\'' + '/event_images/' + event.src + '\'' + ');'"
                        class="bg-cover bg-center h-60 w-48">
                        <div class="relative bg-black/30 h-full">
                            <span class="absolute -mt-1 text-4xl text-yellow-500 font-extrabold tracking-wider text-justify w-48 overflow-hidden">
                                {{ event.title }}
                            </span>
                        </div>
                    </div>
                </Link>
                <Link :href="route('events.index')">
                    <div class="relative h-60 w-48 px-8 my-auto bg-yellow-400/40  border-2 rounded border-grey-700 overflow-clip">
                        <svg class="absolute top-12 -right-8 fill-white stroke-yellow-700 stroke-2 w-24 h-24">
                            <use href="/icons/planet-earth.svg#Layer_1" />
                        </svg>
                        <div class="text-xl text-grey-700 font-semibold tracking-wider hyphens-auto pt-8">
                            View All <span class="underline">{{ props.recentEventsCount }}</span> Events
                        </div>
                    </div>
                </Link>
            </div>
        </div>
        <div class="py-4">
            <Link :href="route('events.create')" class="text-xl bg-white pl-2 pr-4 py-2 border border-gray-200 border-2 rounded ml-8">
                <svg class="inline stroke-black fill-none w-8 h-8">
                    <use href="/icons/feather-sprite.svg#plus" />
                </svg>
                 New Event
            </Link>
        </div>
        <div class="pb-3 bg-white">
            <div class="flex justify-between w-full mb-3 py-3 px-4 bg-white">
                <div class="text-2xl font-semibold">Your Inactive Events</div>
                <Link :href="route('events.index')" class="text-lg underline text-gray-700 px-2">View all</Link>
            </div>
            <div class="flex flex-nowrap overflow-x-scroll py-1 px-8 my-2 gap-4">
                <Link v-for="event in inactiveEvents" :href="route('events.show', [event.slug])">
                    <div :style="'background-image: url(' + '\'' + '/event_images/' + event.src + '\'' + ');'"
                        class="bg-cover bg-center h-60 w-48">
                        <div class="relative bg-black/30 h-full">
                            <span class="absolute -mt-1 text-4xl text-yellow-500 font-extrabold tracking-wider text-justify w-48 overflow-hidden">
                                {{ event.title }}
                            </span>
                        </div>
                    </div>
                </Link>
                <Link :href="route('events.index')">
                    <div class="relative h-60 w-48 px-8 my-auto bg-yellow-400/40  border-2 rounded border-grey-700 overflow-clip">
                        <svg class="absolute top-12 -right-8 fill-white stroke-yellow-700 stroke-2 w-24 h-24">
                            <use href="/icons/planet-earth.svg#Layer_1" />
                        </svg>
                        <div class="text-xl text-grey-700 font-semibold tracking-wider hyphens-auto pt-8">
                            View All <span class="underline">{{ props.inactiveEventsCount }}</span> Inactive Events
                        </div>
                    </div>
                </Link>
            </div>
        </div>
        <div class="pb-3 bg-white">
            <div class="flex justify-between w-full mb-3 py-3 px-4 bg-white">
                <div class="text-2xl font-semibold">Your Active Events</div>
                <Link :href="route('events.index')" class="text-lg underline text-gray-700 px-2">View all</Link>
            </div>
            <div class="flex flex-nowrap overflow-x-scroll py-1 px-8 my-2 gap-4">
                <Link v-for="event in activeEvents" :href="route('events.show', [event.slug])">
                    <div :style="'background-image: url(' + '\'' + '/event_images/' + event.src + '\'' + ');'"
                        class="bg-cover bg-center h-60 w-48">
                        <div class="relative bg-black/30 h-full">
                            <span class="absolute -mt-1 text-4xl text-yellow-500 font-extrabold tracking-wider text-justify w-48 overflow-hidden">
                                {{ event.title }}
                            </span>
                        </div>
                    </div>
                </Link>
                <Link :href="route('events.index')">
                    <div class="relative h-60 w-48 px-8 my-auto bg-yellow-400/40  border-2 rounded border-grey-700 overflow-clip">
                        <svg class="absolute top-12 -right-8 fill-white stroke-yellow-700 stroke-2 w-24 h-24">
                            <use href="/icons/planet-earth.svg#Layer_1" />
                        </svg>
                        <div class="text-xl text-grey-700 font-semibold tracking-wider hyphens-auto pt-8">
                            View All <span class="underline">{{ props.activeEventsCount }}</span> Active Events
                        </div>
                    </div>
                </Link>
            </div>
        </div>
    </PlannerLayout>
</template>
