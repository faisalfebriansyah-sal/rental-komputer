<script setup>
import { ref } from "vue";
import AdminSidebar from "../../components/admin/AdminSidebar.vue";
import AdminHeader from "../../components/admin/AdminHeader.vue";
import {
  Monitor,
  Gamepad2,
  DollarSign,
  TrendingUp,
} from "lucide-vue-next";

const stats = ref([
  {
    icon: Monitor,
    title: "Total Perangkat",
    value: "20",
    subValue: "15 PC · 5 PlayStation",
    bgColor: "rgba(145, 200, 228, 0.25)",
    iconColor: "#4682A9",
    subTextColor: "text-gray-500",
  },
  {
    icon: Gamepad2,
    title: "Sedang Rental",
    value: "8",
    subValue: "40% perangkat digunakan",
    bgColor: "#DCFCE7", // bg-green-100
    iconColor: "#15803D", // text-green-700
    subTextColor: "text-green-600",
  },
  {
    icon: Monitor,
    title: "Perangkat Tersedia",
    value: "10",
    subValue: "Siap digunakan",
    bgColor: "#DBEAFE", // bg-blue-100
    iconColor: "#1D4ED8", // text-blue-700
    subTextColor: "text-gray-500",
  },
  {
    icon: TrendingUp,
    title: "Pendapatan Hari Ini",
    value: "Rp480.000",
    subValue: "24 transaksi",
    bgColor: "#F6F4EB",
    iconColor: "#4682A9",
    subTextColor: "text-gray-500",
  },
  {
    icon: DollarSign,
    title: "Pending Payment",
    value: "24",
    subValue: "Belum dibayar",
    bgColor: "#FEE2E2", // bg-red-100
    iconColor: "#B91C1C", // text-red-700
    subTextColor: "text-red-500",
  },
]);
</script>

<template>
  <div class="min-h-screen bg-[#F6F4EB]">
    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main -->
    <main class="lg:ml-64">
      <!-- Header -->
      <AdminHeader />

      <!-- Content -->
      <div class="p-6 sm:p-8">
        <!-- Welcome -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-800">
            Selamat datang, Admin 👋
          </h2>
          <p class="mt-1 text-sm text-gray-500">
            Berikut ringkasan kondisi rental hari ini.
          </p>
        </div>

        <!-- Stats -->
        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-5">
          <!-- Render dinamis menggunakan v-for -->
          <div
            v-for="(item, index) in stats"
            :key="index"
            class="rounded-2xl bg-white p-6 shadow-sm"
          >
            <div class="flex items-center justify-between">
              <p class="text-sm text-gray-500">
                {{ item.title }}
              </p>

              <span
                v-if="item.icon"
                class="rounded-lg px-3 py-2 text-lg"
                :style="{ backgroundColor: item.bgColor }"
              >
                <component
                  :is="item.icon"
                  :size="18"
                  :stroke-width="1.8"
                  :style="{ color: item.iconColor }"
                />
              </span>
            </div>

            <p class="mt-5 text-2xl font-bold text-gray-800">
              {{ item.value }}
            </p>

            <p class="mt-2 text-xs" :class="item.subTextColor">
              {{ item.subValue }}
            </p>
          </div>
        </div>

        <!-- Bottom Section -->
        <div class="mt-8 grid gap-6 xl:grid-cols-3">
          <!-- Device Status -->
          <div class="rounded-2xl bg-white p-6 shadow-sm xl:col-span-2">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="font-semibold text-gray-800">Status Perangkat</h3>
                <p class="mt-1 text-xs text-gray-500">Kondisi perangkat saat ini</p>
              </div>
              <button class="text-sm font-medium text-[#4682A9]">Lihat semua</button>
            </div>

            <div class="mt-6 overflow-x-auto">
              <table class="w-full min-w-[600px] text-left">
                <thead>
                  <tr class="border-b border-gray-100 text-xs text-gray-400">
                    <th class="pb-4 font-medium">Perangkat</th>
                    <th class="pb-4 font-medium">Jenis</th>
                    <th class="pb-4 font-medium">Status</th>
                    <th class="pb-4 font-medium">Penggunaan</th>
                  </tr>
                </thead>
                <tbody class="text-sm">
                  <tr class="border-b border-gray-100">
                    <td class="py-4 font-medium text-gray-700">PC Gaming 01</td>
                    <td class="py-4 text-gray-500">PC</td>
                    <td class="py-4">
                      <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">Digunakan</span>
                    </td>
                    <td class="py-4 text-gray-500">01:42:36</td>
                  </tr>
                  <tr class="border-b border-gray-100">
                    <td class="py-4 font-medium text-gray-700">PC Gaming 02</td>
                    <td class="py-4 text-gray-500">PC</td>
                    <td class="py-4">
                      <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">Tersedia</span>
                    </td>
                    <td class="py-4 text-gray-500">-</td>
                  </tr>
                  <tr>
                    <td class="py-4 font-medium text-gray-700">PS 01</td>
                    <td class="py-4 text-gray-500">PlayStation</td>
                    <td class="py-4">
                      <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">Maintenance</span>
                    </td>
                    <td class="py-4 text-gray-500">-</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Recent Rental -->
          <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div>
              <h3 class="font-semibold text-gray-800">Rental Terbaru</h3>
              <p class="mt-1 text-xs text-gray-500">Aktivitas rental terakhir</p>
            </div>

            <div class="mt-6 space-y-5">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-700">PC Gaming 01</p>
                  <p class="mt-1 text-xs text-gray-400">Kode: PC-A12-458</p>
                </div>
                <span class="text-xs text-green-600">Aktif</span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-700">PS 02</p>
                  <p class="mt-1 text-xs text-gray-400">Kode: PS-B04-231</p>
                </div>
                <span class="text-xs text-gray-400">Selesai</span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-700">PC Gaming 05</p>
                  <p class="mt-1 text-xs text-gray-400">Kode: PC-C18-729</p>
                </div>
                <span class="text-xs text-green-600">Aktif</span>
              </div>
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-700">PS 01</p>
                  <p class="mt-1 text-xs text-gray-400">Kode: PS-A02-112</p>
                </div>
                <span class="text-xs text-gray-400">Selesai</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>