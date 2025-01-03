<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router, useForm} from "@inertiajs/vue3";
import Modal from "@/Pages/TocDo/Modal.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref, watch} from "vue";
import {_TIME_DEBOUNCE, loaiOptions, maPhanCapOptions} from "@/Constants/constants.js";
import { debounce } from 'lodash';
import Upload from "@/Components/UploadFile.vue";
import moment from "moment";
const props = defineProps({
    gioi_han_toc_do: Object,
    tuyen_duong: Object,
    don_vi: Object,
    nguoi_duyet: Object,
})

const gioi_han_toc_do_selected = ref(null);
const gioi_han_toc_do_cha_selected = ref(null);
const isEdit = ref(false);
const key = ref(0);
const keyModal = ref(0);
const changePage = (page) => {
    router.visit(route('gioi-han-toc-do.index', {page: page, ten_duong: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}


const modal = useModal('modal');

onMounted(() => {
   // eventForEditBtn()
})

const onRefresh = () => {
    key.value++
    keyModal.value++
    nextTick(() => {
       // eventForEditBtn()
    })
    if(gioi_han_toc_do_selected.value) {
        gioi_han_toc_do_selected.value = props.gioi_han_toc_do.data.
        find(item => item.id === gioi_han_toc_do_selected.value.id);
    }
}

const openModal = (item) => {
    keyModal.value++
    gioi_han_toc_do_selected.value = null;
    gioi_han_toc_do_cha_selected.value = item;
    isEdit.value = false;
    modal.showModal();
}
const editModal = (item) => {
    gioi_han_toc_do_selected.value = item;
    console.log(item);
    if (item.gioi_han_toc_do_id) {
        gioi_han_toc_do_cha_selected.value = props.gioi_han_toc_do.data.find(item => item.id === item.gioi_han_toc_do_id);
    } else {
        gioi_han_toc_do_cha_selected.value = null;
    }
    keyModal.value++
    isEdit.value = true;
    modal.showModal();
}
const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
    router.visit(route('gioi-han-toc-do.index', {ten_duong: value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}, _TIME_DEBOUNCE)



function deleteDiemGioiHanTocDo(value) {
    router.visit(route('gioi-han-toc-do.deleteDiemGioiHanTocDo', {id: value}), {
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
   formFile.type = 'gioi_han_toc_do';
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
             <button @click.prevent="openModal" class="btn btn-success">Thêm giới hạn tốc độ</button>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm giới hạn tốc độ">
         </div>
        <div class="table-responsive">
            <table class="table table-striped text-2xl table-line">
                <thead>
                    <tr>
                    <th class="text-center"></th>
                    <th class="text-left">Tuyến đường</th>
                    <th class="text-left">Nội dung</th>
                    <th class="text-center">Từ km</th>
                    <th class="text-center">Đến km</th>
                    <th class="text-center">Từ ngày</th>
                    <th class="text-center">Đến ngày</th>
                    <th class="text-left">Đơn vị</th>
                    <th class="text-left">File đính kèm</th>
                    <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item,i) in gioi_han_toc_do.data" :key="i">
                    <tr v-if="!item.gioi_han_toc_do_id">
                    <td><a @click.prevent="openModal(item)" class=" cursor-pointer" title="Thêm đoạn đường"><i class="fas fa-plus mr-2"></i></a></td>
                    <td>{{ i+1 }}. {{ item.tuyen_duong ? item.tuyen_duong.ten : ''}}</td>
                    <td>{{ item.noi_dung}}</td>
                    <td class="text-center">{{ item.tu_km}}</td>
                    <td class="text-center">{{ item.den_km }}</td>
                    <td class="text-center"><span v-if="item.tu_ngay">{{ moment(item.tu_ngay).format("DD/MM/YYYY HH:mm") }}</span></td>
                    <td class="text-center"><span v-if="item.den_ngay">{{ moment(item.den_ngay).format("DD/MM/YYYY HH:mm") }}</span></td>
                    <td  class="text-left">{{ item.don_vi ? item.don_vi.ten : ''}}</td>
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
                    <td class="text-center"><a @click.prevent="editModal(item)" class="cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a></td>
                </tr>
                <tr v-for="(item,i) in item.doan_duong" :key="i">
                    <td></td>
                    <td></td>
                    <td>{{ item.noi_dung}}</td>
                    <td class="text-center">{{ item.tu_km}}</td>
                    <td class="text-center">{{ item.den_km }}</td>
                    <td class="text-center"><span v-if="item.tu_ngay">{{ moment(item.tu_ngay).format("DD/MM/YYYY HH:mm") }}</span></td>
                    <td class="text-left"><span v-if="item.den_ngay">{{ moment(item.den_ngay).format("DD/MM/YYYY HH:mm") }}</span></td>
                    <td>{{ item.don_vi ? item.don_vi.ten : ''}}</td>   
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
                    <td class="text-center"><a @click.prevent="editModal(item)" class="cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a></td>
                </tr>
                </template>
                </tbody>
                </table>
                
        </div>
         <Pagination
             :all-data="gioi_han_toc_do"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :is-edit="isEdit"
         :key-modal="keyModal"
         :tuyen_duong="tuyen_duong"
         :gioi_han_toc_do="gioi_han_toc_do_selected"
         :gioi_han_toc_do_cha="gioi_han_toc_do_cha_selected"
         :don_vi="don_vi"
         :nguoi_duyet="nguoi_duyet"
     />
</MainLayout>
</template>
