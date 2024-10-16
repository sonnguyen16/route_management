<script setup>

import {router, useForm} from "@inertiajs/vue3";
import {
    danhMucTaiLieuOptions,
    loaiOptions,
    loaiTuanTraOptions,
    maPhanCapOptions}
    from "@/Constants/constants.js";
import Select from "@/Components/Select.vue";
import Input from "@/Components/Input.vue";
import File from "@/Components/File.vue";
import {computed, ref, watch} from "vue";
import Upload from "@/Components/Upload.vue";
import {formatDate} from "@/assets/js/script.js";

const props = defineProps({
    huyen: Object,
    tuyen_duong: Object,
    don_vi: Object,
    isEdit: {
        type: Boolean,
        default: false
    },
})

const emits = defineEmits(['closeModal', 'refresh', 'fileChange']);
const closeModal = () => {
    emits('closeModal');
    form.reset();
    formFile.reset();
    form.clearErrors();
}

let form = useForm({
    id: '',
    ten: '',
    loai: '',
    ma_phan_cap: '',
    diem_dau_huyen_id: '',
    diem_cuoi_huyen_id: '',
    diem_dau_lat: '',
    diem_dau_lng: '',
    diem_dau_xa_id: '',
    diem_cuoi_xa_id: '',
    diem_cuoi_lat: '',
    diem_cuoi_lng: '',
    chieu_dai: '',
    chieu_rong: '',
    dien_tich: '',
    loai_tuan_tra: '',
    don_vi_id: '',
    xi_nghiep: '',
    huyen_id: '',
})

let formFile = useForm({
    tuyen_duong_id: '',
    danh_muc: null,
    file: []
})

const dau_xa = computed(() => {
    if(form.diem_dau_huyen_id) {
        return props.huyen.find(h => h.id === form.diem_dau_huyen_id).xa
    }
})

const cuoi_xa = computed(() => {
    if(form.diem_cuoi_huyen_id) {
        return props.huyen.find(h => h.id === form.diem_cuoi_huyen_id).xa
    }
})

watch(() => props.tuyen_duong, (value) => {
    if(value) {
        Object.assign(form, value);
        formFile.tuyen_duong_id = value.id;
        formFile.danh_muc = danhMucTaiLieuOptions.tuyen_duong;
        uploadedFiles.value = value.tai_lieu;
    }else{
        form.reset();
        formFile.reset();
    }
})

const submit = () => {
    form.post(route('tuyen-duong.store'), {
        onSuccess: () => {
            closeModal()
            emits('refresh')
        },
        onError: (err) => {
            console.log(err)
        }
    })
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
                                <label for="ten_tuyen_duong">Tên đường</label>
                                <Input v-model="form.ten" :errors="form.errors.ten" />
                            </div>
                            <div class="form-group">
                                <label for="loai_tuyen_duong">Loại</label>
                                <Select v-model="form.loai"
                                        :errors="form.errors.loai"
                                        :options="loaiOptions"
                                        option-default="Chọn loại"/>
                            </div>
                            <div class="form-group">
                                <label for="ma_phan_cap">Mã phân cấp</label>
                                <Select v-model="form.ma_phan_cap"
                                        :errors="form.errors.ma_phan_cap"
                                        :options="maPhanCapOptions"
                                        option-default="Chọn mã phân cấp"/>
                            </div>

                            <div class="form-group">
                                <label for="diem_dau_huyen_id">Điểm đầu</label>
                                <div class="grid grid-cols-2 gap-x-3">
                                    <Select v-model="form.diem_dau_huyen_id"
                                            :errors="form.errors.diem_dau_huyen_id"
                                            :options="huyen"
                                            option-default="Chọn huyện"/>
                                    <Select v-model="form.diem_dau_xa_id"
                                            :errors="form.errors.diem_dau_xa_id"
                                            :options="dau_xa"
                                            option-default="Chọn xã"/>
                                    <Input v-model="form.diem_dau_lat" :errors="form.errors.diem_dau_lat" />
                                    <Input v-model="form.diem_dau_lng" :errors="form.errors.diem_dau_lng" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="diem_cuoi_huyen_id">Điểm cuối</label>
                                <div class="grid grid-cols-2 gap-x-3">
                                    <Select v-model="form.diem_cuoi_huyen_id"
                                            :errors="form.errors.diem_cuoi_huyen_id"
                                            :options="huyen"
                                            option-default="Chọn huyện"/>
                                    <Select v-model="form.diem_cuoi_xa_id"
                                            :errors="form.errors.diem_cuoi_xa_id"
                                            :options="cuoi_xa"
                                            option-default="Chọn xã"/>
                                    <Input v-model="form.diem_cuoi_lat" :errors="form.errors.diem_cuoi_lat" />
                                    <Input v-model="form.diem_cuoi_lng" :errors="form.errors.diem_cuoi_lng" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="chieu_dai">Chiều dài</label>
                                <Input v-model="form.chieu_dai" :errors="form.errors.chieu_dai" />
                            </div>

                            <div class="form-group">
                                <label for="chieu_rong">Chiều rộng</label>
                                <Input v-model="form.chieu_rong" :errors="form.errors.chieu_rong" />
                            </div>

                            <div class="form-group">
                                <label for="dien_tich">Diện tích</label>
                                <Input v-model="form.dien_tich" :errors="form.errors.dien_tich" />
                            </div>

                            <div class="form-group">
                                <label for="loai_tuan_tra">Loại tuần tra</label>
                                <Select v-model="form.loai_tuan_tra"
                                        :errors="form.errors.loai_tuan_tra"
                                        :options="loaiTuanTraOptions"
                                        option-default="Chọn loại tuần tra"/>
                            </div>

                            <div class="form-group">
                                <label for="don_vi_quan_ly">Đơn vị quản lý</label>
                                <Select v-model="form.don_vi_id"
                                        :errors="form.errors.don_vi_id"
                                        :options="props.don_vi"
                                        option-default="Chọn đơn vị"/>
                            </div>

                            <div class="form-group">
                                <label for="xi_nghiep">Xí nghiệp</label>
                                <Input v-model="form.xi_nghiep" :errors="form.errors.xi_nghiep" />
                            </div>

                            <div class="form-group">
                                <label for="huyen_id">Huyện</label>
                                <Select v-model="form.huyen_id"
                                        :errors="form.errors.huyen_id"
                                        :options="huyen"
                                        option-default="Chọn huyện"/>
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
                                            {{ formatDate(form.created_at.toString()) }}
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
                            <div class="flex-1 overflow-y-auto max-h-[550px] mt-2">
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
                    <button type="submit" class="btn btn-success">Lưu</button>
                    <button type="reset"
                            class="btn btn-secondary ml-2">
                        Hủy
                    </button>
                </div>
            </div>

        </div>
    </div>
</template>
