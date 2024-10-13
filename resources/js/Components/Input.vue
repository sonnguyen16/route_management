<script setup>
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    type: String,
    errors: String,
    placeholder: String,
    disabled: {
        type: Boolean,
        default: false,
    },
})

const model = defineModel();
</script>

<template>
    <div>
        <input v-model="model" :type="props.type" :disabled="disabled"
               :placeholder="props.placeholder"
               :class="['form-control', errors && 'border border-danger']" />
        <InputError :message="errors" />
    </div>
</template>
