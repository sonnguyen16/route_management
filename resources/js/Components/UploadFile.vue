<script setup>
import {router, useForm} from "@inertiajs/vue3";
import File from "@/Components/FileView.vue";
import {onMounted, ref, watch} from "vue";

const props = defineProps({
    type: String,
    danh_muc: String,
    listFile: Object,
})
const danh_muc = ref(null);
onMounted(() => {
   console.log('abc');
   danh_muc.value = props.danh_muc;
   console.log(danh_muc.value);
   formFile.danh_muc = props.danh_muc;
   formFile.type = props.type;
//   console.log(danh_muc);
})

const emits = defineEmits([ 'refresh']);// 'fileChange',

let formFile = useForm({
    type: '',
    danh_muc: '',
    file: []
})
watch(() => props.type, () => {
    
    console.log('alo');
})

const onFileChange = (e) => {
  //  console.log(props);
    formFile.file = e.target.files;
    formFile.type = props.type;
    console.log('aaa');
    console.log(formFile.danh_muc);
    /*
    formFile.post(route('tai-lieu.store'), {
        onSuccess: () => {
            emits('refresh')
        },
        onError: (err) => {
            console.log(err)
        }
    }) */
   // emits('fileChange', e.target.files);
}

const removeFileUploaded = (id) => {    
    router.delete(route('tai-lieu.delete', {id: id}), {
        onSuccess: () => {
            emits('refresh')
        }
    })
}

</script>

<template>
    <div class="space-y-2">
        <label style="font-weight: normal;color: #007bff;" for="file"
               class="cursor-pointer border-0 w-full text-start rounded-md mb-0">
            <i class="fa fa-paperclip mr-2"></i>
            Tải lên tệp {{ props.danh_muc }}
        </label>
        <Input v-model="formFile.danh_muc"/>
       
        <input  @change="onFileChange" id="file" type="file" class="hidden" multiple>
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

