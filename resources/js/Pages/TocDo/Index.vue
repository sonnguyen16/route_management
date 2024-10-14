<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import Modal from "@/Pages/TocDo/Modal.vue";
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

const key = ref(0);
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
    {field: 'tuyen_duong.loai', label: 'Loại', enums: loaiOptions},
    {field: 'tuyen_duong.ma_phan_cap', label: 'Loại', enums: maPhanCapOptions},
    {field: 'tuyen_duong.diem_dau_xa.name', label: 'Điểm đầu'},
    {field: 'tuyen_duong.diem_cuoi_xa.name', label: 'Điểm cuối'},
    {field: 'tu_km', label: 'Từ km'},
    {field: 'den_km', label: 'Đến km'},
    {field: 'toc_do', label: 'Tốc độ'},
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
        gioi_han_toc_do_selected.value = props.gioi_han_toc_do.data.find(item => item.id === id);
        modal.showModal();
    });
}

const openModal = () => {
    gioi_han_toc_do_selected.value = null;
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

</script>

<template>
 <MainLayout>
     <div class="py-3 px-4">
         <div class="mb-3 flex justify-between">
             <button @click.prevent="openModal" class="btn btn-success">Thêm giới hạn tốc độ</button>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm giới hạn tốc độ">
         </div>
         <table :key="key"
                class="table table-striped text-2xl"
                v-data="{ data: gioi_han_toc_do.data, columns: columns }">
         </table>
         <Pagination
             :all-data="gioi_han_toc_do"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :tuyen_duong="tuyen_duong"
         :gioi_han_toc_do="gioi_han_toc_do_selected"
         :don_vi="don_vi"
         :nguoi_duyet="nguoi_duyet"
     />
</MainLayout>
</template>
