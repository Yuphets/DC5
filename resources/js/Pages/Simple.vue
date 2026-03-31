<template>
    <div>
        <h1>Simple Test</h1>
        <p>{{ message }}</p>
    </div>
</template>
<script setup>
defineProps({ message: String });
</script>