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
    ngay_het_han: '',
    noi_dung: '',
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
            :class="[isEdit ? 'modal-lg' : 'modal-lg']"
        >
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="row">
                        <!-- Main Content -->
                        <div :class="['px-4 pt-4', isEdit ? 'col-md-12' : 'col-md-12']">
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
                            <div class="form-group">
                                <label for="ngay_cap_phep">Ngày hết hạn</label>
                                <Input v-model="form.ngay_het_han"
                                       type="date"
                                       :errors="form.errors.ngay_het_han"/>
                            </div>
                            <div class="form-group">
                                <label for="so_cap_phep">Nội dung</label>
                                <Input v-model="form.noi_dung"
                                       :errors="form.errors.noi_dung"/>
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
