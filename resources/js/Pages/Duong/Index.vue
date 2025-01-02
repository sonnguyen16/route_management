<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router, useForm} from "@inertiajs/vue3";
import Modal from "@/Pages/Duong/Modal.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref, watch} from "vue";
import {debounce} from "lodash";
import {
    _TIME_DEBOUNCE,
} from "@/Constants/constants.js";
import Upload from "@/Components/UploadFile.vue";

const props = defineProps({
    tuyen_duong: Object,
    huyen: Object,
    don_vi: Object,
    loai_tuyen_duong: Object,
    phan_cap: Object,
    loai_tuan_tra: Object,
})

const tuyen_duong_selected = ref(null);
const tuyen_duong_diem_selected = ref(null);
const tuyen_duong_cha_selected = ref(null);
const keyModal = ref(0);
const key = ref(0);
const changePage = (page) => {
    router.visit(route('tuyen-duong.index', {page: page, ten_duong: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const modal = useModal('modal');
onMounted(() => {
    eventForEditBtn()
})

const onRefresh = () => {
    key.value++
    keyModal.value++
    nextTick(() => {
        eventForEditBtn()
    })
    if(tuyen_duong_selected.value){
        tuyen_duong_selected.value = props.tuyen_duong.data.
        find(item => item.id === tuyen_duong_selected.value.id);
    }
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        tuyen_duong_selected.value = props.tuyen_duong.data.find(item => item.id === id);
        console.log(tuyen_duong_selected.value);
        if (tuyen_duong_selected.tuyen_duong_id) {
            tuyen_duong_cha_selected.value = props.tuyen_duong.data.find(item => item.id === tuyen_duong_selected.value.tuyen_duong_id);
        } else {
            tuyen_duong_cha_selected.value = null;
        }
        
        isEdit.value = true;
        keyModal.value++;
        
        modal.showModal();
    });
}

const openModal = (value) => {
    keyModal.value++
    tuyen_duong_selected.value = null;
    tuyen_duong_cha_selected.value = value;
    isEdit.value = false;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
    router.visit(route('tuyen-duong.index', {ten_duong: value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}, _TIME_DEBOUNCE)

const isEdit = ref(false);

function deleteDiemCam(id) {
    router.visit(route('tuyen-duong.deleteDiem', {id: id}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}
// upload hình ảnh
let formFile = useForm({
    type: '',
    danh_muc: '',
    file: []
})
var input = document.createElement('input');
input.type = 'file';
input.multiple = true;
input.onchange = e => { 
   formFile.file = e.target.files;
   formFile.type = 'duong';
   formFile.post(route('tai-lieu.store'), {
        onSuccess: () => {
            onRefresh();
        },
        onError: (err) => {
            console.log(err)
        }
    })
}
const chooseFile = (id) => {
  formFile.danh_muc = id;
  input.click();
}
// ket thuc
</script>

<template>
 <MainLayout>
     <div class="py-3 px-3"> <!--class="py-3 px-4"-->
         <div class="mb-3 flex justify-between">
            <span>
                <button @click.prevent="openModal" class="btn btn-success">Thêm tuyến đường</button>
               <span style="padding-left:10px"><a :href="route('cau-hinh.index',{loai: 'loai-tuyen-duong'})" class="btn btn-success">Phân loại</a></span>
               <span style="padding-left:10px"><a :href="route('cau-hinh.index',{loai: 'phan-cap'})" class="btn btn-success">Phân cấp</a></span>
            </span>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm tuyến đường">
         </div>
         <div class="table-responsive">
            <table class="table table-striped text-2xl table-line">
                <thead>
                    <tr>
                    <th class="text-center"></th>
                    <th class="text-left" style="min-width: 100px;">Tên tuyến đường</th>
                    <th class="text-left" style="min-width: 120px;">Loại tuyến đường</th>
                    <th class="text-left"  style="min-width: 90px;">Mã phân cấp</th>
                    <th class="text-left">Điểm đầu</th>
                    <th class="text-left">Điểm cuối</th>
                    <th class="text-center" style="min-width: 80px;">Chiều dài</th>
                    <th class="text-center" style="min-width: 80px;">Chiều rộng</th>
                    <th class="text-center" style="min-width: 80px;">Lộ giới</th>
                    <th class="text-center">Đơn vị quản lý</th>
                    <th class="text-center">File đính kèm</th>
                    <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item, i) in tuyen_duong.data" :key="i">
                    <tr v-if="!item.tuyen_duong_id">
                    <td class="text-center" scope="row">
                       <a @click.prevent="openModal(item)" class="edit cursor-pointer" title="Thêm đoạn đường"><i class="fas fa-plus mr-2"></i></a>
                    </td>
                    <td><a :data-id=item.id class="edit cursor-pointer" title="Sửa"> {{ i+1 }}
                        .{{ item.ten }}</a>
                    </td>
                    <td>{{ item.loai_tuyen_duong ? item.loai_tuyen_duong.ten : ''}}</td>
                    <td>{{ item.phan_cap ? item.phan_cap.ten : ''}}</td>
                    <td style="line-height: 1.5;">
                        {{ item.diem_dau_xa ? item.diem_dau_xa.name : ''}}, {{ item.diem_dau_huyen ? item.diem_dau_huyen.name : ''}}
                    </td>
                    <td>
                        {{ item.diem_cuoi_xa ? item.diem_cuoi_xa.name : ''}}, {{ item.diem_cuoi_huyen ? item.diem_cuoi_huyen.name : ''}}
                    </td>
                    <td class="text-center">{{ item.chieu_dai }}</td>
                    <td class="text-center">{{ item.chieu_rong }}</td>
                    <td class="text-center">{{ item.lo_gioi }}</td>
                    <td><a href="#">{{ item.don_vi ? item.don_vi.ten : ''}}</a></td>
                    <td style="vertical-align: unset !important;">
                            <Upload
                                :listFile ="item.tai_lieu"
                                @refresh="onRefresh"
                            />
                            <label title="Thêm tài liệu" style="font-weight: normal;color: #007bff;" @click.prevent="chooseFile(item.id)"
                            class="cursor-pointer border-0 w-full text-start rounded-md mb-0">
                            <i class="fa fa-paperclip mr-2"></i>
                        </label>
                        </td>
                        <td class="text-right">
                       <a :data-id=item.id class="edit cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a>
                        </td>
                    </tr>

                    <tr v-for="(item, k) in item.doan_duong" :key="k">
                    <td class="text-center" scope="row"></td>
                    <td><a :data-id=item.id class="edit cursor-pointer" title="Sửa">{{ item.ten }}</a>
                    </td>
                    <td>{{ item.loai_tuyen_duong ? item.loai_tuyen_duong.ten : ''}}</td>
                    <td>{{ item.phan_cap ? item.phan_cap.ten : ''}}</td>
                    <td style="line-height: 1.5;">
                        {{ item.diem_dau_xa ? item.diem_dau_xa.name : ''}}, {{ item.diem_dau_huyen ? item.diem_dau_huyen.name : ''}}
                    </td>
                    <td>
                        {{ item.diem_cuoi_xa ? item.diem_cuoi_xa.name : ''}}, {{ item.diem_cuoi_huyen ? item.diem_cuoi_huyen.name : ''}}
                    </td>
                    <td class="text-center">{{ item.chieu_dai }}</td>
                    <td class="text-center">{{ item.chieu_rong }}</td>
                    <td class="text-center">{{ item.lo_gioi }}</td>
                    <td><a href="#">{{ item.don_vi ? item.don_vi.ten : ''}}</a></td>
                    <td style="vertical-align: unset !important;">
                            <Upload
                                :listFile ="item.tai_lieu"
                                @refresh="onRefresh"
                            />
                            <label title="Thêm tài liệu" style="font-weight: normal;color: #007bff;" @click.prevent="chooseFile(item.id)"
                            class="cursor-pointer border-0 w-full text-start rounded-md mb-0">
                            <i class="fa fa-paperclip mr-2"></i>
                        </label>
                        </td>
                        <td class="text-right">
                            <a :data-id=item.id class="edit cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a>
                        </td>
                    </tr>

                    
                    </template>
                    
                </tbody>
                </table>
         </div>
         <Pagination
             :all-data="tuyen_duong"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :huyen="huyen"
         :key-modal="keyModal"
         :loai_tuyen_duong="loai_tuyen_duong"
         :phan_cap="phan_cap"
         :tuyen_duong="tuyen_duong_selected"
         :tuyen_duong_cha="tuyen_duong_cha_selected"
         :don_vi="don_vi"
         :is-edit="isEdit"
     />
</MainLayout>
</template>
