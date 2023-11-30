<script setup>
import PlannerLayout from '@/Layouts/PlannerLayout.vue';
import XFilter from '@/Components/Events/XFilter.vue';
import YFilter from '@/Components/Events/YFilter.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
        events: {
            type: Object,
        },
    });
</script>

<template>
    <Head title="Dashboard" />

    <PlannerLayout>
        <!-- <XFilter name="All Recent Events" :events="events" class="bg-blue-900/60 text-white" /> -->

        <div class="py-4 bg-blue-900/60">
            <Link
                :href="route('events.create')"
                as="button"
                class="text-xl bg-white pl-2 pr-4 py-2 border border-gray-200 border-2 rounded ml-8"
            >
                <svg class="inline align-middle stroke-black fill-none w-6 h-6">
                    <use href="/icons/feather-sprite.svg#plus" />
                </svg>
                <span class="align-middle">
                    New Event
                </span>
            </Link>
        </div>

        <YFilter :events="events" class="bg-gray-100 border-t-2 border-gray-400" />
    </PlannerLayout>
</template>
