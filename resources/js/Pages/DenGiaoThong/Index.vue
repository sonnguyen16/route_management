<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import Modal from "@/Pages/DenGiaoThong/Modal.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref, watch} from "vue";
import { debounce } from 'lodash';
import {_TIME_DEBOUNCE, maPhanCapOptions} from "@/Constants/constants.js";

const props = defineProps({
    den_giao_thong: Object,
})

const den_giao_thong_selected = ref(null);
const isEdit = ref(false);
const key = ref(0);
const keyModal = ref(0);
const changePage = (page) => {
    router.visit(route('den-giao-thong.index', {page: page, nut_giao: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const columns = [
    {field: 'id', label: 'ID'},
    {field: 'nut_giao', label: 'Nút giao'},
    {field: 'so_pha_den', label: 'Số pha đèn'},
    {field: 'thoi_gian_pha_den', label: 'Thời gian pha đèn'},
    {field: 'thoi_gian_hoat_dong', label: 'Thời gian hoạt động'},
    {field: 'lat', label: 'Lat'},
    {field: 'lng', label: 'Lng'},
    {field: 'action', label: 'Hành động'},
]
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
    if (den_giao_thong_selected.value) {
        den_giao_thong_selected.value = props.den_giao_thong.data.
        find(item => item.id === den_giao_thong_selected.value.id);
    }
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        console.log(id);
        den_giao_thong_selected.value = props.den_giao_thong.data.find(item => item.id === id);
        isEdit.value = true;
        keyModal.value++
        modal.showModal();
    });
}

const openModal = () => {
    den_giao_thong_selected.value = null;
    isEdit.value = false;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
    router.visit(route('den-giao-thong.index', {search: value}), {
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
             <button @click.prevent="openModal" class="btn btn-success">Thêm đèn giao thông</button>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm nút giao">
         </div>
         <div class="table-responsive">
             <table :key="key"
                    class="table table-striped text-2xl"
                    v-data="{ data: den_giao_thong.data, columns: columns }">
             </table>
         </div>
         <Pagination
             :all-data="obj"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :is-edit="isEdit"
         :key-modal="keyModal"
         :den_giao_thong="den_giao_thong_selected"
     />
</MainLayout>
</template>
