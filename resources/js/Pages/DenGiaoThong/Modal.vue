<script setup>
import {useForm} from "@inertiajs/vue3";
import {watch} from "vue";
import Input from "@/Components/Input.vue";
import {formatDate} from "@/assets/js/script.js";
import File from "@/Components/File.vue";
import Upload from "@/Components/Upload.vue";

const props = defineProps({
    den_giao_thong: Object,
    keyModal: Number,
    loai: String,
    isEdit: {
        type: Boolean,
        default: false
    },
})

const emits = defineEmits(['closeModal', 'refresh']);

const form = useForm({
    id: '',
    nut_giao: '',
    so_pha_den: '',
    thoi_gian_pha_den: '',
    thoi_gian_hoat_dong: '',
    vi_tri: '',
})

const submit = () => {
    form.post(route('den-giao-thong.store'), {
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
    if(props.den_giao_thong) {
        Object.assign(form, props.den_giao_thong);
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
                                <label for="name">Nút giao</label>
                                <Input
                                    v-model="form.nut_giao"
                                    :errors="form.errors.nut_giao"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Số pha đèn</label>
                                <Input
                                    v-model="form.so_pha_den"
                                    :errors="form.errors.so_pha_den"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Thời gian pha đèn</label>
                                <Input
                                    v-model="form.thoi_gian_pha_den"
                                    :errors="form.errors.thoi_gian_pha_den"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Thời gian hoạt động</label>
                                <Input
                                    v-model="form.thoi_gian_hoat_dong"
                                    :errors="form.errors.thoi_gian_hoat_dong"
                                />
                            </div>
                            <div class="form-group">
                                <label for="name">Vị trí</label>
                                <Input
                                    v-model="form.vi_tri"
                                    :errors="form.errors.vi_tri"
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