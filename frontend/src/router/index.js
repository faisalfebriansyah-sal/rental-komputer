import { createRouter, createWebHistory } from "vue-router";


const router = createRouter({
    history: createWebHistory(),
    routes: [

        //----------  
        // User Routes
        //----------  
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

        //----------  
        // Admin Routes
        //----------  
        {
            path: "/admin/login",
            name: "Login",
            component: () => import("../view/admin/Login.vue")
        },
        {
            path: "/admin/dashboard",
            name: "AdminDashboard",
            component: () => import("../view/admin/Dashboard.vue")
        },
        {
            path: '/admin/perangkat',
            name: 'AdminPerangkat',
            component: () => import("../view/admin/Perangkat.vue")
        },
        {
            path: '/admin/rental',
            name: 'AdminRental',
            component: () => import("../view/admin/Rentals.vue")
        },
        {
            path: '/admin/transaksi',
            name: 'AdminTransaksi',
            component: () => import("../view/admin/Transaksi.vue")
        },
        {
            path: '/admin/settings',
            name: 'AdminSettings',
            component: () => import("../view/admin/Settings.vue")
        }
    ],
})

export default router