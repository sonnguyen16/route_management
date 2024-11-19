<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import {nextTick, onMounted, ref, watch} from "vue";
import {debounce} from "lodash";
import {_TIME_DEBOUNCE} from "@/Constants/constants.js";
import {useModal} from "@/Hooks/useModal.js";
import Modal from "@/Pages/DonVi/Modal.vue";

const props = defineProps({
    obj: Object,
    loai: String,
    ten: String,
})
const key = ref(0);
const keyModal = ref(0);
const don_vi_selected = ref(null);
const isEdit = ref(false);
const changePage = (page) => {
    router.visit(route('don-vi.index', {page: page, ten_duong: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const columns = [
    {field: 'id', label: 'ID'},
    {field: 'ma_so_thue', label: 'Mã số thuế'},
    {field: 'ten', label: 'Tên'},
    {field: 'dia_chi', label: 'Địa chỉ'},
    {field: 'dien_thoai', label: 'Điện thoại'},
    {field: 'fax', label: 'Fax'},
    {field: 'email', label: 'Email'},
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
    if (don_vi_selected.value) {
        don_vi_selected.value = props.obj.data.
        find(item => item.id === don_vi_selected.value.id);
    }
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        don_vi_selected.value = props.obj.data.find(item => item.id === id);
        keyModal.value++
        isEdit.value = true;
        modal.showModal();
    });
}

const openModal = () => {
    don_vi_selected.value = null;
    modal.showModal();
    isEdit.value = false;
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
    console.log('aaa');
    router.visit(route('don-vi.index', {search: value, loai: props.loai}), {
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
                <button @click.prevent="openModal" class="btn btn-success">Thêm đơn vị</button>
                <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm">
            </div>
            <div class="table-responsive">
                <table :key="key" v-data="{ data: obj.data, columns: columns }"
                class="table table-striped text-2xl">
                </table>
            </div>
            <Pagination
                :all-data="obj"
                @changePage="changePage"
            />
        </div>
        <Modal
            :keyModal="keyModal"
            :don_vi="don_vi_selected"
            :loai="1"
            @closeModal="modal.hideModal"
            @refresh="onRefresh"
            :is-edit="isEdit"
            />
    </MainLayout>
</template>
