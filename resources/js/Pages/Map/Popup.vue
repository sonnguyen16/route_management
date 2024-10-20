<script setup>

import {loaiOptions, loaiTuanTraOptions, maPhanCapOptions} from "@/Constants/constants.js";

const props = defineProps({
    selectedRoute: Object,
    isSheetOpen: Boolean
})

const emits = defineEmits(['closeSheet'])

</script>

<template>
    <!-- Sheet hiển thị thông tin tuyến đường -->
    <div v-if="isSheetOpen" class="sheet">
        <div class="sheet-header">
            <h5 class="mb-0">Thông tin tuyến đường</h5>
            <button @click.prevent="emits('closeSheet')">Đóng</button>
        </div>
        <div class="sheet-content px-2">
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
</style>
