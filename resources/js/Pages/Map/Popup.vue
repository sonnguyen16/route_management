<script setup>
import { loaiOptions, loaiTuanTraOptions, maPhanCapOptions } from "@/Constants/constants.js";
import { ref } from "vue";
import File from "@/Components/File.vue";

const props = defineProps({
    selectedRoute: Object,
    isSheetOpen: Boolean,
});

const emits = defineEmits(['closeSheet', 'removeFileUploaded']);

const currentTab = ref('info');

</script>

<template>
    <div v-if="isSheetOpen" class="sheet">
        <div class="sheet-header">
            <h5 class="mb-0">Thông tin tuyến đường</h5>
            <button @click.prevent="emits('closeSheet')">Đóng</button>
        </div>

        <div class="tabs">
            <button
                :class="{ active: currentTab === 'info' }"
                @click="currentTab = 'info'"
            >
                Thông tin
            </button>
            <button
                :class="{ active: currentTab === 'documents' }"
                @click="currentTab = 'documents'"
            >
                Tài liệu
            </button>
        </div>

        <div class="sheet-content px-2">
            <!-- Tab Thông tin -->
            <div v-if="currentTab === 'info'">
                <table class="table table-striped text-2xl">
                    <tr>
                        <th>Tên đoạn:</th>
                        <td>{{ selectedRoute.ten }}</td>
                    </tr>
                    <tr>
                        <th>Mã phân cấp:</th>
                        <td>{{ maPhanCapOptions.find(m => m.id === selectedRoute.ma_phan_cap)?.name }}</td>
                    </tr>
                    <tr>
                        <th>Loại đường:</th>
                        <td>{{ loaiOptions.find(m => m.id === selectedRoute.loai)?.name }}</td>
                    </tr>
                    <tr>
                        <th>Điểm đầu:</th>
                        <td>{{ selectedRoute.diem_dau_xa?.name }}</td>
                    </tr>
                    <tr>
                        <th>Điểm cuối:</th>
                        <td>{{ selectedRoute.diem_cuoi_xa?.name }}</td>
                    </tr>
                    <tr>
                        <th>Chiều rộng (m):</th>
                        <td>{{ selectedRoute.chieu_rong }}</td>
                    </tr>
                    <tr>
                        <th>Chiều dài (m):</th>
                        <td>{{ selectedRoute.chieu_dai }}</td>
                    </tr>
                    <tr>
                        <th>Diện tích (m²):</th>
                        <td>{{ selectedRoute.dien_tich }}</td>
                    </tr>
                    <tr>
                        <th>Loại tuần tra:</th>
                        <td>{{ loaiTuanTraOptions.find(m => m.id === selectedRoute.loai_tuan_tra)?.name }}</td>
                    </tr>
                    <tr>
                        <th>Đơn vị quản lý:</th>
                        <td>{{ selectedRoute.don_vi?.ten }}</td>
                    </tr>
                    <tr>
                        <th>Xí nghiệp:</th>
                        <td>{{ selectedRoute.xi_nghiep }}</td>
                    </tr>
                </table>
            </div>

            <!-- Tab Tài liệu -->
            <div v-if="currentTab === 'documents'">
                <h6 class="mt-2">Danh sách tài liệu:</h6>
                <ul v-if="selectedRoute.tai_lieu && selectedRoute.tai_lieu.length > 0">
                    <File :file="file" v-for="file in selectedRoute.tai_lieu"
                          :key="file.id" :enable-delete="false"
                    />
                </ul>
                <p v-else>Không có tài liệu nào.</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.sheet {
    position: absolute;
    top: 0;
    left: 0;
    width: 300px;
    height: 100vh;
    background-color: white;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    overflow-y: auto;
}

.sheet-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background-color: #f1f1f1;
    border-bottom: 1px solid #ddd;
}

.tabs {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.tabs button {
    flex-basis: 20px;
    flex-grow: 1;
    padding: 10px;
    cursor: pointer;
    background-color: #fff;
    border: none;
    border-bottom: 2px solid transparent;
}

.tabs button.active {
    background-color: #f1f1f1;
    border-bottom: 2px solid #007bff;
}

.sheet-content {
    padding: 10px;
}

.table {
    width: 100%;
}

.table th {
    text-align: left;
}

.table td {
    text-align: right;
}
</style>
