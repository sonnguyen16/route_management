<script setup>
import InputError from "@/Components/InputError.vue";
const props = defineProps({
    options: Object,
    optionDefault: String,
    errors: String
})

const model = defineModel();
</script>

<template>
    <div>
        <select v-model="model" :class="['form-control', errors && 'border border-danger']">
            <option value="">{{ optionDefault }}</option>
            <option v-for="option in options"
                    :value="option.id"
                    :key="option.id">{{ option.name }}</option>
        </select>
        <InputError :message="errors" />
    </div>
</template>

<style scoped>

</style>
