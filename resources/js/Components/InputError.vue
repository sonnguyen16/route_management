<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div class="h-[10px] d-flex align-items-center">
        <p class="text-sm text-red-600 mb-0">
            {{ message }}
        </p>
    </div>
</template>
