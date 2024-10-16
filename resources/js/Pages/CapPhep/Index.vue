<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import Modal from "@/Pages/CapPhep/Modal.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref, watch} from "vue";
import { debounce } from 'lodash';
import {_TIME_DEBOUNCE} from "@/Constants/constants.js";

const props = defineProps({
    cap_phep: Object,
    tuyen_duong: Object,
    don_vi: Object,

})

const cap_phep_selected = ref(null);
const key = ref(0);
const isEdit = ref(false);
const changePage = (page) => {
    router.visit(route('cap-phep.index', {page: page, ten_duong: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const columns = [
    {field: 'id', label: 'ID'},
    {field: 'tuyen_duong.ten', label: 'Tên đường'},
    {field: 'so_cap_phep', label: 'Số cấp phép'},
    {field: 'ngay_cap_phep', label: 'Ngày cấp phép'},
    {field: 'tuyen_duong.diem_dau_xa.name', label: 'Điểm đầu'},
    {field: 'tuyen_duong.diem_cuoi_xa.name', label: 'Điểm cuối'},
    {field: 'don_vi.ten', label: 'Đơn vị cấp phép'},
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
    if(cap_phep_selected.value) {
        cap_phep_selected.value = props.cap_phep.data.find(item => item.id === cap_phep_selected.value.id);
    }
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        cap_phep_selected.value = props.cap_phep.data.find(item => item.id === id);
        isEdit.value = true;
        modal.showModal();
    });
}

const openModal = () => {
    cap_phep_selected.value = null;
    isEdit.value = false;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
    router.visit(route('cap-phep.index', {ten_duong: value}), {
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
             <button @click.prevent="openModal" class="btn btn-success">Thêm cấp phép</button>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm cấp phép">
         </div>
         <table :key="key"
                class="table table-striped text-2xl"
                v-data="{ data: cap_phep.data, columns: columns }">
         </table>
         <Pagination
             :all-data="cap_phep"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :is-edit="isEdit"
         :cap_phep="cap_phep_selected"
         :tuyen_duong="tuyen_duong"
         :don_vi="don_vi"
     />
</MainLayout>
</template>
