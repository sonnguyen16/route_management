<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import Modal from "@/Pages/GiamSat/Modal.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref, watch} from "vue";
import { debounce } from 'lodash';
import {_TIME_DEBOUNCE} from "@/Constants/constants.js";

const props = defineProps({
    giam_sat: Object,
    tuyen_duong: Object,
    don_vi: Object,
})

const giam_sat_selected = ref(null);
const key = ref(0);
const changePage = (page) => {
    router.visit(route('giam-sat.index', {page: page, ten_duong: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const columns = [
    {field: 'id', label: 'ID'},
    {field: 'tuyen_duong.ten', label: 'Tên đường'},
    {field: 'tuyen_duong.loai', label: 'Tên đường'},
    {field: 'tuyen_duong.ma_phan_cap', label: 'Mã phân cấp'},
    {field: 'tuyen_duong.diem_dau_xa.name', label: 'Điểm đầu'},
    {field: 'tuyen_duong.diem_cuoi_xa.name', label: 'Điểm cuối'},
    {field: 'tuyen_duong.chieu_dai', label: 'Chiều dài'},
    {field: 'tuyen_duong.chieu_rong', label: 'Chiều rộng'},
    {field: 'tuyen_duong.dien_tich', label: 'Chiều rộng'},
    {field: 'don_vi.ten', label: 'Đơn vị giám sát'},
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
        giam_sat_selected.value = props.giam_sat.data.find(item => item.id === id);
        modal.showModal();
    });
}

const openModal = () => {
    giam_sat_selected.value = null;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
    router.visit(route('giam-sat.index', {ten_duong: value}), {
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
             <button @click.prevent="openModal" class="btn btn-success">Thêm giám sát</button>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm giám sát">
         </div>
         <table :key="key"
                class="table table-striped text-2xl"
                v-data="{ data: giam_sat.data, columns: columns }">
         </table>
         <Pagination
             :all-data="giam_sat"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :giam_sat="giam_sat_selected"
         :tuyen_duong="tuyen_duong"
         :don_vi="don_vi"
     />
</MainLayout>
</template>
