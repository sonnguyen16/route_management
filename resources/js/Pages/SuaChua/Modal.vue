<script setup>
import Input from "@/Components/Input.vue";
import {router, useForm} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import {ref, watch} from "vue";
import {danhMucTaiLieuOptions, loaiSuaChuaOptions} from "@/Constants/constants.js";
import {formatDate} from "@/assets/js/script.js";
import Upload from "@/Components/Upload.vue";
import File from "@/Components/File.vue";

const props = defineProps({
    sua_chua: Object,
    tuyen_duong: Object,
    don_vi: Object,
    nguoi_duyet: Object,
    isEdit: {
        type: Boolean,
        default: false
    },
    keyModal: Number
})

const emits = defineEmits(['closeModal', 'refresh', 'fileChange']);

let form = useForm({
    id: '',
    tuyen_duong_id: '',
    loai_sua_chua: '',
    tu_km: '',
    den_km: '',
    ngay_duyet: '',
    nguoi_duyet_id: '',
    ngay_khoi_cong: '',
    ngay_hoan_thanh: '',
    don_vi_id: '',
    tai_lieu: [],
})


const submit = () => {
    form.post(route('sua-chua.store'), {
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
    if(props.sua_chua) {
        Object.assign(form, props.sua_chua);
        formFile.tuyen_duong_id = props.sua_chua.tuyen_duong_id;
        formFile.danh_muc = danhMucTaiLieuOptions.sua_chua;
        uploadedFiles.value = props.sua_chua.tai_lieu;
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
                                <label for="tuyen_duong_id">Tuyến đường</label>
                                <Select
                                    v-model="form.tuyen_duong_id"
                                    :options="tuyen_duong"
                                    :errors="form.errors.tuyen_duong_id"
                                    id="tuyen_duong_id"
                                    option-default="Chọn tuyến đường"
                                />
                            </div>

                            <div class="form-group">
                                <label for="loai_sua_chua">Loại sửa chữa</label>
                                <Select
                                    v-model="form.loai_sua_chua"
                                    :options="loaiSuaChuaOptions"
                                    :errors="form.errors.loai_sua_chua"
                                    id="loai_sua_chua"
                                    option-default="Chọn loại sửa chữa"
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
                                <label for="nguoi_duyet_id">Người duyệt</label>
                                <Select
                                    v-model="form.nguoi_duyet_id"
                                    :options="nguoi_duyet"
                                    :errors="form.errors.nguoi_duyet_id"
                                    id="nguoi_duyet_id"
                                    option-default="Chọn người duyệt"
                                />
                            </div>

                            <div class="form-group">
                                <label for="don_vi_id">Đơn vị thi công</label>
                                <Select
                                    v-model="form.don_vi_id"
                                    :options="don_vi"
                                    :errors="form.errors.don_vi_id"
                                    id="don_vi_id"
                                    option-default="Chọn đơn vị thi công"
                                />
                            </div>

                            <div class="form-group">
                                <label for="ngay_duyet">Ngày duyệt</label>
                                <Input
                                    v-model="form.ngay_duyet"
                                    type="date"
                                    :errors="form.errors.ngay_duyet"
                                />
                            </div>

                            <div class="form-group">
                                <label for="ngay_khoi_cong">Ngày khởi công</label>
                                <Input
                                    v-model="form.ngay_khoi_cong"
                                    type="date"
                                    :errors="form.errors.ngay_khoi_cong"
                                />
                            </div>

                            <div class="form-group">
                                <label for="ngay_hoan_thanh">Ngày hoàn thành</label>
                                <Input
                                    v-model="form.ngay_hoan_thanh"
                                    type="date"
                                    :errors="form.errors.ngay_hoan_thanh"
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
                                            {{ formatDate(sua_chua.created_at.toString()) }}
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
                            <div class="flex-1 overflow-y-auto max-h-[280px] mt-2">
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
