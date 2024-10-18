<script setup>
import Input from "@/Components/Input.vue";
import {router, useForm} from "@inertiajs/vue3";
import Select from "@/Components/Select.vue";
import {ref, watch} from "vue";
import {danhMucTaiLieuOptions, loaiCongVanOptions, trangThaiCongVan} from "@/Constants/constants.js";
import Upload from "@/Components/Upload.vue";
import File from "@/Components/File.vue";
import {formatDate} from "@/assets/js/script.js";

const props = defineProps({
    cong_van: Object,
    don_vi: Object,
    nguoi_xu_ly: Object,
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
    loai: '',
    don_vi_id: '',
    ngay_gui: '',
    ngay_nhan: '',
    nguoi_xu_ly_id: '',
    trang_thai: '',
    ghi_chu: '',
})

const submit = () => {
    form.post(route('cong-van.store'), {
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
    cong_van_id: '',
    danh_muc: null,
    file: []
})

watch(() => props.keyModal, () => {
    if(props.cong_van) {
        Object.assign(form, props.cong_van);
        formFile.cong_van_id = props.cong_van.id;
        formFile.danh_muc = danhMucTaiLieuOptions.cong_van;
        uploadedFiles.value = props.cong_van.tai_lieu;
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
                                <label for="ten">Tên công văn</label>
                                <Input :errors="form.errors.ten"
                                       v-model="form.ten" id="ten"
                                       type="text" />
                            </div>

                            <div class="form-group">
                                <label for="loai">Loại công văn</label>
                                <Select :errors="form.errors.loai"
                                        v-model="form.loai"
                                        option-default="Chọn loại"
                                        id="loai"
                                        :options="loaiCongVanOptions"  />
                            </div>

                            <div class="form-group">
                                <label for="don_vi_id">Đơn vị gửi</label>
                                <Select :errors="form.errors.don_vi_id"
                                        v-model="form.don_vi_id"
                                        option-default="Chọn đơn vị"
                                        id="don_vi_id"
                                        :options="props.don_vi"  />
                            </div>

                            <div class="form-group">
                                <label for="nguoi_xu_ly">Người xử lý</label>
                                <Select :errors="form.errors.nguoi_xu_ly_id"
                                        v-model="form.nguoi_xu_ly_id"
                                        option-default="Chọn người xử lý"
                                        id="nguoi_xu_ly_id"
                                        :options="props.nguoi_xu_ly"  />
                            </div>

                            <div class="form-group">
                                <label for="trang_thai">Trạng thái</label>
                                <Select :errors="form.errors.trang_thai"
                                        v-model="form.trang_thai"
                                        option-default="Chọn trạng thái"
                                        id="trang_thai"
                                        :options="trangThaiCongVan"  />
                            </div>

                            <div class="form-group">
                                <label for="ghi_chu">Ghi chú</label>
                                <Input :errors="form.errors.ghi_chu"
                                       v-model="form.ghi_chu" id="ghi_chu"
                                       type="text" />
                            </div>

                            <div class="form-group">
                                <label for="ngay_gui">Ngày gửi</label>
                                <Input :errors="form.errors.ngay_gui"
                                       v-model="form.ngay_gui" id="ngay_gui"
                                       type="date"  />
                            </div>

                            <div class="form-group">
                                <label for="ngay_nhan">Ngày nhận</label>
                                <Input :errors="form.errors.ngay_nhan"
                                       v-model="form.ngay_nhan" id="ngay_nhan"
                                       type="date"  />
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
                                            {{ formatDate(cong_van.created_at.toString()) }}
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
