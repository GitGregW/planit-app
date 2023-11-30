<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
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

router.on(('navigate'), () => {
    setTimeout(() => {
        document.getElementById('toolbar').style.marginTop = '0';
        // document.getElementById('logo').style.marginLeft = '2em';
    }, 1000);
})

function registerVerb(){
    i == (verbs.length - 1) ? i = 0 : i++;
    document.getElementById('registerAttendeeWord').innerHTML = verbs[i];
}

function toggleToolbarDropdown()
{
    const toolbarDropdown = document.getElementById('toolbar-dropdown');

    toolbarDropdown.style.display == 'block'
        ? toolbarDropdown.style.display = 'none'
        : toolbarDropdown.style.display = 'block';
        
    // toolbarDropdown.style.marginLeft == '0px'
    //     ? toolbar.style.marginLeft = '50%'
    //     : toolbar.style.marginLeft = '0px';

    // const chevron = document.getElementById('toolbar-svg');
    // toolbarDropdown.style.marginBottom == '0px'
    //     ? chevron.setAttribute('href', '/icons/feather-sprite.svg#chevron-down')
    //     : chevron.setAttribute('href', '/icons/feather-sprite.svg#chevron-right');
}
</script>

<template>
    <div class="bg-yellow-400 h-screen w-auto">
        
        <Head title="Welcome" />

        <div id="toolbar" class="relative grid grid-cols-3 items-center py-1 bg-gradient-to-tr from-yellow-500 via-yellow-400 via-15% via-yellow-400 via-40% via-yellow-400 via-80% to-yellow-200 border-b-2 border-white drop-shadow-md z-40 transition-[margin] ease-in delay-500 duration-500 md:grid-cols-5">
            <div class="col-span-2">
                <div>
                    <div @click="toggleToolbarDropdown()" class="w-fit pl-2 pr-4">
                        <svg class="inline-block w-6 h-6 fill-none stroke-white stroke-8 md:w-8 md:h-8">
                            <use href="/icons/feather-sprite.svg#menu" />
                        </svg>
                        <div class="absolute z-50 left-6 -bottom-8">
                            <div id="logo" class="h-22 w-16 mr-4 bg-yellow-400 rounded-full transition-[margin] ease-in delay-700 duration-700">
                                <svg class="w-16 h-16 mx-auto mt-1 fill-white hover:rotate-15">
                                    <use href="/icons/planet-earth.svg#Layer_1" />
                                </svg>
                            </div>
                        </div>
                        <div v-if="$page.props.auth.user" class="inline-block overflow-hidden max-w-14 ml-12 text-ellipsis whitespace-nowrap">
                            <span class="relative text-white text-sm">Hello </span>
                            <span class="absolute mt-3 -ml-4 md:mt-4">{{ $page.props.auth.user.name }}</span>
                        </div>
                    </div>

                    <div
                        @click="toggleToolbarDropdown()"
                        id="toolbar-dropdown"
                        class="hidden absolute mt-1 pt-4 px-2 min-w-12 bg-white -z-1 transition-[margin] ease-in-out duration-500 md:mt-2"
                        >
                        <div id="toolbar-links" class="flex flex-col gap-3 mx-auto p-2 text-lg bg-white min-h-32 max-h-48 overflow-y-scroll">
                            <Link v-if="!$page.props.auth.user" :href="route('login')" class="text-sm text-center text-gray-700 mb-1 py-2 border-b-2 border-gray-300">sign in</Link>
                            <Link v-else :href="route('logout')" method="post" as="button" class="text-sm text-center text-gray-700 mb-1 py-2 border-b-2 border-gray-300">sign out</Link>
                            <Link v-if="($page.props.auth.user)" :href="route('dashboard')" :class="($page.component == 'DashboardPlanner' || $page.component == 'Dashboard') ? 'font-semibold' : 'text-gray-700'">Dashboard</Link>
                            <Link v-if="($page.props.auth.user && $page.props.auth.user.user_group_id == 1)" :href="route('event_bookings.index')" :class="$page.component == 'EventBookings/Index' ? 'font-semibold' : 'text-gray-700'">Bookings</Link>
                            <Link :href="route('events.index')" :class="$page.component == 'Events/Index' ? 'font-semibold' : 'text-gray-700'">Events</Link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-2xl text-right w-fit bold mx-auto text-white font-custom z-20 md:text-center md:text-4xl">PLANIT</div>
        </div>
        
        <div
            style="background-image: url('/images/unsplash/planit/oxana-v-qoAIlAmLJBU-unsplash.jpg');"
            class="flex relative bg-cover bg-bottom w-screen h-48 brightness-110"
            >
            <div
                id="registerAttendeeWord"
                class="absolute bottom-4 text-2xl font-semibold text-white w-full text-center font-custom"
                >
                {{ verbs[0] }}
            </div>
            <Link
                href="/register/attendee"
                type="button"
                class="inline-block text-extrabold text-2xl text-white text-center py-2 px-4 m-auto border-2 border-white bg-gray-600/40"
            >
                Register an Account
            </Link>
        </div>
        <div class="relative bg-white overflow-clip">
            <div class="lg:pl-8">
                <div class="flex justify-between w-full py-3 px-4 bg-white lg:justify-start lg:gap-8">
                    <div class="text-xl font-semibold font-custom md:text-2xl">Recent Events</div>
                    <Link :href="route('events.index')" class="text-lg underline text-gray-700 px-2">View all</Link>
                </div>
                <div class="flex flex-nowrap overflow-x-scroll py-3 px-8 gap-2 relative z-5"
                    style="z-index: 5;"
                    >
                    <Link
                        v-for="event in activeEvents"
                        :href="route('events.show', [event.slug])"
                        class="relative block w-48"
                    >
                        <div :style="'background-image: url(' + '\'' + '/event_images/' + event.src + '\'' + ');'"
                                class="bg-cover bg-bottom h-60 w-48 shadow-md"
                        ></div>
                        <h2 class="absolute bottom-2 left-2 text-white text-2xl font-bold w-40 overflow-hidden">
                            {{ event.title.toUpperCase() }}
                        </h2>
                    </Link>
                    <Link :href="route('events.index')">
                        <div class="relative h-60 w-48 px-8 my-auto bg-yellow-400/40 border-4 rounded border-white border-tr-full overflow-clip">
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
            <div class="absolute top-32 -right-12 bg-gradient-to-b from-yellow-500 via-yellow-400 via-15% via-yellow-400 via-40% to-yellow-400 w-[125%] h-96 rounded-tr-full overflow-hidden"></div>
            <div class="relative h-80 grid grid-cols-1 place-content-evenly gap-2 mt-8 px-4 py-8 bg-yellow-400 z-10 md:grid-cols-2 md:place-content-center md:py-0">
                <svg class="absolute w-full h-80 md:relative md:row-span-3 md:ml-auto" style="z-index: -5;">
                    <use href="/icons/united-kingdom.svg#Capa_1" />
                </svg>
                <div class="text-lg text-right pl-2 mt-auto bg-yellow-400 rounded-full md:text-left">
                    <span class="text-2xl text-white">See</span> your events on here
                </div>
                <Link href="/register/planner" type="button" class="w-fit my-auto mx-auto text-bold text-2xl py-2 px-6 border-4 border-yellow-600 bg-white md:mx-0">
                    Register as a <span class="underline text-gray-800">Planner</span>
                </Link>
                <div class="text-lg font-bold text-gray-800 bg-yellow-400">
                    <span class="text-2xl text-white">100+</span> Events already listed nationwide
                </div>
            </div>
        </div>
    </div>
</template>