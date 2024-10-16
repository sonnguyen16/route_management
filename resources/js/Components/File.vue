<script setup>
import {ref} from "vue";
import {formatDate} from "../assets/js/script.js";
import {MEDIA_ENDPOINT} from "@/Constants/constants.js";
import {useForm} from "@inertiajs/vue3";
const props = defineProps({
    file: Object
})

const iconFileTypes = {
    pdf: 'fa-file-pdf',
    docx: 'fa-file-word',
    xlsx: 'fa-file-excel',
    xls: 'fa-file-excel',
    doc: 'fa-file-word',
    jpg: 'fa-file-image',
    png: 'fa-file-image',
    jpeg: 'fa-file-image',
}

const emits = defineEmits(['removeFileUpload', 'refresh']);
const edit = ref(false);
const caculateFileSize = () => {
    const size = props.file.dung_luong;
    if(size < 1024){
        return size + ' bytes';
    }else if(size < 1024 * 1024){
        return (size / 1024).toFixed(2) + ' KB';
    }else if(size < 1024 * 1024 * 1024){
        return (size / 1024 / 1024).toFixed(2) + ' MB';
    }else{
        return (size / 1024 / 1024 / 1024).toFixed(2) + ' GB';
    }
}

const form = useForm({
    id: props.file.id,
    mo_ta: props.file.mo_ta
})

const addDetail = () => {
    form.post(route('tai-lieu.update'), {
        preserveState: true,
        onSuccess: () => {
            edit.value = false;
            emits('refresh');
        }
    })
}

</script>

<template>
<div class="hover:bg-gray-200 rounded p-1">
    <div class="flex gap-3">
        <img v-if="[ 'jpg', 'png', 'jpeg' ].includes(file.loai)"
             :src="MEDIA_ENDPOINT + file.file"
             class="w-[60px] h-[80px] object-cover rounded-md"
             alt="image"
        >
        <i v-else class="fa text-[80px] text-gray-500" :class="[iconFileTypes[file.loai]]"></i>
        <div class="flex-1">
            <div class="font-semibold p-[1px]">{{ file.ten }}</div>
            <div class="text-gray-500 p-[1px]">
                {{ formatDate(file.created_at.toString()) }} | {{ caculateFileSize() }}
            </div>
            <div v-show="!edit" class="text-gray-500">
                <button @click.prevent="edit = !edit" class="hover:text-indigo-500 border-0 p-[1px] rounded-md text-gray-500">
                    {{ form.mo_ta ? form.mo_ta : 'Thêm mô tả' }}
                </button>
            </div>
            <div v-show="edit">
                <textarea v-model="form.mo_ta" placeholder="Mô tả" class="form-control mb-2"></textarea>
                <div class="flex gap-2 items-center">
                    <button @click.prevent="addDetail" class="py-[5px] px-[8px] btn-success rounded">Cập nhật mô tả</button>
                    <button @click.prevent="edit = !edit" class="text-xl">
                        &times;
                    </button>
                </div>
            </div>
            <div class="flex gap-2">
                <a :href="MEDIA_ENDPOINT + file.file"
                   target="_blank"
                   class="hover:text-indigo-500 border-0 p-[1px] rounded-md text-gray-500 actions">
                    Tải xuống
                </a>
                <button @click="emits('removeFileUpload', file.id)"
                        class="hover:text-indigo-500 border-0 p-[1px] rounded-md text-gray-500 actions">
                    Xóa
                </button>
            </div>
        </div>
    </div>
</div>
</template>

<style scoped>
.actions{
    text-decoration: underline;
}
</style>
