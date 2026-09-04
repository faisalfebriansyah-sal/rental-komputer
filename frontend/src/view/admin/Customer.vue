<script setup>
import AdminSidebar from "../../components/admin/AdminSidebar.vue";
import AdminHeader from "../../components/admin/AdminHeader.vue";

import {
  Users,
  Plus,
  Search,
  MoreVertical,
  UserRound,
} from "lucide-vue-next";

const customers = [
  {
    id: 1,
    name: "Budi",
    phone: "081234567890",
    totalRental: 5,
  },
  {
    id: 2,
    name: "Andi",
    phone: "085712345678",
    totalRental: 3,
  },
  {
    id: 3,
    name: "Rizky",
    phone: "089612345678",
    totalRental: 8,
  },
  {
    id: 4,
    name: "Fajar",
    phone: "082112345678",
    totalRental: 2,
  },
];
</script>

<template>
  <div class="min-h-screen bg-[#F6F4EB]">
    <AdminSidebar />

    <div class="ml-64">
      <AdminHeader />

      <main class="p-8">

        <!-- Header -->
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-slate-800">
              Pelanggan
            </h1>

            <p class="mt-1 text-sm text-slate-500">
              Kelola data pelanggan rental
            </p>
          </div>

          <button
            class="flex items-center gap-2 rounded-xl bg-[#4682A9] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#3d7599]"
          >
            <Plus :size="18" />
            Tambah Pelanggan
          </button>
        </div>

        <!-- Statistics -->
        <div class="mt-8 grid grid-cols-3 gap-5">

          <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
              <div
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#91C8E4]/30 text-[#4682A9]"
              >
                <Users :size="21" />
              </div>

              <div>
                <p class="text-sm text-slate-500">
                  Total Pelanggan
                </p>

                <p class="mt-1 text-2xl font-bold text-slate-800">
                  128
                </p>
              </div>
            </div>
          </div>

          <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
              <div
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-600"
              >
                <UserRound :size="21" />
              </div>

              <div>
                <p class="text-sm text-slate-500">
                  Pelanggan Hari Ini
                </p>

                <p class="mt-1 text-2xl font-bold text-slate-800">
                  24
                </p>
              </div>
            </div>
          </div>

          <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
              <div
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-500"
              >
                <Users :size="21" />
              </div>

              <div>
                <p class="text-sm text-slate-500">
                  Sedang Rental
                </p>

                <p class="mt-1 text-2xl font-bold text-slate-800">
                  8
                </p>
              </div>
            </div>
          </div>

        </div>

        <!-- Table -->
        <div class="mt-8 rounded-2xl bg-white shadow-sm">

          <!-- Search -->
          <div
            class="flex items-center justify-between border-b border-slate-100 p-5"
          >
            <div>
              <h2 class="font-semibold text-slate-800">
                Daftar Pelanggan
              </h2>

              <p class="mt-1 text-xs text-slate-400">
                Data pelanggan yang terdaftar
              </p>
            </div>

            <div class="relative w-72">
              <Search
                :size="18"
                class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"
              />

              <input
                type="text"
                placeholder="Cari nama atau nomor HP..."
                class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-4 text-sm outline-none transition focus:border-[#4682A9]"
              />
            </div>
          </div>

          <!-- Table -->
          <div class="overflow-hidden">
            <table class="w-full text-left">

              <thead class="bg-slate-50">
                <tr>
                  <th class="px-6 py-4 text-xs font-semibold uppercase text-slate-500">
                    Pelanggan
                  </th>

                  <th class="px-6 py-4 text-xs font-semibold uppercase text-slate-500">
                    No. HP
                  </th>

                  <th class="px-6 py-4 text-xs font-semibold uppercase text-slate-500">
                    Total Rental
                  </th>

                  <th class="px-6 py-4 text-right text-xs font-semibold uppercase text-slate-500">
                    Aksi
                  </th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="customer in customers"
                  :key="customer.id"
                  class="border-t border-slate-100 transition hover:bg-slate-50"
                >
                  <!-- Name -->
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-[#91C8E4]/30 text-sm font-bold text-[#4682A9]"
                      >
                        {{ customer.name.charAt(0) }}
                      </div>

                      <div>
                        <p class="text-sm font-semibold text-slate-800">
                          {{ customer.name }}
                        </p>

                        <p class="text-xs text-slate-400">
                          ID #{{ customer.id }}
                        </p>
                      </div>
                    </div>
                  </td>

                  <!-- Phone -->
                  <td class="px-6 py-4 text-sm text-slate-600">
                    {{ customer.phone }}
                  </td>

                  <!-- Rental -->
                  <td class="px-6 py-4">
                    <span
                      class="rounded-lg bg-[#91C8E4]/20 px-3 py-1.5 text-xs font-semibold text-[#4682A9]"
                    >
                      {{ customer.totalRental }}x Rental
                    </span>
                  </td>

                  <!-- Action -->
                  <td class="px-6 py-4 text-right">
                    <button
                      class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                    >
                      <MoreVertical :size="18" />
                    </button>
                  </td>
                </tr>
              </tbody>

            </table>
          </div>

          <!-- Footer -->
          <div
            class="flex items-center justify-between border-t border-slate-100 px-6 py-4"
          >
            <p class="text-xs text-slate-400">
              Menampilkan 4 dari 128 pelanggan
            </p>

            <div class="flex gap-2">
              <button
                class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs text-slate-500"
              >
                Sebelumnya
              </button>

              <button
                class="rounded-lg bg-[#4682A9] px-3 py-1.5 text-xs text-white"
              >
                1
              </button>

              <button
                class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs text-slate-500"
              >
                Berikutnya
              </button>
            </div>
          </div>

        </div>

      </main>
    </div>
  </div>
</template>