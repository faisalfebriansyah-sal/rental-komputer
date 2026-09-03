import { createRouter, createWebHistory } from "vue-router";


const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            name: "Home",
            component: () => import("../view/user/HomeView.vue"),
        },
        {
            path: "/admin/login",
            name: "Login",
            component: () => import("../view/admin/Login.vue"),
        },
        {
            path: "/rental/code",
            name: "RentalCode",
            component: () => import("../view/user/LoginCode.vue"),
        },
        {
            path: "/rental/confirmation",
            name: "Confirmation",
            component: () => import("../view/user/Confirmation.vue"),
        },
        {
            path: "/rental/session",
            name: "ActiveSession",
            component: () => import("../view/user/ActiveSession.vue"),
        },
        {
            path: "/admin/login",
            name: "Login",
            component: () => import("../view/admin/Login.vue")
        }
    ],
})

export default router