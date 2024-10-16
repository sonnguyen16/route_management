<script setup>
import Input from "@/Components/Input.vue";
import {router, useForm} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import {ref, watch} from "vue";
import {danhMucTaiLieuOptions} from "@/Constants/constants.js";
import {formatDate} from "@/assets/js/script.js";
import Upload from "@/Components/Upload.vue";
import File from "@/Components/File.vue";

const props = defineProps({
    duong_cam: Object,
    tuyen_duong: Object,
    don_vi: Object,
    isEdit: {
        type: Boolean,
        default: false
    },
})

const emits = defineEmits(['closeModal', 'refresh', 'fileChange']);

let form = useForm({
    id: '',
    tuyen_duong_id: '',
    don_vi_quyet_dinh_id: '',
    don_vi_thuc_hien_id: '',
    tu_ngay: '',
    den_ngay: '',
    ly_do: '',
})

const submit = () => {
    form.post(route('duong-cam.store'), {
        onSuccess: () => {
            closeModal()
            emits('refresh')
        },
        onError: (err) => {
            console.log(err)
        }
    })
}

let formFile = useForm({
    tuyen_duong_id: '',
    danh_muc: null,
    file: []
})

watch(() => props.duong_cam, (value) => {
    if(value) {
        Object.assign(form, value);
        formFile.tuyen_duong_id = value.tuyen_duong_id;
        formFile.danh_muc = danhMucTaiLieuOptions.duong_cam;
        uploadedFiles.value = value.tai_lieu;
    }else{
        form.reset();
        formFile.reset();
    }
})
const closeModal = () => {
    emits('closeModal');
    form.reset();
    formFile.reset();
    form.clearErrors();
}

const uploadedFiles = ref([]);
const removeFileUploaded = (id) => {
    uploadedFiles.value = uploadedFiles.value.filter(file => file.id !== id)
    router.delete(route('tai-lieu.delete', {id: id}), {
        onSuccess: () => {
            emits('refresh')
        }
    })
}

const uploadFiles = (files) => {
    formFile.file = files;
    formFile.post(route('tai-lieu.store'), {
        onSuccess: () => {
            emits('refresh')
        },
        onError: (err) => {
            console.log(err)
        }
    })
}

</script>

<template>
    <div class="modal fade" id="modal">
        <div
            class="modal-dialog modal-dialog-centered"
            :class="[isEdit ? 'modal-xl' : 'modal-lg']"
        >
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="row">
                        <!-- Main Content -->
                        <div :class="['p-4 pb-0', isEdit ? 'col-md-8' : 'col-md-12']">
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
                                <label for="tu_ngay">Từ ngày</label>
                                <Input
                                    v-model="form.tu_ngay"
                                    type="datetime-local"
                                    :errors="form.errors.tu_ngay"
                                />
                            </div>

                            <div class="form-group">
                                <label for="den_ngay">Đến ngày</label>
                                <Input
                                    v-model="form.den_ngay"
                                    type="datetime-local"
                                    :errors="form.errors.den_ngay"
                                />
                            </div>

                            <div class="form-group">
                                <label for="don_vi_quyet_dinh_id">Đơn vị quyết định</label>
                                <Select
                                    v-model="form.don_vi_quyet_dinh_id"
                                    :options="don_vi"
                                    :errors="form.errors.don_vi_quyet_dinh_id"
                                    option-default="Chọn đơn vị quyết định"
                                />
                            </div>

                            <div class="form-group">
                                <label for="don_vi_thuc_hien_id">Đơn vị thực hiện</label>
                                <Select
                                    v-model="form.don_vi_thuc_hien_id"
                                    :options="don_vi"
                                    :errors="form.errors.don_vi_thuc_hien_id"
                                    option-default="Chọn đơn vị thực hiện"
                                />
                            </div>

                            <div class="form-group">
                                <label for="ly_do">Lý do</label>
                                <Input
                                    v-model="form.ly_do"
                                    :errors="form.errors.ly_do"
                                />
                            </div>
                        </div>

                        <!-- Sidebar Actions -->
                        <div v-if="isEdit" class="col-md-4 bg-gray-100 p-3 flex flex-col">
                            <div class="mb-3">
                                <div class="flex items-center space-x-2">
                                    <img src="@/assets/img/avatar.jpg"
                                         alt="Avatar" class="rounded-full w-12 h-12">
                                    <div>
                                        <div class="font-semibold">Admin</div>
                                        <div class="text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ formatDate(duong_cam.created_at.toString()) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <Upload
                                @file-change="uploadFiles"
                                @submit="submit"
                                @close="closeModal"
                            />
                            <div class="font-bold mt-4 ">Tệp đã tải lên</div>
                            <div class="flex-1 overflow-y-auto max-h-[200px] mt-2">
                                <File v-for="file in uploadedFiles"
                                      :key="file.id"
                                      :file="file"
                                      @remove-file-upload="removeFileUploaded"
                                      @refresh="emits('refresh')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="!isEdit" class="modal-footer">
                    <button @click.prevent="submit" type="submit" class="btn btn-success">Lưu</button>
                    <button @click.prevent="closeModal" type="reset"
                            class="btn btn-secondary ml-2">
                        Hủy
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
