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
import Question from "@/Components/Question.vue";

const props = defineProps({
    duong_cam: Object,
    tuyen_duong: Object,
    don_vi: Object,
    stt: Number,
})

const duong_cam_selected = ref(null);
const isEdit = ref(false);
const key = ref(0);
const keyModal = ref(0);
const tu_ngay = ref('');
const den_ngay = ref('');
const diem_cam_selected = ref(null);
const duong_cam_cha_selected = ref(null);
const flag = ref(false);

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


const modal = useModal('modal');

onMounted(() => {
  //  eventForEditBtn()
})

const onRefresh = () => {
    key.value++
    keyModal.value++
    nextTick(() => {
      //  eventForEditBtn()
    })
    if (duong_cam_selected.value) {
        duong_cam_selected.value = props.duong_cam.data.
        find(item => item.id === duong_cam_selected.value.id);
    }
}

const openModal = (item) => {
    keyModal.value++;
    duong_cam_selected.value = null;
    duong_cam_cha_selected.value = item;
    if(item && item.id) {
        flag.value = true;
    } else {
        flag.value = false;
    }
    isEdit.value = false;
    modal.showModal();
}

const editModal = (item) => {    
    duong_cam_selected.value = item;
    if (item.duong_cam_id) {
        duong_cam_cha_selected.value = props.duong_cam.data.find(item => item.id === item.duong_cam_id);
        flag.value = true;
    } else {
        duong_cam_cha_selected.value = null;
        flag.value = false;
    }
 
    isEdit.value = true;
    keyModal.value++;
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

// ket thuc
const msg = ref(null);
const question = useModal('question');
const remove = (item) => {    
    msg.value ="Bạn có muốn xóa: "+( item.noi_dung ? item.noi_dung : item.tuyen_duong.ten) +"?";
    duong_cam_selected.value =  item;
    question.showModal();
}
const drop = (item) => {
    question.hideModal();
    router.visit(route('duong-cam.delete', {id: item.id}), {
        preserveState: true,
        onSuccess: () => {
            onRefresh()
        }
    });
}
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
            <table class="table table-striped text-2xl table-line">
                <thead>
                    <tr>
                    <th class="text-center"></th>
                    <th class="text-left">Tuyến đường</th>
                    <th class="text-left">Nội dung</th>
                    <th class="text-left">Từ km</th>
                    <th class="text-left">Đến km</th>
                    <th class="text-center">Từ ngày</th>
                    <th class="text-center">Đến ngày</th>
                    <th class="text-left">Đơn vị quyết định</th>
                    <th class="text-left">Đơn vị thực hiện</th>
                    
                    <th class="text-left">File đính kèm</th>
                    <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(it,i) in duong_cam.data" :key="i">
                    <tr v-if="!it.duong_cam_id" >
                    <td class="text-center" scope="row">{{ props.stt + i }}</td>
                    <td class="text-left" scope="row">{{ it.tuyen_duong ? it.tuyen_duong.ten : ''}}</td>
                    <td class="text-left" scope="row"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center" ></td>
                    <td class="text-center" ></td>
                    
                    <td class="text-left" scope="row"></td>
                    <td class="text-left" scope="row"></td>
                        <td style="vertical-align: unset !important;">
                            <label style="font-weight: normal;color: #007bff;" @click.prevent="chooseFile(it.id)"
                            class="cursor-pointer border-0 w-full text-start rounded-md mb-0">
                            <i class="fa fa-paperclip mr-2"></i>
                            </label>
                            <Upload
                                :listFile ="it.tai_lieu"
                                @refresh="onRefresh"
                                
                            />
                        </td>
                    <td class="text-center">
                        <a @click.prevent="editModal(it)" class=" cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a>
                        <a @click.prevent="remove(it)"  class="edit cursor-pointer" title="Xóa"><i class="fas fa-times-circle mr-1"></i></a>
                    </td>
                </tr>
                <tr v-for="(item,i) in it.doan_duong" :key="i">
                    <td class="text-center" scope="row"></td>
                    <td class="text-left" scope="row"></td>
                    <td class="text-left" scope="row">{{ item.noi_dung }}</td>
                    <td class="text-center">{{ item.tu_km}}</td>
                    <td class="text-center">{{ item.den_km}}</td>
                    <td class="text-center" > <span v-if="item.tu_ngay">{{ moment(item.tu_ngay).format("DD/MM/YYYY") }}</span></td>
                    <td class="text-center" ><span v-if="item.den_ngay">{{ moment(item.den_ngay).format("DD/MM/YYYY") }}</span></td>
                   
                    <td class="text-left" scope="row"><span v-if="item.don_vi_quyet_dinh">{{ item.don_vi_quyet_dinh.ten ? item.don_vi_quyet_dinh.ten : ''}}</span></td>
                    <td class="text-left" scope="row"><span v-if="item.don_vi_thuc_hien">{{ item.don_vi_thuc_hien.ten ? item.don_vi_thuc_hien.ten : ''}}</span></td>
                    
                    
                        <td style="vertical-align: unset !important;">
                            <label style="font-weight: normal;color: #007bff;" @click.prevent="chooseFile(item.id)"
                            class="cursor-pointer border-0 w-full text-start rounded-md mb-0">
                            <i class="fa fa-paperclip mr-2"></i>
                            </label>
                            <Upload
                                :listFile ="item.tai_lieu"
                                @refresh="onRefresh"
                                
                            />
                        </td>
                    <td class="text-center">
                        <a @click.prevent="editModal(item)" class=" cursor-pointer" title="Sửa"><i class="fas fa-edit mr-2"></i></a>
                        <a @click.prevent="remove(item)"  class="edit cursor-pointer" title="Xóa"><i class="fas fa-times-circle mr-1"></i></a>
                    </td>
                </tr>

                <tr v-if="!it.duong_cam_id">
                    <td class="text-left" scope="row"></td>
                    <td class="text-left" scope="row"><a @click.prevent="openModal(it)" class="edit cursor-pointer" title="Thêm đoạn đường"><i class="fas fa-plus mr-2"></i></a></td>
                   
                    <td class="text-left" scope="row"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center" ></td>
                    <td class="text-center" ></td>
                    
                    <td class="text-left" scope="row"></td>
                    <td class="text-left" scope="row"></td>
                        <td style="vertical-align: unset !important;">
                           
                        </td>
                    <td class="text-center"></td>
                </tr>

            </template>
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
         :duong_cam_cha="duong_cam_cha_selected"
         :don_vi="don_vi"
         :flag="flag"
     />
     <Question
        @close-modal="question.hideModal"
        @drop="drop"
        :obj="duong_cam_selected"
        :msg="msg"
    />
</MainLayout>
</template>
