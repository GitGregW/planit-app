<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <div class="col-span-7 text-lg font-semibold">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
        <slot />
    </div>
</template>