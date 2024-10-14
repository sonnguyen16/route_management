<script setup>
import NavItem from "@/Components/NavItem.vue";

const links = [
    {
        title: "Quản lý bản đồ",
        href: "/map",
    },
    {
        title: "Quản lý tuyến đường",
        href: "/tuyen-duong",
    },
    {
        title: "Quản lý giám sát",
        href: "/giam-sat",
    },
    {
        title: "Quản lý sửa chữa",
        href: "/sua-chua",
    },
    {
        title: "Quản lý giới hạn tốc độ",
        href: "/gioi-han-toc-do",
    },
    {
        title: "Quản lý công văn",
        href: "/cong-van",
    },
    {
        title: "Quản lý cấp phép",
        href: "/cap-phep",
    },
    {
        title: "Quản lý đường cấm",
        href: "/duong-cam",
    },
    {
        title: "Quản lý đơn vị",
        href: "/don-vi",
    },
    {
        title: "Quản lý tài liệu",
        href: "/tai-lieu",
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
  >
    <div class="sidebar p-0">
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
      </nav>
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


