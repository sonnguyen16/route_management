<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router, useForm} from "@inertiajs/vue3";
import Modal from "@/Pages/SuaChua/Modal.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref, watch} from "vue";
import {_TIME_DEBOUNCE, loaiSuaChuaOptions} from "@/Constants/constants.js";
import Upload from "@/Components/UploadFile.vue";
import moment from "moment";

import { debounce } from 'lodash';
import Input from "@/Components/Input.vue";

const props = defineProps({
    sua_chua: Object,
    tuyen_duong: Object,
    don_vi: Object,
    nguoi_duyet: Object,    
    loai_sua_chua: Object,
})
const sua_chua_selected = ref(null);
const isEdit = ref(false);
const key = ref(0);
const keyModal = ref(0);
const ngay_khoi_cong = ref('');
const ngay_hoan_thanh = ref('');
const showModal = ref(false);
const sua_chua_cha_selected = ref(null);


const changePage = (page) => {
    router.visit(route('sua-chua.index', {
            page: page,
            ten_duong: search.value,
            ngay_hoan_thanh: ngay_hoan_thanh.value,
            ngay_khoi_cong: ngay_khoi_cong.value
        }), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const modal = useModal('modal');

onMounted(() => {
    // eventForEditBtn();
    // openModalDiem();
   // openModalEditDiem();
})

const onRefresh = () => {
    key.value++
    keyModal.value++
    nextTick(() => {
      //  eventForEditBtn()
    })
    if (sua_chua_selected.value) {
        sua_chua_selected.value = props.sua_chua.data.
        find(item => item.id === sua_chua_selected.value.id);
    }
}

const openModal = (item) => {
    keyModal.value++
    sua_chua_selected.value = null;
    sua_chua_cha_selected.value = item;
    isEdit.value = false;
    modal.showModal();
}
const editModal = (item) => {
    console.log(item);
    keyModal.value++
    sua_chua_selected.value = item;
    if (item.sua_chua_id) {
        sua_chua_cha_selected.value = props.sua_chua.data.find(item => item.id === item.sua_chua_id);
    } else {
        sua_chua_cha_selected.value = null;
    }
    isEdit.value = true;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

watch(ngay_khoi_cong, (value) => {
    ngay_hoan_thanh.value = '';
    router.visit(route('sua-chua.index', {
        ngay_khoi_cong: value,
        ngay_hoan_thanh: ngay_hoan_thanh.value,
        ten_duong: search.value
    }), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
})

watch(ngay_hoan_thanh, (value) => {
    router.visit(route('sua-chua.index', {
        ngay_khoi_cong: ngay_khoi_cong.value,
        ngay_hoan_thanh: value,
        ten_duong: search.value
    }), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
})

const searchDebounce = debounce((value) => {
    router.visit(route('sua-chua.index', {ten_duong: value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}, _TIME_DEBOUNCE)

function deleteDiem(id) {
    router.visit(route('sua-chua.deleteDiem', {id: id}), {
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
   formFile.type = 'sua_chua';
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
     <div class="py-3 px-4">
         <div class="mb-3 flex justify-between">
             <div class="flex gap-5 items-center">
                <button @click.prevent="openModal(null)" class="btn btn-success">Thêm sửa chữa</button>
                <a :href="route('cau-hinh.index',{loai: 'loai-sua-chua'})" class="btn btn-success">Loại sửa chữa</a>
                <!--
                <div class="flex items-center w-[300px] gap-2">
                     <label class="mb-0" for="ngay_khoi_cong">Ngày khởi công</label>
                     <input v-model="ngay_khoi_cong" type="date" class="form-control flex-1" placeholder="Ngày khởi công"/>
                 </div>
                 <div class="flex items-center w-[300px] gap-2">
                     <label class="mb-0" for="ngay_hoan_thanh">Ngày hoàn thành</label>
                     <input v-model="ngay_hoan_thanh" type="date" class="form-control flex-1" placeholder="Ngày hoàn thành"/>
                 </div>--> 
             </div>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm sửa chữa">
         </div>
         <div class="table-responsive">
            <table class="table table-striped text-2xl table-line">
                <thead>
                    <tr>
                    <th class="text-center"></th>
                    <th class="text-left">Tuyến đường</th>
                    <th class="text-left">Loại sửa chữa</th>
                    <th class="text-left">Từ km</th>
                    <th class="text-left">Đến km</th>
                    <th class="text-center">Ngày duyệt</th>
                    <th class="text-center">Ngày khởi công</th>
                    <th class="text-center">Ngày hoàn thành</th>
                   
                    <th class="text-left">Đơn vị thi công</th>
                    <th class="text-left">Ghi chú</th>
                    <th class="text-left">File đính kèm</th>
                    <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item, i) in sua_chua.data" :key="i">
                    <tr v-if="!item.sua_chua_id">
                    <td class="text-center" scope="row"><a @click.prevent="openModal(item)" class="edit cursor-pointer" title="Thêm đoạn đường"><i class="fas fa-plus mr-2"></i></a></td>  
                    <td>{{ i+1 }}. {{ item.tuyen_duong ? item.tuyen_duong.ten : ''}}</td>                    
                    <td>{{ item.loai_sua_chua ? item.loai_sua_chua.ten : ''}}</td>
                    <td class="text-center">{{ item.tu_km}}</td>
                    <td class="text-center">{{ item.den_km}}</td>
                    <td class="text-center" > <span v-if="item.ngay_duyet">{{ moment(item.ngay_duyet).format("DD/MM/YYYY") }}</span></td>
                    <td class="text-center" ><span v-if="item.ngay_khoi_cong">{{ moment(item.ngay_khoi_cong).format("DD/MM/YYYY") }}</span></td>
                    <td class="text-center" ><span v-if="item.ngay_hoan_thanh">{{ moment(item.ngay_hoan_thanh).format("DD/MM/YYYY") }}</span></td>
                   
                    <td>{{ item.don_vi ? item.don_vi.ten : ''}}</td>
                    <td class="text-center">{{ item.noi_dung}}</td>
                    <td style="vertical-align: unset !important;">
                            <label style="font-weight: normal;color: #007bff;" @click.prevent="chooseFile(item.id)"
                            class="cursor-pointer border-0 w-full text-start rounded-md mb-0">
                            <i class="fa fa-paperclip mr-2"></i>
                        </label>
                            <Upload
                               :listFile ="item.tai_lieu"
                                @refresh="onRefresh"
                            />
                    </td>
                    <td class="text-center"><a @click.prevent="editModal(item)" class="edit cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a></td>
                </tr>
                <tr v-for="(item, i) in item.doan_duong" :key="i">
                    <td class="text-center" scope="row"></td>  
                    <td></td>                    
                    <td>{{ item.loai_sua_chua ? item.loai_sua_chua.ten : ''}}</td>
                    <td class="text-center" > <span v-if="item.ngay_duyet">{{ moment(item.ngay_duyet).format("DD/MM/YYYY") }}</span></td>
                    <td class="text-center" ><span v-if="item.ngay_khoi_cong">{{ moment(item.ngay_khoi_cong).format("DD/MM/YYYY") }}</span></td>
                    <td class="text-center" ><span v-if="item.ngay_hoan_thanh">{{ moment(item.ngay_hoan_thanh).format("DD/MM/YYYY") }}</span></td>
                    <td class="text-center">{{ item.tu_km}}</td>
                    <td class="text-center">{{ item.den_km}}</td>
                    <td>{{ item.don_vi ? item.don_vi.ten : ''}}</td>
                    <td class="text-center">{{ item.noi_dung}}</td>
                    <td style="vertical-align: unset !important;">
                            <label style="font-weight: normal;color: #007bff;" @click.prevent="chooseFile(item.id)"
                            class="cursor-pointer border-0 w-full text-start rounded-md mb-0">
                            <i class="fa fa-paperclip mr-2"></i>
                        </label>
                            <Upload
                               :listFile ="item.tai_lieu"
                                @refresh="onRefresh"
                            />
                        </td>
                        
                    <td class="text-center"><a @click.prevent="editModal(item)" class="edit cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a></td>
                </tr>
                </template>
                </tbody>
                </table>
         </div>
         <Pagination
             :all-data="sua_chua"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :is-edit="isEdit"
         :key-modal="keyModal"
         :tuyen_duong="tuyen_duong"
         :sua_chua="sua_chua_selected"
         :loai_sua_chua ="loai_sua_chua"
         :don_vi="don_vi"
         :sua_chua_cha="sua_chua_cha_selected"
         :nguoi_duyet="nguoi_duyet"
     />
     <!---
     <ModelHangMuc 
      @close-modal="showmodal.hideModal"
     :sua_chua="sua_chua_selected" 
     :sua_chua_diem="sua_chua_diem_selected"
     :loai_sua_chua="loai_sua_chua"
     :key-modal="keyModal"
     @refresh="onRefresh">
    </ModelHangMuc>-->
</MainLayout>
</template>
