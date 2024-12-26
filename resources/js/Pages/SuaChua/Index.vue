<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router, useForm} from "@inertiajs/vue3";
import Modal from "@/Pages/SuaChua/Modal.vue";
import ModelHangMuc from "@/Pages/SuaChua/ModelHangMuc.vue";
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
const sua_chua_diem_selected = ref(null);



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

const columns = [
    {field: 'id', label: 'ID'},
    {field: 'tuyen_duong.ten', label: 'Tên đường'},
    {field: 'don_vi.ten', label: 'Đơn vị thi công'},
    {field: 'ngay_duyet', label: 'Ngày duyệt'},
    {field: 'ngay_khoi_cong', label: 'Ngày khởi công'},
    {field: 'ngay_hoan_thanh', label: 'Ngày hoàn thành'},
    {field: 'action', label: 'Hành động'},
]

const modal = useModal('modal');
const showmodal = useModal('ModelHangMuc');

onMounted(() => {
    eventForEditBtn();
    openModalDiem();
   // openModalEditDiem();
})

const onRefresh = () => {
    key.value++
    keyModal.value++
    nextTick(() => {
        eventForEditBtn()
    })
    if (sua_chua_selected.value) {
        sua_chua_selected.value = props.sua_chua.data.
        find(item => item.id === sua_chua_selected.value.id);
    }
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        sua_chua_selected.value = props.sua_chua.data.find(item => item.id === id);
        console.log(sua_chua_selected.value);
        isEdit.value = true;
        keyModal.value++
        modal.showModal();
    });
}

const openModal = () => {
    sua_chua_selected.value = null;
    isEdit.value = false;
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

const openModalDiem = () => {
    $('.newDiem').click(function () {
        const id = $(this).data('id');
        sua_chua_selected.value = props.sua_chua.data.find(item => item.id === id);
        sua_chua_diem_selected.value = null;
        showmodal.showModal();
        
    });
}
function updateMsg(value) {
 sua_chua_selected.value = props.sua_chua.data.find(item => item.id === value.sua_chua_id);
 sua_chua_diem_selected.value = value;
 keyModal.value++
 showModal.value = true;
 showmodal.showModal();
}
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
                <button @click.prevent="openModal" class="btn btn-success">Thêm sửa chữa</button>
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
            <table class="table table-striped text-2xl">
                <thead>
                    <tr>
                    <th class="text-center">STT</th>
                    <th class="text-center">Nội dung</th>
                    <th class="text-center">File đính kèm</th>
                    <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,i) in sua_chua.data" :key="i">
                    <td class="text-center" scope="row">{{ i+1 }}</td>  
                   
                    <td class="text-left" style="vertical-align: unset !important;">
                    <div>Tuyến đường: <b> {{ item.tuyen_duong ? item.tuyen_duong.ten : ''}} </b></div>
                    <div>Đơn vị thi công: <b>{{ item.don_vi ? item.don_vi.ten : ''}}</b></div>
                       <table v-if="item.sua_chua_diem.length > 0" style="width: 100%;color:#4f85f9">
                            <tr>
                                <th class="text-left">Hạng mục</th>
                                <th class="text-center">Ngày duyệt</th>
                                <th class="text-center">Ngày khởi công</th>
                                <th class="text-center">Ngày hoàn thành</th>
                                <th class="text-center">Từ km</th>
                                <th class="text-center">Đến km</th>
                                <th></th>
                                <th></th>
                                </tr>
                            <tr v-for="(a,i) in item.sua_chua_diem" :key="i">
                                <td ><!--<a :data-id=a.id class="editDiem cursor-pointer">-->
                                   <a href="#" @click.prevent="updateMsg(a)"> {{ i+1 }}.{{ a.loai_sua_chua.ten }}</a><!--</a>-->
                                </td>
                                <td class="text-center"><span v-if="a.ngay_duyet">{{ moment(a.ngay_duyet).format("DD/MM/YYYY") }}</span></td>
                                <td class="text-center"><span v-if="a.ngay_khoi_cong">{{ moment(a.ngay_khoi_cong).format("DD/MM/YYYY") }}</span></td>
                                <td class="text-center"><span v-if="a.ngay_hoan_thanh">{{ moment(a.ngay_hoan_thanh).format("DD/MM/YYYY") }}</span></td>
                                <td class="text-center">Km {{ a.tu_km }}+</td>
                                <td class="text-center">Km {{ a.den_km }}+</td>
                                <td><a href="#" @click.prevent="deleteDiem(a.id)" class="cursor-pointer"><i class="fa fa-times-circle mr-1"></i></a></td>
                                <td><a href="#" @click.prevent="updateMsg(a)" class=" cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a></td>
                            </tr>
                        </table>
                        <div style="padding-left:10px"><a :data-id=item.id class="newDiem cursor-pointer" title="Sửa"><i class="fas fa-plus mr-2"></i>Thêm mới vị trí</a></div>
                        </td>
                        <td style="vertical-align: unset !important;">
                            <label style="font-weight: normal;color: #007bff;" @click.prevent="chooseFile(item.id)"
                            class="cursor-pointer border-0 w-full text-start rounded-md mb-0">
                            <i class="fa fa-paperclip mr-2"></i>
                            Tải lên tệp
                        </label>
                            <Upload
                               :listFile ="item.tai_lieu"
                                @refresh="onRefresh"
                            />
                        </td>
                    <td class="text-center"><a :data-id=item.id class="edit cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a></td>
                </tr>
                </tbody>
                </table>
         <!--    <table :key="key"
                    class="table table-striped text-2xl"
                    v-data="{ data: sua_chua.data, columns: columns }">
             </table>-->
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
         :nguoi_duyet="nguoi_duyet"
     />
     <ModelHangMuc 
      @close-modal="showmodal.hideModal"
     :sua_chua="sua_chua_selected" 
     :sua_chua_diem="sua_chua_diem_selected"
     :loai_sua_chua="loai_sua_chua"
     :key-modal="keyModal"
     @refresh="onRefresh">
    </ModelHangMuc>
</MainLayout>
</template>
