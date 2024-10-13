<script setup>
    defineProps({
        title: {
            required: true,
            type: String
        }
    })
</script>

<template>
    <a href="#" class="nav-link">
        <p class="txt-color font-weight-bold">
            {{ title }}
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
</template>
