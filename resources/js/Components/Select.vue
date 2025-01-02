<script setup>
import InputError from "@/Components/InputError.vue";
import Choices from "choices.js";
import {defineProps, defineModel, onMounted, watch, onUnmounted} from 'vue';

const props = defineProps({
    options: Object,
    optionDefault: String,
    errors: String,
    id: String,
});

const model = defineModel();
let choice = null;

onMounted(() => {
   // model.value = 19;
    
    const selectElement = document.getElementById(props.id);
    choice = new Choices(selectElement);
    choice.setChoices(props.options, 'id', props?.options?.[0]?.name ? 'name' : 'ten', true);
});

watch(() => props.options, (newOptions) => {
   if (choice) {
        choice.setChoiceByValue('');
        choice.clearChoices();
        if(newOptions) {
            choice.setChoices(newOptions, 'id', newOptions[0]?.name ? 'name' : 'ten', true);
        }
       
        choice.setChoiceByValue(model.value);
    }
});

watch(() => model.value, () => {
    choice.setChoiceByValue(model.value);
});

</script>

<template>
    <div>
       <select :id="id" v-model="model" :class="['form-control', errors && 'border border-danger']">
            <option value="">{{ optionDefault }}</option>
        </select>
        <InputError :message="errors" />
    </div>
</template>
