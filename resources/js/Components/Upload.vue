<script setup>
const emits = defineEmits(['fileChange', 'submit', 'close']);
const onFileChange = (e) => {
    emits('fileChange', e.target.files);
}
</script>

<template>
    <div class="space-y-2">
        <label style="font-weight: normal" for="file"
               class="border-0 p-2 bg-white w-full text-start rounded-md mb-0">
            <i class="fa fa-paperclip mr-2"></i>
            Tải lên tệp
        </label>
        <input @change="onFileChange" id="file" type="file" class="hidden" multiple>
        <button @click.prevent="emits('submit')" class="border-0 p-2 bg-white w-full text-start rounded-md">
            <i class="fa fa-save mr-2"></i>
            Lưu thay đổi
        </button>
        <button @click.prevent="emits('close')" class="border-0 p-2 bg-white w-full text-start rounded-md">
            <i class="fa fa-times-circle mr-1"></i>
            Thoát
        </button>
    </div>
</template>

