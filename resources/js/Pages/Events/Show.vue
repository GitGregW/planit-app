<script setup>
defineProps({
    event: {
        type: Array
    }
});
</script>
<template>
    <div>Events/Show.</div>
    <div>{{ $event.title }}</div>
</template>