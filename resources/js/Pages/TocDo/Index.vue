<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import Modal from "@/Pages/TocDo/Modal.vue";
import ModalDiemGioiHanTocDo from "@/Pages/TocDo/ModalDiemGioiHanTocDo.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref, watch} from "vue";
import {_TIME_DEBOUNCE, loaiOptions, maPhanCapOptions} from "@/Constants/constants.js";
import { debounce } from 'lodash';

const props = defineProps({
    gioi_han_toc_do: Object,
    tuyen_duong: Object,
    don_vi: Object,
    nguoi_duyet: Object,
})

const gioi_han_toc_do_selected = ref(null);
const diem_gioi_han_toc_do_selected = ref(null);
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

const columns = [
    {field: 'id', label: 'ID'},
    {field: 'tuyen_duong.ten', label: 'Tên đường'},
    {field: 'noi_dung', label: 'Nội dung'},
    {field: 'tu_ngay', label: 'Từ ngày'},
    {field: 'den_ngay', label: 'Đến ngày'},
    {field: 'action', label: 'Hành động'},
]

const modal = useModal('modal');

const modeldiemgioihantocdo = useModal('ModalDiemGioiHanTocDo');

onMounted(() => {
    eventForEditBtn()
})

const onRefresh = () => {
    key.value++
    keyModal.value++
    nextTick(() => {
        eventForEditBtn()
    })
    if(gioi_han_toc_do_selected.value) {
        gioi_han_toc_do_selected.value = props.gioi_han_toc_do.data.
        find(item => item.id === gioi_han_toc_do_selected.value.id);
    }
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        gioi_han_toc_do_selected.value = props.gioi_han_toc_do.data.find(item => item.id === id);
        isEdit.value = true;
        keyModal.value++
        modal.showModal();
    });
}

const openModal = () => {
    gioi_han_toc_do_selected.value = null;
    isEdit.value = false;
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



function themDiemGioiHanTocDovalue(id) {
  gioi_han_toc_do_selected.value = props.gioi_han_toc_do.data.find(item => item.id === id);
  diem_gioi_han_toc_do_selected.value = null;
  keyModal.value++
  modeldiemgioihantocdo.showModal();
}
function suaDiemGioiHanTocDo(value) {
    console.log(value);
    gioi_han_toc_do_selected.value = props.gioi_han_toc_do.data.find(item => item.id === value.gioi_han_toc_do_id);
    diem_gioi_han_toc_do_selected.value = value;
    keyModal.value++
    modeldiemgioihantocdo.showModal();
   
}
function deleteDiemGioiHanTocDo(value) {
    router.visit(route('gioi-han-toc-do.deleteDiemGioiHanTocDo', {id: value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

</script>

<template>
 <MainLayout>
     <div class="py-3 px-4">
         <div class="mb-3 flex justify-between">
             <button @click.prevent="openModal" class="btn btn-success">Thêm giới hạn tốc độ</button>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm giới hạn tốc độ">
         </div>
        <div class="table-responsive">
            <table class="table table-striped text-2xl">
                <thead>
                    <tr>
                    <th class="text-center">STT</th>
                    <th class="text-left">Tên đường</th>
                    <th class="text-left">Nội dung</th>
                    <th class="text-left">Từ ngày</th>
                    <th class="text-left">Đến ngày</th>
                    <th class="text-center">Vị trí giới hạn</th>
                    <th class="text-center">File đính kèm</th>
                    <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,i) in gioi_han_toc_do.data" :key="i">
                    <td class="text-center" scope="row">{{ i+1 }}</td>  
                    <td>{{ item.tuyen_duong ? item.tuyen_duong.ten : ''}}</td>  
                    <td>{{ item.noi_dung}}</td>  
                    
                    <td class="text-center">{{ item.tu_ngay }}</td>
                    <td class="text-center">{{ item.den_ngay }}</td>
                   
                    <td class="text-left" style="vertical-align: unset !important;">
                        <div style="padding-left:10px"><a @click.prevent="themDiemGioiHanTocDovalue(item.id)" class="newDiem cursor-pointer" title="Sửa"><i class="fas fa-plus mr-2"></i>Thêm mới vị trí</a></div>
                        <table>
                            <tr v-for="(a,i) in item.diem_gioi_han_toc_do" :key="i">
                                <td >
                                   <a href="#" @click.prevent="suaDiemGioiHanTocDo(a)"> {{ i+1 }}.{{ a.toc_do }}</a>
                                </td>
                                <td>Km {{ a.tu_km }}+</td>
                                <td>Km {{ a.den_km }}+</td>
                                <td><a href="#" @click.prevent="deleteDiemGioiHanTocDo(a.id)" class="cursor-pointer"><i class="fa fa-times-circle mr-1"></i></a></td>
                            </tr>
                        </table>
                        </td>
                        <td style="vertical-align: unset !important;">
                            <Upload
                                type="gioi_han_toc_do"
                                :danh_muc="item.id"
                                :listFile ="item.tai_lieu"
                                @refresh="onRefresh"
                            />
                        </td>
                    <td class="text-center"><a :data-id=item.id class="edit cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a></td>
                </tr>
                </tbody>
                </table>
                <!--
            <table :key="key"
                   class="table table-striped text-2xl"
                   v-data="{ data: gioi_han_toc_do.data, columns: columns }">
            </table> -->
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
         :don_vi="don_vi"
         :nguoi_duyet="nguoi_duyet"
     />
     <ModalDiemGioiHanTocDo 
      @close-modal="modeldiemgioihantocdo.hideModal"
     :diem_gioi_han_toc_do="diem_gioi_han_toc_do_selected" 
     :gioi_han_toc_do="gioi_han_toc_do_selected"
     :key-modal="keyModal"
     @refresh="onRefresh">
    </ModalDiemGioiHanTocDo>
</MainLayout>
</template>
