<script setup>
import DropdownLink from "@/Components/DropdownLink.vue";

</script>

<template>
    <nav class="main-header navbar navbar-expand navbar-light sticky-top border-bottom">
        <!-- Left navbar links -->
        <ul class="navbar-nav ml-3">
            <li class="nav-item d-lg-none d-block mt-0">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="navbar-brand d-flex align-items-center" href="#">
                <h5 class="font-weight-bold d-md-inline d-none mb-0 text-primary">Quản lý hệ thống</h5>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-user"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right border-0 shadow">
                    <DropdownLink :href="route('logout')" class="dropdown-item" as="button">
                        Đăng xuất
                    </DropdownLink>
                </div>
            </li>
        </ul>
    </nav>
</template>

