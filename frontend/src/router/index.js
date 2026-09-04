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
            meta: {
                title: "Play Point"
            }
        },
        {
            path: "/admin/login",
            name: "Login",
            component: () => import("../view/admin/Login.vue"),
            meta: {
                title: "Login Admin"
            }
        },
        {
            path: "/rental/code",
            name: "RentalCode",
            component: () => import("../view/user/LoginCode.vue"),
            meta: {
                title: "Rental Code"
            }
        },
        {
            path: "/rental/confirmation",
            name: "Confirmation",
            component: () => import("../view/user/Confirmation.vue"),
            meta: {
                title: "Confirmation"
            }
        },
        {
            path: "/rental/session",
            name: "ActiveSession",
            component: () => import("../view/user/ActiveSession.vue"),
            meta: {
                title: "Active Session"
            }
        },

        //----------  
        // Admin Routes
        //----------  
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
        },
        {
            path: '/admin/customers',
            name: 'AdminCustomers',
            component: () => import("../view/admin/Customer.vue")
        }
    ],
})

// TAMBAHKAN DI SINI
router.afterEach((to) => {
    // Mengecek apakah route punya meta.title, jika tidak ada maka gunakan route name, jika tidak ada lagi gunakan default 'Play Point'
    const pageTitle = to.meta.title || to.name || 'Play Point';

    // Hasilnya akan menjadi "Rental Code - Play Point" atau sekadar "Play Point" jika di Home
    document.title = pageTitle === 'Play Point' ? pageTitle : `${pageTitle} - Play Point`;
});

export default router;