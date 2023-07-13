<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        
        <!-- Page Heading -->
        <header class="bg-gray-200 shadow" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-3 text-center">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <main class="relative pb-16 overflow-clip">
            <slot />
        </main>

        <div class="fixed bottom-0 w-full">
            <div class="flex justify-between p-2 bg-yellow-400">
                <Link href="/dashboard">
                    <svg class="w-8 h-8 mx-2 fill-white">
                        <use href="/icons/planet-earth.svg#Layer_1" />
                    </svg>
                </Link>
                <div class="flex justify-between gap-4 pr-2 my-auto">
                    <Link :href="route('events.index')" :class="$page.component == 'Events/Index' ? 'text-white font-semibold underline' : 'text-gray-700'">Events</Link>
                    <span class="text-gray-700">Bookings</span>
                    <!-- Tbc. -->
                    <span class="text-gray-700">
                        {{ $page.props.auth.user.name }}
                        <svg class="inline stroke-gray-700 fill-none stroke-2 w-4 h-4">
                            <use href="/icons/feather-sprite.svg#chevron-down" />
                        </svg>
                    </span>
                    <!-- Account Settings, Logout -->
                </div>
            </div>
        </div>
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
</template>