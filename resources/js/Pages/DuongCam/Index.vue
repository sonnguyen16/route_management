<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {vData} from "@/Directives/v-data.js";
import Pagination from "@/Components/Pagination.vue";
import {router, useForm} from "@inertiajs/vue3";
import Modal from "@/Pages/DuongCam/Modal.vue";
import ModelDiemCam from "@/Pages/DuongCam/ModalDiemCam.vue";
import {useModal} from "@/Hooks/useModal.js";
import {nextTick, onMounted, ref, watch} from "vue";
import {_TIME_DEBOUNCE} from "@/Constants/constants.js";
import { debounce } from 'lodash';
import Upload from "@/Components/UploadFile.vue";
import moment from "moment";

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
const diem_cam_selected = ref(null);

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
const Modeldiemcam = useModal('ModelDiemCam');

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
    keyModal.value++;
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

function themDiemCam(value) {
  duong_cam_selected.value = props.duong_cam.data.find(item => item.id === value);
  diem_cam_selected.value = null;
 keyModal.value++
 Modeldiemcam.showModal();
}
function suaDiemCam(value) {
    console.log(value);
    duong_cam_selected.value = props.duong_cam.data.find(item => item.id === value.duong_cam_id);
    diem_cam_selected.value = value;
    keyModal.value++
    Modeldiemcam.showModal();

}
function deleteDiemCam(value) {
    router.visit(route('tuyen-duong.deleteDiem', {id: value}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}


// upload hình ảnh
let formFile = useForm({
    type: '',
    danh_muc: '',
    file: []
})
var input = document.createElement('input');
input.type = 'file';
input.multiple = true;
input.onchange = e => { 
   formFile.file = e.target.files;
   formFile.type = 'duong_cam';
   formFile.post(route('tai-lieu.store'), {
        onSuccess: () => {
            onRefresh();
        },
        onError: (err) => {
            console.log(err)
        }
    })
}
const chooseFile = (id) => {
  formFile.danh_muc = id;
  input.click();
}
// ket thuc

</script>

<template>
 <MainLayout>
     <div class="py-3 px-4">
         <div class="mb-3 flex justify-between">
             <div class="flex gap-5 items-center">
                 <button @click.prevent="openModal" class="btn btn-success ">Thêm đường cấm</button> <!--w-[200px]-->
                <!-- <div class="flex items-center w-[300px] gap-2">
                     <label class="mb-0" for="ngay_khoi_cong">Từ ngày</label>
                     <input v-model="tu_ngay" type="date" class="form-control flex-1" placeholder="Ngày khởi công"/>
                 </div>
                 <div class="flex items-center w-[300px] gap-2">
                     <label class="mb-0" for="ngay_hoan_thanh">Đến ngày</label>
                     <input v-model="den_ngay" type="date" class="form-control flex-1" placeholder="Ngày hoàn thành"/>
                 </div> -->
             </div>
             <input v-model="search" class="border-gray-300 rounded-lg w-1/5" placeholder="Tìm kiếm đường cấm">
         </div>
         <div class="table-responsive">
            <table class="table table-striped text-2xl">
                <thead>
                    <tr>
                    <th class="text-center">STT</th>
                    <th class="text-center">Nội dung</th>
                    <th class="text-center">File đính kèm</th>
                    <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,i) in duong_cam.data" :key="i">
                    <td class="text-center" scope="row">{{ i+1 }}</td>
                    <td class="text-left" style="vertical-align: unset !important;">
                        <div>
                            Tuyến đường: <b>{{ item.tuyen_duong ? item.tuyen_duong.ten : ''}}</b><br>
                            Đơn vị quyết định: <b>{{ item.don_vi_quyet_dinh.ten ? item.don_vi_quyet_dinh.ten : ''}}</b><br>
                            Đơn vị thực hiện: <b>{{ item.don_vi_thuc_hien.ten ? item.don_vi_thuc_hien.ten : ''}}</b>
                        </div>
                        <div style="padding-left:10px"><a  @click.prevent="themDiemCam(item.id)"  class="newDiem cursor-pointer" title="Thêm điểm cấm"><i class="fas fa-plus mr-2"></i>Thêm điểm cấm</a></div>
                        <table v-if="item.diem_cam.length > 0" style="width: 100%;">
                            <tr>
                                <th class="text-left">Điểm cấm</th>
                                <th class="text-center">Từ km</th>
                                <th class="text-center">Đến km</th>
                                <th class="text-center" style="min-width:100px;">Từ ngày</th>
                                <th class="text-center" style="min-width:100px;">Đến ngày</th>
                                <th class="text-center" style="min-width:100px;">Nội dung</th>
                                <th></th>
                                <th></th>
                                </tr>
                            <tr v-for="(a,i) in item.diem_cam" :key="i">
                                <td ><!--<a :data-id=a.id class="editDiem cursor-pointer">-->
                                   <a href="#"  @click.prevent="suaDiemCam(a)">  {{ i+1 }}.{{ a.noi_dung }}</a><!--</a>-->
                                </td>
                                <td><span v-if="a.tu_km">{{ 'km '+a.tu_km}}</span></td>
                                <td><span v-if="a.den_km">{{ 'km '+a.den_km }}</span></td>
                                <td class="text-center"><span v-if="a.tu_ngay">{{ moment(a.tu_ngay).format("DD/MM/YYYY HH:mm") }}</span></td>
                                <td class="text-center"><span v-if="a.den_ngay">{{ moment(a.den_ngay).format("DD/MM/YYYY HH:mm") }}</span></td>
                                <td><span >{{ a.noi_dung }}</span></td>
                                <td><a href="#" @click.prevent="deleteDiemCam(a)" class="cursor-pointer"><i class="fa fa-times-circle mr-1"></i></a></td>
                               <td><a href="#" @click.prevent="suaDiemCam(a)" class=" cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a></td>
                            </tr>
                        </table>
                    </td>
                        <td style="vertical-align: unset !important;">
                            <label style="font-weight: normal;color: #007bff;" @click.prevent="chooseFile(item.id)"
                            class="cursor-pointer border-0 w-full text-start rounded-md mb-0">
                            <i class="fa fa-paperclip mr-2"></i>
                            Tải lên tệp
                            </label>
                            <Upload
                                :listFile ="item.tai_lieu"
                                @refresh="onRefresh"
                                
                            />
                        </td>
                    <td class="text-center"><a :data-id=item.id class="edit cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a></td>
                </tr>
                </tbody>
                </table>
          <!--   <table :key="key"
                    class="table table-striped text-2xl"
                    v-data="{ data: duong_cam.data, columns: columns }">
             </table> -->
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
     <ModelDiemCam 
      @close-modal="Modeldiemcam.hideModal"
     :duong_cam="duong_cam_selected" 
     :diem_cam="diem_cam_selected"
     :key-modal="keyModal"
     @refresh="onRefresh">
    </ModelDiemCam>
</MainLayout>
</template>
