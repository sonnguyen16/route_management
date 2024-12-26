<script setup>
import {router, useForm} from "@inertiajs/vue3";
import File from "@/Components/FileView.vue";
import {onMounted, ref, watch} from "vue";

const props = defineProps({
    listFile: Object,
})
const emits = defineEmits([ 'refresh']);// 'fileChange',

const removeFileUploaded = (id) => {    
    router.delete(route('tai-lieu.delete', {id: id}), {
        onSuccess: () => {
            emits('refresh')
        }
    })
}
</script>

<template>
    <div class="space-y-2" >        
        <div class="flex-1 overflow-y-auto max-h-[280px] mt-2">
            <File v-for="file in props.listFile"
                :key="file.id"
                :file="file"
                @remove-file-upload="removeFileUploaded"
                @refresh="emits('refresh')"
            />
        </div>
    </div>
</template>

