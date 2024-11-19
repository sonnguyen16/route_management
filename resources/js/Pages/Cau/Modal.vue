<script setup>
import {useForm} from "@inertiajs/vue3";
import {watch} from "vue";
import Input from "@/Components/Input.vue";
import {formatDate} from "@/assets/js/script.js";
import File from "@/Components/File.vue";
import Upload from "@/Components/Upload.vue";
import Select from "@/Components/Select.vue";

const props = defineProps({
    cau: Object,
    keyModal: Number,
    loai_ket_cau_nhip: Object,
    loai_cau: Object,
    tuyen_duong: Object,
    isEdit: {
        type: Boolean,
        default: false
    },
})

const emits = defineEmits(['closeModal', 'refresh']);

const form = useForm({
    id: '',
    ten: '',
    ly_trinh: '',
    chieu_rong: '',
    chieu_dai: '',
    loai_ket_cau_nhip_id: '',
    nam_khai_thac: '',
    tai_trong: '',
    kiem_dinh: '',
    tuyen_duong_id: '',
    loai_cau_id: '',
    lat: '',
    lng: ''
})

const submit = () => {
    form.post(route('cau.store'), {
        onSuccess: () => {
            closeModal()
            emits('refresh')
        },
        onError: (err) => {
            console.log(err)
        }
    })
}

watch(() => props.keyModal, () => {
    if(props.cau) {
        Object.assign(form, props.cau);
    }else{
        form.reset();
    }
})
const closeModal = () => {
    emits('closeModal');
    form.reset();
    form.clearErrors();
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
                                <label for="name">Tên cầu</label>
                                <Input
                                    v-model="form.ten"
                                    :errors="form.errors.ten"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Lý trình</label>
                                <Input
                                    v-model="form.ly_trinh"
                                    :errors="form.errors.ly_trinh"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Chiều rộng</label>
                                <Input
                                    v-model="form.chieu_rong"
                                    :errors="form.errors.chieu_rong"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Chiều dài</label>
                                <Input
                                    v-model="form.chieu_dai"
                                    :errors="form.errors.chieu_dai"
                                />
                            </div>
                            <div class="form-group">
                                <label for="loai_ket_cau_nhip_id">Loại kết cấu nhịp</label>
                                <Select
                                    v-model="form.loai_ket_cau_nhip_id"
                                    :options="loai_ket_cau_nhip"
                                    :errors="form.errors.loai_ket_cau_nhip_id"
                                    id="loai_ket_cau_nhip_id"
                                    option-default="Chọn loại kết cấu nhịp"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Năm khai thác</label>
                                <Input
                                    v-model="form.nam_khai_thac"
                                    :errors="form.errors.nam_khai_thac"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Tải trọng</label>
                                <Input
                                    v-model="form.tai_trong"
                                    :errors="form.errors.tai_trong"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Kiểm định</label>
                                <Input
                                    v-model="form.kiem_dinh"
                                    :errors="form.errors.kiem_dinh"
                                />
                            </div>
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
                                <label for="loai_cau_id">Loại cầu</label>
                                <Select
                                    v-model="form.loai_cau_id"
                                    :options="loai_cau"
                                    :errors="form.errors.loai_cau_id"
                                    id="loai_cau_id"
                                    option-default="Chọn loại cầu"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Lat</label>
                                <Input
                                    v-model="form.lat"
                                    :errors="form.errors.lat"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Lng</label>
                                <Input
                                    v-model="form.lng"
                                    :errors="form.errors.lng"
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
                                            {{ formatDate(den_giao_thong.created_at?.toString()) }}
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
<!--test-->