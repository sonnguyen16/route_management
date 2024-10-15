<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import Modal from "@/Pages/DuongCam/Modal.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref, watch} from "vue";
import {_TIME_DEBOUNCE} from "@/Constants/constants.js";
import { debounce } from 'lodash';

const props = defineProps({
    duong_cam: Object,
    tuyen_duong: Object,
    don_vi: Object,
})

const duong_cam_selected = ref(null);

const key = ref(0);
const changePage = (page) => {
    router.visit(route('duong-cam.index', {page: page, ten_duong: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const columns = [
    {field: 'id', label: 'ID'},
    {field: 'tuyen_duong.ten', label: 'Tên đường'},
    {field: 'tu_ngay', label: 'Từ ngày'},
    {field: 'den_ngay', label: 'Đến ngày'},
    {field: 'don_vi_quyet_dinh.ten', label: 'Đơn vị quyết định'},
    {field: 'don_vi_thuc_hien.ten', label: 'Đơn vị thực hiện'},
    {field: 'tai_lieu', label: 'Tài liệu'},
    {field: 'action', label: 'Hành động'},
]

const modal = useModal('modal');

onMounted(() => {
    eventForEditBtn()
})

const onRefresh = () => {
    key.value++
    nextTick(() => {
        eventForEditBtn()
    })
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        duong_cam_selected.value = props.duong_cam.data.find(item => item.id === id);
        modal.showModal();
    });
}

const openModal = () => {
    duong_cam_selected.value = null;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
    router.visit(route('duong-cam.index', {ten_duong: value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}, _TIME_DEBOUNCE)

</script>

<template>
 <MainLayout>
     <div class="py-3 px-4">
         <div class="mb-3 flex justify-between">
             <button @click.prevent="openModal" class="btn btn-success">Thêm đường cấm</button>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm đường cấm">
         </div>
         <table :key="key"
                class="table table-striped text-2xl"
                v-data="{ data: duong_cam.data, columns: columns }">
         </table>
         <Pagination
             :all-data="duong_cam"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :tuyen_duong="tuyen_duong"
         :duong_cam="duong_cam_selected"
         :don_vi="don_vi"
     />
</MainLayout>
</template>
