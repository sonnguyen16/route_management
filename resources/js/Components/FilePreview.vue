<template>
    <div class="">
        <!-- File Input -->
        <div class="mb-2">
            <label
                for="fileInput"
                class="cursor-pointer bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
            >
                Chọn file
            </label>
            <input
                type="file"
                id="fileInput"
                class="hidden"
                multiple
                @change="handleFileSelect"
            />
        </div>

        <!-- Preview Selected Files -->
        <div v-if="selectedFiles?.length" class="mb-2">
            <h3 class="font-bold text-lg mb-2">Tài liệu vừa tải lên</h3>
            <ul class="space-y-2">
                <li
                    v-for="(file, index) in selectedFiles"
                    :key="index"
                    class="flex justify-between items-center border rounded p-2"
                >
                    <span>
                        <i :class="['fa me-2 text-gray-500', iconFileTypes[file.name.split('.').pop()]]"></i>
                        {{ file.name }}
                    </span>
                    <button
                        @click.prevent="removeFile(index, 'selected')"
                        class="text-red-500 hover:text-red-700"
                    >
                        Xóa
                    </button>
                </li>
            </ul>
        </div>

        <!-- List of Existing Files -->
        <div v-if="existingFiles?.length" class="mb-2">
            <h3 class="font-bold text-lg mb-2">Tài liệu đã tải lên</h3>
            <ul class="space-y-2">
                <li
                    v-for="(file, index) in existingFiles"
                    :key="index"
                    class="flex justify-between items-center border rounded p-2"
                >
                    <a target="_blank" :href="'/storage/' + file.file">
                        <i :class="['fa me-2 text-gray-500', iconFileTypes[file.loai]]"></i>
                        {{ file.ten }}
                    </a>
                    <button
                        @click.prevent="removeFile(file.id, 'existing')"
                        class="text-red-500 hover:text-red-700"
                    >
                        Xóa
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>

const props = defineProps({
    existingFiles: Array,
    selectedFiles: Array,
});

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

const emits = defineEmits(['addFiles', 'removeFileUploaded', 'removeFileSelected']);
const handleFileSelect = (event) => {
    const files = Array.from(event.target.files);
    emits('addFiles', files);
};

const removeFile = (index, type) => {
    if (type === 'selected') {
        emits('removeFileSelected', index);
    } else if (type === 'existing') {
        emits('removeFileUploaded', index);
    }
};

</script>
