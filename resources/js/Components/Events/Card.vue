<script setup>
    defineProps({
        event: Object
    });
</script>

<template>
    <img :src="'/event_images/' + event.src" class="w-full object-cover h-72 pb-10 lg:h-96 lg:col-span-3 lg:pb-0" />
    <div class="absolute top-0 w-full bg-black/40 text-white">
        <h1 class="w-full pl-6 pr-4 py-2 font-bold text-2xl">
            {{ event.title }}
        </h1>
    </div>
    <div class="absolute text-ellipsis text-justify overflow-hidden h-24 leading-5 line-clamp-4 px-2 py-3 bottom-0 text-gray-800 bg-gradient-to-t from-white from-95% transition-[padding] duration-500
        lg:relative lg:h-full lg:top-0 lg:-ml-6 lg:p-3 lg:bg-white lg:line-clamp-[12]">
        {{ event.description }}
    </div>
</template>