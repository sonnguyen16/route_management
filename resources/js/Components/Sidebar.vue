<script setup>
import NavItem from "@/Components/NavItem.vue";

const links = [
    {
        title: "Quản lý bản đồ",
        href: "/map",
    },
    {
        title: "Đơn vị",
        href: "/don-vi",
    },
    {
        title: "Quản lý tuyến đường",
        href: "/tuyen-duong",
    },
    {
        title: "Quản lý sửa chữa",
        href: "/sua-chua",
    },
    {
        title: "Quản lý công văn",
        href: "/cong-van",
    },
    {
        title: "Quản lý đường cấm",
        href: "/duong-cam",
    },
    {
        title: "Quản lý giám sát",
        href: "/giam-sat",
    },

    {
        title: "Quản lý giới hạn tốc độ",
        href: "/gioi-han-toc-do",
    },
    {
        title: "Quản lý cấp phép",
        href: "/cap-phep",
    },
    // {
    //     title: "Quản lý đơn vị",
    //     href: "/don-vi",
    // },
    {
        title: "Quản lý người dùng",
        href: "/nguoi-dung",
    },
    {
        title: "Đăng xuất",
        href: "/logout",
    },
]

const isActive = (href) => {
    return window.location.pathname === href;
}

</script>

<template>
  <aside
    class="main-sidebar sidebar-collapse sidebar-light-primary border-top border-right"
  >
    <div class="sidebar p-0">
        <nav class="mt-3">
            <ul class="nav nav-pills nav-child-indent nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item menu">
                    <a href="/map" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý bản đồ
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý tuyến đường
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item ">
                          <a href="tuyen-duong" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Tuyến đường</p>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a href="don-vi" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Đơn vị quản lý</p>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a href="tuyen-duong" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Danh sách xí nghiệp</p>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a href="/cau-hinh/loai-tuyen-duong" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Phân loại</p>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a href="/cau-hinh/phan-cap" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Phân cấp</p>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a href="/cau-hinh/loai-tuan-tra" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Loại tuần tra</p>
                          </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý cầu  <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                      <li class="nav-item ">
                          <a href="/cau" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Danh sách cầu</p>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a href="/cau-hinh/loai-ket-cau-nhip" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Loại kết cấu nhịp</p>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a href="/cau-hinh/loai-cau" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Loại cầu</p>
                          </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu">
                    <a href="/den-giao-thong" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý đèn giao thông
                        </p>
                    </a>
                </li>
                <li class="nav-item menu">
                    <a href="/diem-tai-nan" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Điểm hay xảy ra tai nạn
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý sửa chữa
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item ">
                          <a href="/sua-chua" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Tuyến đường sửa chữa</p>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a href="/cau-hinh/loai-sua-chua" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Loại sửa chữa</p>
                          </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý công văn
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item ">
                          <a href="cong-van" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Danh sách công văn</p>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a href="/cau-hinh/loai-cong-van" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Loại công văn</p>
                          </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý đường cấm
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item ">
                          <a href="/duong-cam" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Danh sách đường cấm</p>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a href="/cau-hinh/loai-cong-van" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Đơn vị quyết định</p>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a href="/cau-hinh/loai-cong-van" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Đơn vị thực hiện</p>
                          </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý giám sát
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item ">
                          <a href="/giam-sat" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Danh sách đường</p>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a href="/cau-hinh/loai-cong-van" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Đơn vị giám sát</p>
                          </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý giới hạn tốc độ
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item ">
                          <a href="/gioi-han-toc-do" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Danh sách đường giới hạn</p>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a href="/cau-hinh/loai-gioi-han" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Loại giới hạn</p>
                          </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý cấp phép
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item ">
                          <a href="cap-phep" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Danh sách đường cấp phép</p>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a href="/cau-hinh/loai-gioi-han" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Đơn vị cấp phép</p>
                          </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Cài đặt hệ thống
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item ">
                          <a href="nguoi-dung" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Người dùng</p>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a href="" class="nav-link ">
                            <i class="fa-solid nav-icon"><img class="d-inline-block me-2" src="@/assets/img/widgets.png" alt="icon"></i>
                            <p> Lịch sử hoạt động</p>
                          </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu">
                    <a href="/logout" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Thoát phần mềm
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    <!--
      <nav class="">
        <ul
          class="nav nav-pills nav-child-indent nav-sidebar flex-column"
          data-widget="treeview"
          role="menu"
          data-accordion="false"
        >
            <NavItem
                v-for="(link, index) in links"
                :key="index"
                :title="link.title"
                :href="link.href"
                :class="[isActive(link.href) && 'bg-gray-200']"
            />
        </ul>
      </nav> -->
    </div>
  </aside>
</template>
<style scoped>
    @media (width >= 768px) {
        .main-sidebar {
            height: calc(100vh - 51px) !important;
        }
    }

    @media (width < 768px) {
        .main-sidebar {
            height: 100vh !important;
        }
    }
</style>


