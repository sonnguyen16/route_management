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
    gioi_han_toc_do: Object,
    gioi_han_toc_do_cha: Object,
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
    tu_ngay: '',
    den_ngay: '',
    noi_dung: '',
    tu_km: '',
    den_km:'',
    gioi_han_toc_do_id:'',
    tai_lieu: [],
})

const submit = () => {
    form.post(route('gioi-han-toc-do.store'), {
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
    if(props.gioi_han_toc_do) {
        Object.assign(form, props.gioi_han_toc_do);
    }else{
        form.reset();
        form.id = '';
        form.tuyen_duong_id = '';
        form.tu_ngay = '';
        form.den_ngay = '';
        form.noi_dung = '';
        form.tu_km = '';
        form.den_km ='';
        form.gioi_han_toc_do_id = '';
    }
    console.log(props.gioi_han_toc_do_cha);
    if(props.gioi_han_toc_do_cha) {
        form.gioi_han_toc_do_id = props.gioi_han_toc_do_cha.id; 
        form.tuyen_duong_id = props.gioi_han_toc_do_cha.tuyen_duong_id; 
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
                                <label for="toc_do">Nội dung</label>
                                <Input
                                    v-model="form.noi_dung"
                                    :errors="form.errors.noi_dung"
                                />
                            </div>
                            <div class="form-group">
                              <label for="tu_km">Từ km</label>
                              <Input
                                v-model="form.tu_km"
                                type="text"
                                :errors="form.errors.tu_km"
                                placeholder="Từ km"
                                id="tu_km"
                              />
                          </div>
                          <div class="form-group">
                              <label for="den_km">Đến km</label>
                              <Input
                                v-model="form.den_km"
                                type="text"
                                :errors="form.errors.den_km"
                                placeholder="Đến km"
                                id="den_km"
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
