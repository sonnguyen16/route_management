<script setup>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";

const form = useForm({
    email: "",
    password: ""
})
const submit = () => {
    form.post(route('auth.login'), {
        onFinish: () => {
            form.reset('password');
        },
    })
}
</script>

<template>
    <AuthLayout>
        <div class="row vh-100">
            <div class="col d-none d-xl-flex justify-content-center align-items-center flex-column">
                <h1 class="font-weight-bold text-primary">Quản lý hệ thống</h1>
            </div>
            <div id="app" class="col bg-light-blue d-flex justify-content-center flex-column m-4" style="border-radius: 8px">
                <div class="row">
                    <div class="col-md-6 mx-auto">
                        <div class="card mx-auto shadow-lg pt-5 pb-5 pl-4 pr-4 " style="border-radius: 8px">
                            <img src="" width="150px" class="mb-3 d-lg-none d-block mx-auto" alt="">
                            <div class="card-body">

                                <p class="font-weight-bold mb-4 text-center text-lg txt-color" style="">Chào mừng đã trở lại!</p>

                                <form @submit.prevent="submit">
                                    <div class="input-group mb-1" :class="{'border border-danger' : form.errors.email}">
                                        <input v-model="form.email"
                                               type="text" id="email"
                                               class="form-control"
                                               placeholder="Mã tài khoản" >
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-user"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <InputError :message="form.errors.email" />

                                    <div class="input-group mb-1" :class="{'border border-danger' : form.errors.password}">
                                        <input autocomplete=""
                                               v-model="form.password"
                                               type="password"
                                               id="password"
                                               class="form-control"
                                               placeholder="Password" >
                                        <div class="input-group-append">
                                            <div class="input-group-text" >
                                                <span class="fas fa-lock"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <InputError :message="form.errors.password" />
                                    <button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>

