<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router} from "@inertiajs/vue3";
import {nextTick, onMounted, ref, watch} from "vue";
import {debounce} from "lodash";
import {_TIME_DEBOUNCE} from "@/Constants/constants.js";
import {useModal} from "@/Hooks/useModal.js";
import Modal from "@/Pages/NguoiDung/Modal.vue";

const props = defineProps({
    nguoi_dung: Object,
})
const key = ref(0);
const keyModal = ref(0);
const nguoi_dung_selected = ref(null);
const changePage = (page) => {
    router.visit(route('nguoi-dung.index', {page: page, ten_duong: search.value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}

const columns = [
    {field: 'id', label: 'ID'},
    {field: 'name', label: 'Tên'},
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
    if (nguoi_dung_selected.value) {
        nguoi_dung_selected.value = props.nguoi_dung.data.
        find(item => item.id === nguoi_dung_selected.value.id);
    }
}
const eventForEditBtn = () => {
    $('.edit').click(function () {
        const id = $(this).data('id');
        nguoi_dung_selected.value = props.nguoi_dung.data.find(item => item.id === id);
        keyModal.value++
        modal.showModal();
    });
}

const openModal = () => {
    nguoi_dung_selected.value = null;
    modal.showModal();
}

const search = ref('');

watch(search, (value) => {
    searchDebounce(value)
})

const searchDebounce = debounce((value) => {
    router.visit(route('nguoi-dung.index', {search: value}), {
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
                <button @click.prevent="openModal" class="btn btn-success">Thêm người dùng</button>
                <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm người dùng">
            </div>
            <div class="table-responsive">
                <table :key="key" v-data="{ data: nguoi_dung.data, columns: columns }"
                class="table table-striped text-2xl">
                </table>
            </div>
            <Pagination
                :all-data="nguoi_dung"
                @changePage="changePage"
            />
        </div>
        <Modal
            :keyModal="keyModal"
            :nguoi_dung="nguoi_dung_selected"
            @closeModal="modal.hideModal"
            @refresh="onRefresh"
            />
    </MainLayout>
</template>
