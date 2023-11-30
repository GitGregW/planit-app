<script setup>
    const props = defineProps({
        value: {
            type: Number,
            default: 0
        },
        closed: {
            type: Boolean,
            default: false
        }
    });

    var colour = 'bg-white';
    if(props.closed){
        colour = 'bg-gray-200 text-gray-400';
    }
    if(!props.value){
        colour = 'bg-gray-300 text-gray-400';
    }
    
</script>
<template>
    <div :class="'relative h-10 text-center my-auto text-sm ' + colour">
        {{ props.value ? props.value : '' }}
        <slot />
    </div>
</template>