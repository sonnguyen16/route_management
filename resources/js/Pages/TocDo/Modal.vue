<script setup>
import Input from "@/Components/Input.vue";
import {router, useForm} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import {ref, watch} from "vue";
import FilePreview from "@/Components/FilePreview.vue";

const props = defineProps({
    gioi_han_toc_do: Object,
    tuyen_duong: Object,
    don_vi: Object,
    nguoi_duyet: Object,
})

const emits = defineEmits(['closeModal', 'refresh']);
const closeModal = () => {
    emits('closeModal');
    form.reset();
    form.clearErrors();
}

let form = useForm({
    id: '',
    tuyen_duong_id: '',
    tu_km: '',
    den_km: '',
    toc_do: '',
    tai_lieu: [],
})

watch(() => props.gioi_han_toc_do, (value) => {
    if(value) {
        Object.assign(form, value);
        uploadedFiles.value = value.tai_lieu;
    }else{
        form.reset();
    }
})

const uploadedFiles = ref([]);
const selectedFiles = ref([]);

const submit = () => {
    form.tai_lieu = selectedFiles.value;
    form.post(route('gioi-han-toc-do.store'), {
        onSuccess: () => {
            closeModal()
            emits('refresh')
            selectedFiles.value = []
        },
        onError: (err) => {
            console.log(err)
        }
    })
}

const addFiles = (files) => {
    selectedFiles.value = files;
}

const removeFileUploaded = (id) => {
    uploadedFiles.value = uploadedFiles.value.filter(file => file.id !== id)
    router.delete(route('tai-lieu.delete', {id: id}), {
        onSuccess: () => {
            emits('refresh')
            selectedFiles.value = []
        }
    })
}

const removeFileSelected = (index) => {
    selectedFiles.value.splice(index, 1)
}

</script>

<template>
    <div id="modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pt-2">
                    <h5 class="modal-title text-primary">
                        <span v-if="form.id">Chỉnh sửa</span>
                        <span v-else>Thêm mới</span>
                    </h5>
                    <button type="button" class="btn-close" @click.prevent="closeModal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="submit">
                    <div class="modal-body mt-3">
                        <div class="form-group">
                            <label for="tuyen_duong_id">Tuyến đường</label>
                            <Select
                                v-model="form.tuyen_duong_id"
                                :options="tuyen_duong"
                                :errors="form.errors.tuyen_duong_id"
                                option-default="Chọn tuyến đường"
                            />
                        </div>


                        <div class="form-group">
                            <label for="tu_km">Từ km</label>
                            <Input
                                v-model="form.tu_km"
                                type="number"
                                :errors="form.errors.tu_km"
                            />
                        </div>

                        <div class="form-group">
                            <label for="den_km">Đến km</label>
                            <Input
                                v-model="form.den_km"
                                type="number"
                                :errors="form.errors.den_km"
                            />
                        </div>

                        <div class="form-group">
                            <label for="toc_do">Tốc độ</label>
                            <Input
                                v-model="form.toc_do"
                                type="number"
                                :errors="form.errors.toc_do"
                            />
                        </div>

                        <div class="form-group">
                            <label for="tai_lieu">Tài liệu</label>
                            <FilePreview
                                :existing-files="uploadedFiles"
                                :selected-files="selectedFiles"
                                @add-files="addFiles"
                                @remove-file-selected="removeFileSelected"
                                @remove-file-uploaded="removeFileUploaded"
                            />
                        </div>
                    </div>
                    <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Lưu</button>
                            <button type="reset"
                                    @click.prevent="closeModal"
                                    class="btn btn-secondary ml-2">
                                Hủy
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
