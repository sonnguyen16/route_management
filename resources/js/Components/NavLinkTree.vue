<script setup>
defineProps({
    title: {
        required: true,
        type: String
    }
})
</script>

<template>
    <a href="" class="nav-link">
        <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">
        <p>
            {{title}}
            <i class="fas fa-angle-left right" style="margin-right: 16px;"></i>
        </p>
    </a>
</template>

