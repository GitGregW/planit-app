<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onBeforeUnmount } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    activeEvents: {
        type: Object,
    },
    activeEventsCount: {
        type: Number,
    },
});

var i = 0;
let verbs = ['Book', 'Attend', 'Pin', 'Plan']
var verbCarousel = window.setInterval(registerVerb, 5000);

onBeforeUnmount(() => {
    window.clearInterval(verbCarousel);
})

function registerVerb(){
    i == (verbs.length - 1) ? i = 0 : i++;
    document.getElementById('registerAttendeeWord').innerHTML = verbs[i];
}
</script>

<template>
    <Head title="Welcome" />

        <div class="grid grid-cols-3 justify-between p-2 bg-yellow-400 w-screen">
            <span></span>
            <div class="relative">
                <Link href="#" class="absolute -top-4 p-4 bg-yellow-400 rounded-full">
                    <svg class="w-16 h-16 mx-2 fill-white">
                        <use href="/icons/planet-earth.svg#Layer_1" />
                    </svg>
                </Link>
            </div>
            <Link href="/login" class="text-gray-700 text-right">
                {{ $page.props.auth.user ? $page.props.auth.user.name : 'Login' }}
                <svg class="inline stroke-gray-700 fill-none stroke-2 w-4 h-4">
                    <use href="/icons/feather-sprite.svg#chevron-down" />
                </svg>
            </Link>
        </div>
        <div style="background-image: url('/images/unsplash/planit/oxana-v-qoAIlAmLJBU-unsplash.jpg');" class="bg-cover bg-center w-screen h-36"></div>
        
        <div class="pb-3 bg-white">
            <div class="flex justify-between w-full mb-3 py-3 px-4 bg-white">
                <div class="text-2xl font-semibold">Recent Events</div>
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
                            View All <span class="underline">{{ activeEventsCount }}</span> Recent Events
                        </div>
                    </div>
                </Link>
            </div>
        </div>
        <div class="flex flex-col gap-6 bg-white p-4">
            <div class="text-center">
                <div class="my-2 text-left font-semibold text-lg">
                    <span id="registerAttendeeWord" class="text-2xl text-semilbold text-yellow-500">{{ verbs[0] }} </span>
                    your next event:
                </div>
                <Link href="/register/attendee" type="button" class="text-bold text-2xl py-2 px-4 border-2 border-yellow-400">
                    Register an Account</Link>
            </div>
            <div class="text-center">
                <div class="my-2 text-left">Want to see your events here?</div>
                <Link href="/register/planner" type="button" class="text-bold text-2xl py-2 px-4 border-2 border-yellow-400">
                    Register as a <span class="underline px-2 py-1 text-yellow-400">Planner .</span></Link>
            </div>
        </div>
        <Link :href="route('logout')" method="post" as="button">
            Log Out
        </Link>
</template>