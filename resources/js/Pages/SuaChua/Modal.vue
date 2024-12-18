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
    loai_sua_chua: Object,
    keyModal: Number,
    isEdit: {
        type: Boolean,
        default: false
    },
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
    console.log('abc1');
    if(props.sua_chua) {
        console.log('abc');
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
            :class="[isEdit ? 'modal-lg' : 'modal-lg']"
        >
        <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="row">
                        <!-- Main Content -->
                        
                        <div :class="['px-4 pt-4', isEdit ? 'col-md-12' : 'col-md-12']">
                            <div class="form-group">
                                <label for="tuyen_duong_id">Tuyến đường  {{ keyModal }}</label>
                                <Select
                                    v-model="form.tuyen_duong_id"
                                    :options="tuyen_duong"
                                    :errors="form.errors.tuyen_duong_id"
                                    id="tuyen_duong_id"
                                    option-default="Chọn tuyến đường"
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

                           
                            <div class="form-group">
                                
                            </div>
                        </div>

                       

                    </div>
                </div>
                <div class="modal-footer">
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
