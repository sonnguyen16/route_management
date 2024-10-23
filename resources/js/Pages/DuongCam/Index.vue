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
const isEdit = ref(false);
const key = ref(0);
const keyModal = ref(0);
const tu_ngay = ref('');
const den_ngay = ref('');
const changePage = (page) => {
    router.visit(route('duong-cam.index', {
        page: page,
        ten_duong: search.value,
        tu_ngay: tu_ngay.value,
        den_ngay: den_ngay.value
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
    {field: 'tu_ngay', label: 'Từ ngày'},
    {field: 'den_ngay', label: 'Đến ngày'},
    {field: 'don_vi_quyet_dinh.ten', label: 'Đơn vị quyết định'},
    {field: 'don_vi_thuc_hien.ten', label: 'Đơn vị thực hiện'},
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
    if (duong_cam_selected.value) {
        duong_cam_selected.value = props.duong_cam.data.
        find(item => item.id === duong_cam_selected.value.id);
    }
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        duong_cam_selected.value = props.duong_cam.data.find(item => item.id === id);
        isEdit.value = true;
        keyModal.value++
        modal.showModal();
    });
}

const openModal = () => {
    duong_cam_selected.value = null;
    isEdit.value = false;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

watch(tu_ngay, (value) => {
    den_ngay.value = '';
    router.visit(route('duong-cam.index', {
        tu_ngay: value,
        den_ngay: den_ngay.value,
        ten_duong: search.value
    }), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
})

watch(den_ngay, (value) => {
    router.visit(route('duong-cam.index', {
        den_ngay: value,
        tu_ngay: tu_ngay.value,
        ten_duong: search.value
    }), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
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
             <div class="flex gap-5 items-center">
                 <button @click.prevent="openModal" class="btn btn-success w-[200px]">Thêm đường cấm</button>
                 <div class="flex items-center w-[300px] gap-2">
                     <label class="mb-0" for="ngay_khoi_cong">Từ ngày</label>
                     <input v-model="tu_ngay" type="date" class="form-control flex-1" placeholder="Ngày khởi công"/>
                 </div>
                 <div class="flex items-center w-[300px] gap-2">
                     <label class="mb-0" for="ngay_hoan_thanh">Đến ngày</label>
                     <input v-model="den_ngay" type="date" class="form-control flex-1" placeholder="Ngày hoàn thành"/>
                 </div>
             </div>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm đường cấm">
         </div>
         <div class="table-responsive">
             <table :key="key"
                    class="table table-striped text-2xl"
                    v-data="{ data: duong_cam.data, columns: columns }">
             </table>
         </div>
         <Pagination
             :all-data="duong_cam"
             @changePage="changePage"
         />
     </div>
     <Modal
         @close-modal="modal.hideModal"
         @refresh="onRefresh"
         :is-edit="isEdit"
         :key-modal="keyModal"
         :tuyen_duong="tuyen_duong"
         :duong_cam="duong_cam_selected"
         :don_vi="don_vi"
     />
</MainLayout>
</template>
