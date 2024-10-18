<script setup>
import {router, useForm} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import {ref, watch} from "vue";
import Input from "@/Components/Input.vue";
import {danhMucTaiLieuOptions} from "@/Constants/constants.js";
import {formatDate} from "@/assets/js/script.js";
import Upload from "@/Components/Upload.vue";
import File from "@/Components/File.vue";

const props = defineProps({
    cap_phep: Object,
    tuyen_duong: Object,
    don_vi: Object,
    isEdit: {
        type: Boolean,
        default: false
    },
    keyModal: Number
})

const emits = defineEmits(['closeModal', 'refresh', 'fileChange']);

const form = useForm({
    id: '',
    don_vi_id: '',
    tuyen_duong_id: '',
    so_cap_phep: '',
    ngay_cap_phep: '',
})

const submit = () => {
    form.post(route('cap-phep.store'), {
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

watch(() => props.keyModal, () => {
    if(props.cap_phep) {
        Object.assign(form, props.cap_phep);
        formFile.tuyen_duong_id = props.cap_phep.tuyen_duong_id;
        formFile.danh_muc = danhMucTaiLieuOptions.cap_phep;
        uploadedFiles.value = props.cap_phep.tai_lieu;
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
                        <div :class="['px-4 pt-4', isEdit ? 'col-md-8' : 'col-md-12']">
                            <div class="form-group">
                                <label for="loai_tuyen_duong">Tuyến đường</label>
                                <Select v-model="form.tuyen_duong_id"
                                        :errors="form.errors.tuyen_duong_id"
                                        :options="tuyen_duong"
                                        id="tuyen_duong_id"
                                        option-default="Chọn tuyến đường"/>
                            </div>
                            <div class="form-group">
                                <label for="ma_phan_cap">Đơn vị cấp phép</label>
                                <Select v-model="form.don_vi_id"
                                        :errors="form.errors.don_vi_id"
                                        :options="don_vi"
                                        id="don_vi_id"
                                        option-default="Chọn đơn vị"/>
                            </div>
                            <div class="form-group">
                                <label for="so_cap_phep">Số cấp phép</label>
                                <Input v-model="form.so_cap_phep"
                                       :errors="form.errors.so_cap_phep"/>
                            </div>

                            <div class="form-group">
                                <label for="ngay_cap_phep">Ngày cấp phép</label>
                                <Input v-model="form.ngay_cap_phep"
                                       type="date"
                                       :errors="form.errors.ngay_cap_phep"/>
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
                                            {{ formatDate(cap_phep.created_at.toString()) }}
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
