<script setup>
import InputError from "@/Components/InputError.vue";
import Choices from "choices.js";
import { defineProps, defineModel, onMounted, watch } from 'vue';

const props = defineProps({
    options: Object, // Hoặc Array nếu options là mảng
    optionDefault: String,
    errors: String,
    id: String,
});

const model = defineModel();
let choice = null;

onMounted(() => {
    const selectElement = document.getElementById(props.id);
    choice = new Choices(selectElement);
});

watch(() => props.options, (newOptions) => {
    if (choice) {
        choice.clearChoices();
        choice.setChoices(newOptions, 'id', 'name', true);
        choice.setChoiceByValue(model.value);
    }
});

watch(() => model.value, () => {
    choice.setChoiceByValue(model.value.toString());
});
</script>

<template>
    <div>
        <select :id="id" v-model="model" :class="['form-control', errors && 'border border-danger']">
            <option value="">{{ optionDefault }}</option>
            <option v-for="option in options"
                    :value="option.id"
                    :key="option.id">{{ option?.name || option?.ten }}</option>
        </select>
        <InputError :message="errors" />
    </div>
</template>

<style scoped>

</style>
