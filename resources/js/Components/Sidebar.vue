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
    {
        title: "Quản lý đơn vị",
        href: "/don-vi",
    },
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
    style="max-width: 190px;"
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
                <li class="nav-item menu">
                    <a :href="route('don-vi.index')" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý đơn vị
                        </p>
                    </a>
                </li>
                <li class="nav-item menu">
                    <a :href="route('tuyen-duong.index')" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý tuyến đường
                        </p>
                    </a>
                </li>
                <li class="nav-item menu">
                    <a :href="route('sua-chua.index')" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý sửa chữa
                        </p>
                    </a>
                </li>
                <li class="nav-item menu">
                    <a :href="route('duong-cam.index')" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý đường cấm
                        </p>
                    </a>
                </li>
                <li class="nav-item menu">
                    <a :href="route('giam-sat.index')" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý giám sát
                        </p>
                    </a>
                </li>
                <li class="nav-item menu">
                    <a :href="route('gioi-han-toc-do.index')" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý giới hạn tốc độ
                        </p>
                    </a>
                </li>
                <li class="nav-item menu">
                    <a :href="route('cap-phep.index')" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý cấp phép
                        </p>
                    </a>
                </li>
                <li class="nav-item menu">
                    <a :href="route('cau.index')" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý cầu
                        </p>
                    </a>
                </li>
                <li class="nav-item menu">
                    <a :href="route('den-giao-thong.index')" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý đèn giao thông
                        </p>
                    </a>
                </li>
                <li class="nav-item menu">
                    <a :href="route('cong-van.index')" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Quản lý công văn
                        </p>
                    </a>
                </li>
                <li class="nav-item menu">
                    <a :href="route('diem-tai-nan.index')" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Điểm hay xảy ra tai nạn
                        </p>
                    </a>
                </li>
                <li class="nav-item menu">
                    <a :href="route('nguoi-dung.index')" class="nav-link">
                        <p class="txt-color font-weight-bold">
                            <img class="d-inline-block me-2" src="@/assets/img/ico.png" alt="icon">Người dùng
                        </p>
                    </a>
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


