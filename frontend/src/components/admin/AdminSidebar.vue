<script setup>
import { useRoute, useRouter } from "vue-router";
import {ref, onMounted} from "vue";
import {
  LayoutDashboard,
  Monitor,
  Ticket,
  CreditCard,
  Settings,
  LogOut,
  Gamepad2,
  User2
} from "lucide-vue-next";


const router = useRouter()
const route = useRoute();

const loading = ref(false);
const admin = ref(null)



const menuItems = [
  {
    name: "Dashboard",
    icon: LayoutDashboard,
    path: "/admin/dashboard",
  },
  {
    name: "Pelanggan",
    icon: User2,
    path: "/admin/customers",
  },
  {
    name: "Perangkat",
    icon: Monitor,
    path: "/admin/perangkat",
  },
  {
    name: "Rental",
    icon: Ticket,
    path: "/admin/rental",
  },
  {
    name: "Transaksi",
    icon: CreditCard,
    path: "/admin/transaksi",
  },
  {
    name: "Pengaturan",
    icon: Settings,
    path: "/admin/settings",
  },
];

const logout = async () => {
  loading.value = true

  try{
    const token = localStorage.getItem("token")

    await fetch("http://10.10.9.26:8000/api/logout", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        Authorization: `Bearer ${token}`
      },
    });

    localStorage.removeItem("token");

    router.push("/admin/login");
  } catch(err){
    console.error(err)
  } finally {
    loading.value = false
  }
};

const getAdmin = async () => {
  try{
    const token = localStorage.getItem("token");

    const response = await fetch("http://10.10.9.26:8000/api/me", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        Authorization: `Bearer ${token}`
      }
    });

    const data = await response.json();

    if(!response.ok){
      throw new Error(data.message || "Gagal mengambil data admin");
    }

    admin.value = await response.json();
  } catch(err){
    console.error(err)
  }
};


onMounted(() => {
  getAdmin();
});
</script>

<template>
  <aside
    class="fixed left-0 top-0 hidden h-screen w-64 bg-[#4682A9] p-6 text-white lg:flex lg:flex-col"
  >
    <!-- Logo -->
    <div class="flex items-center gap-3">
      <div
        class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/15 text-lg"
      >
        <Gamepad2 :size="21" :stroke-width="1.8" />
      </div>

      <div>
        <p class="text-sm font-bold">
          RENTAL PC & PS
        </p>

        <p class="text-xs text-white/60">
          Admin Panel
        </p>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-12 space-y-2">
      <RouterLink
        v-for="item in menuItems"
        :key="item.path"
        :to="item.path"
        class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition"
        :class="
          route.path === item.path
            ? 'bg-white/15 text-white'
            : 'text-white/75 hover:bg-white/10 hover:text-white'
        "
      >
       <component
       :is="item.icon"
       :size="18"
       :stroke-width="1.8"
       />
        {{ item.name }}
      </RouterLink>
    </nav>

    <!-- Bottom -->
    <div class="mt-auto">
      <div class="mb-4 border-t border-white/15 pt-5">
        <div class="flex items-center gap-3">
          <div
            class="flex h-10 w-10 items-center justify-center rounded-full bg-white/15"
          >
            A
          </div>

          <div>
            <p class="text-sm font-medium">
              {{ admin?.name || "Admin" }}
            </p>

            <p class="text-xs text-white/60">
              {{ admin?.username || "Administrator" }}
            </p>
          </div>
        </div>
      </div>

     <button 
     @click="logout"
     :disabled="loading" 
     
     class="flex items-center gap-3 rounded-xl px-19 py-3 text-sm font-bold bg-red-500/100 text-white/70 transition hover:bg-red-500/90 hover:text-white cursor-pointer">
     
     <span class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white" v-if="loading"></span>
    <LogOut v-else
    :size="16"
    :stroke-width="1.8"
    />
     keluar
     </button>
    </div>
  </aside>
</template>