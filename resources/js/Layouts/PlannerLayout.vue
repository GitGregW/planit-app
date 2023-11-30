<script setup>
    import { ref } from 'vue';
    import { Link, usePage } from '@inertiajs/vue3';

    const page = usePage()

    function toggleToolbar()
    {
        const toolbar = document.getElementById('toolbar');
        toolbar.style.marginBottom == '0px'
            ? toolbar.style.marginBottom = '-17rem'
            : toolbar.style.marginBottom = '0px';

        const chevron = document.getElementById('toolbar-svg');
        toolbar.style.marginBottom == '0px'
            ? chevron.setAttribute('href', '/icons/feather-sprite.svg#chevron-down')
            : chevron.setAttribute('href', '/icons/feather-sprite.svg#chevron-right');
    }

    function manageFlashMessage(message){
        setTimeout(() => {
            page.props.flash.message = '';
        }, 5000);
        
        return message;
    }
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            
            <!-- Page Heading -->
            <header class="bg-gray-200 shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-3 text-center">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="relative overflow-clip pb-12">
                <Transition enter-active-class="ease-out duration-700"
                    enter-from-class="opacity-0 -translate-y-12 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-500"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 -translate-y-12 sm:translate-y-0 sm:scale-95"
                >
                    <div v-if="$page.props.flash.message" id="flashMessage" class="transform transition-all top-2 z-20 fixed w-fit mx-2 py-1 px-3 font-bold text-lg bg-white border-4 border-red-300 rounded-sm">
                        {{ manageFlashMessage($page.props.flash.message) }}
                    </div>
                </Transition>
                <slot />

                <div id="toolbar" class="fixed bottom-0 max-w-2/5 transition-[margin] ease-in-out duration-500" style="z-index:40; margin-bottom: -17rem">
                    <div @click="toggleToolbar()" class="bg-yellow-400 rounded-tr-2xl py-2 px-3 text-center">
                        <div class="inline-block text-white text-center font-semibold text-ellipsis overflow-hidden whitespace-nowrap">
                            {{ $page.props.auth.user.name }}
                        </div>
                        <div class="relative my-auto">
                            <svg class="w-16 h-16 mx-auto fill-white">
                                <use href="/icons/planet-earth.svg#Layer_1" />
                            </svg>
                            <svg class="absolute right-0 top-0 stroke-white fill-none stroke-2 w-6 h-6">
                                <use id="toolbar-svg" href="/icons/feather-sprite.svg#chevron-down" />
                            </svg>
                        </div>
                    </div>
                    <div id="toolbar-links" class="flex flex-col gap-3 mx-auto px-2 text-lg bg-white h-56 overflow-y-scroll">
                        <Link :href="route('logout')" method="post" as="button" class="text-sm text-center text-gray-700 mb-1 py-1 border-b-2 border-gray-300">sign out</Link>
                        <Link :href="route('dashboard')" :class="($page.component == 'DashboardPlanner' || $page.component == 'Dashboard') ? 'font-semibold' : 'text-gray-700'">Dashboard</Link>
                        <Link v-if="$page.props.auth.user.user_group_id == 1" :href="route('event_bookings.index')" :class="$page.component == 'EventBookings/Index' ? 'font-semibold' : 'text-gray-700'">Bookings</Link>
                        <Link :href="route('events.index')" :class="$page.component == 'Events/Index' ? 'font-semibold' : 'text-gray-700'">Events</Link>
                    </div>
                </div>
            </main>

            <!-- <div class="">
                <div class="grid grid-cols-3 bg-yellow-200 py-1 px-2">
                    <div class="col-span-3 text-gray-900">PLANIT
                        <span class="text-xs">copyrigh.</span>
                    </div>
                    <div class="">
                    </div>
                    <div class="text-right">
                        <a class="block text-gray-900 text-xs">What We Do</a>
                        <a class="block text-gray-900 text-xs">Meet The Team</a>
                    </div>
                    <div class="text-right">
                        <a class="block text-gray-900 text-xs">Contact</a>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</template>