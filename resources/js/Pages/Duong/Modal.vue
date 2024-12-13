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
    loai_tuyen_duong: Object,
    phan_cap: Object,
    loai_tuan_tra: Object,
    isEdit: {
        type: Boolean,
        default: false
    },
    keyModal: Number
})

const emits = defineEmits(['closeModal', 'refresh', 'fileChange']);

let form = useForm({
    id: '',
    ten: '',
    loai_tuyen_duong_id: '',
    phan_cap_id: '',
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
    don_vi_id: '',
    lo_gioi: '',
})


const dau_xa = computed(() => {
    if(form.diem_dau_huyen_id) {
        return props.huyen.find(h => h.id == form.diem_dau_huyen_id).xa
    }
})

const cuoi_xa = computed(() => {
    if(form.diem_cuoi_huyen_id) {
        return props.huyen.find(h => h.id == form.diem_cuoi_huyen_id).xa
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

let formFile = useForm({
    tuyen_duong_id: '',
    danh_muc: null,
    file: []
})

watch(() => props.keyModal, () => {
     if(props.tuyen_duong) {
        Object.assign(form, props.tuyen_duong);
        formFile.tuyen_duong_id = props.tuyen_duong.id;
        formFile.danh_muc = danhMucTaiLieuOptions.tuyen_duong;
        uploadedFiles.value = props.tuyen_duong.tai_lieu;
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
    <div class="modal fade" id="modal" >
        <div
            class="modal-dialog modal-dialog-centered"
            :class="[isEdit ? 'modal-lg' : 'modal-lg']"
        >
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="row">
                        <!-- Main Content -->
                         <div :class="['px-4 pt-4', isEdit ? 'col-md-12' : 'col-md-12']">
                            <div class="form-group">
                                <label for="ten_tuyen_duong">Tên đường</label>
                                <Input v-model="form.ten" :errors="form.errors.ten" />
                            </div>
                            <div class="form-group">
                                <label for="loai_tuyen_duong_id">Loại tuyến đường</label>
                                <Select v-model="form.loai_tuyen_duong_id"
                                        :errors="form.errors.loai_tuyen_duong_id"
                                        :options="loai_tuyen_duong"
                                        id="loai_tuyen_duong_id"
                                        option-default="Chọn loại"/>
                            </div>
                            <div class="form-group">
                                <label for="phan_cap_id">Mã phân cấp</label>
                                <Select v-model="form.phan_cap_id"
                                        :errors="form.errors.phan_cap_id"
                                        :options="phan_cap"
                                        id="phan_cap_id"
                                        option-default="Chọn mã phân cấp"/>
                            </div>

                            <div class="form-group">
                                <label for="diem_dau_huyen_id">Điểm đầu</label>
                                <div class="grid grid-cols-2 gap-x-3">
                                    <Select v-model="form.diem_dau_huyen_id"
                                            :errors="form.errors.diem_dau_huyen_id"
                                            :options="huyen"
                                            id="diem_dau_huyen_id"
                                            option-default="Chọn huyện"/>
                                    <Select v-model="form.diem_dau_xa_id"
                                            :errors="form.errors.diem_dau_xa_id"
                                            :options="dau_xa"
                                            id="diem_dau_xa_id"
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
                                            id="diem_cuoi_huyen_id"
                                            option-default="Chọn huyện"/>
                                    <Select v-model="form.diem_cuoi_xa_id"
                                            :errors="form.errors.diem_cuoi_xa_id"
                                            :options="cuoi_xa"
                                            id="diem_cuoi_xa_id"
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
                                <label for="dien_tich">Lộ giới</label>
                                <Input v-model="form.lo_gioi" :errors="form.errors.lo_gioi" />
                            </div>


                            <div class="form-group">
                                <label for="don_vi_quan_ly">Đơn vị quản lý</label>
                                <Select v-model="form.don_vi_id"
                                        :errors="form.errors.don_vi_id"
                                        :options="props.don_vi"
                                        id="don_vi_id"
                                        option-default="Chọn đơn vị"/>
                            </div>

                           
                        </div>

                    </div>
                </div>
                <div  class="modal-footer">
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
