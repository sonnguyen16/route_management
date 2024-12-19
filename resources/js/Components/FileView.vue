<script setup>
import {ref} from "vue";
import {formatDate} from "../assets/js/script.js";
import {MEDIA_ENDPOINT, iconFileTypes} from "@/Constants/constants.js";
import {useForm} from "@inertiajs/vue3";
const props = defineProps({
    file: Object,
    enableDelete: {
        type: Boolean,
        default: true
    }
})

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
<div class="hover:bg-gray-200"> <!-- rounded p-1-->
    <table style="width: 100%;;">
        <tr>
            <td>
                <img v-if="[ 'jpg', 'png', 'jpeg' ].includes(file.loai)"
                :src="MEDIA_ENDPOINT + file.file"
                style="width: 20px; height: 15px;"
                alt="image"
                ><i v-else class="fa text-[80px] text-gray-500" :class="[iconFileTypes[file.loai]]"></i>
            </td>
            <td>
                <a :href="MEDIA_ENDPOINT + file.file"
                   target="_blank"
                   class="hover:text-indigo-500 border-0 p-[1px] rounded-md text-gray-500 actions">
                {{ file.ten }}
                </a>
            </td>
            <td class="text-right" >
                <button v-if="enableDelete" @click="emits('removeFileUpload', file.id)" style="padding-right: 5px;"
                        class="hover:text-indigo-500 border-0 p-[1px] rounded-md text-gray-500 actions">
                    Xóa
                </button>
            </td>
        </tr>
    </table>
</div>
</template>

<style scoped>
.actions{
    text-decoration: underline;
}
</style>
