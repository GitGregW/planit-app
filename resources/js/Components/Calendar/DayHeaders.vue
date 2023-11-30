<script setup>
    const dayHeaders = ["Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"];
</script>

<template>
    <div v-for="dayHeader in dayHeaders" class="text-grey-700 bg-white/50 py-1 border-y-2 border-yellow-400">
        {{ dayHeader }}
    </div>
</template>