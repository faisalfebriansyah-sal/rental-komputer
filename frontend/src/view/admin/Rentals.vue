<script setup>
import AdminSidebar from "../../components/admin/AdminSidebar.vue";
import AdminHeader from "../../components/admin/AdminHeader.vue";
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

        <!-- Title -->
        <div>
          <h2 class="text-xl font-semibold text-gray-800">
            Daftar Rental
          </h2>

          <p class="mt-1 text-sm text-gray-500">
            Pantau seluruh sesi rental pelanggan.
          </p>
        </div>

        <!-- Summary -->
        <div class="mt-8 grid gap-5 sm:grid-cols-3">

          <div class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">
              Rental Aktif
            </p>

            <p class="mt-3 text-3xl font-bold text-gray-800">
              8
            </p>
          </div>

          <div class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">
              Selesai Hari Ini
            </p>

            <p class="mt-3 text-3xl font-bold text-gray-800">
              16
            </p>
          </div>

          <div class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">
              Total Pendapatan
            </p>

            <p class="mt-3 text-2xl font-bold text-gray-800">
              Rp480.000
            </p>
          </div>

        </div>

        <!-- Filter -->
        <div
          class="mt-8 flex flex-col gap-4 rounded-2xl bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between"
        >

          <div class="flex flex-wrap gap-2">

            <button
              class="rounded-lg bg-[#4682A9] px-4 py-2 text-sm font-medium text-white"
            >
              Semua
            </button>

            <button
              class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 transition hover:bg-gray-100"
            >
              Aktif
            </button>

            <button
              class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 transition hover:bg-gray-100"
            >
              Selesai
            </button>

          </div>

          <input
            type="text"
            placeholder="Cari kode rental..."
            class="w-full rounded-xl border border-gray-200 bg-[#F6F4EB]/40 px-4 py-2.5 text-sm outline-none transition placeholder:text-gray-400 focus:border-[#4682A9] focus:ring-4 focus:ring-[#91C8E4]/20 sm:w-64"
          />

        </div>

        <!-- Rental Table -->
        <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm">

          <div class="overflow-x-auto">

            <table class="w-full min-w-[850px] text-left">

              <thead>
                <tr class="border-b border-gray-100 text-xs text-gray-400">

                  <th class="px-6 py-4 font-medium">
                    Kode Rental
                  </th>

                  <th class="px-6 py-4 font-medium">
                    Perangkat
                  </th>

                  <th class="px-6 py-4 font-medium">
                    Durasi
                  </th>

                  <th class="px-6 py-4 font-medium">
                    Mulai
                  </th>

                  <th class="px-6 py-4 font-medium">
                    Selesai
                  </th>

                  <th class="px-6 py-4 font-medium">
                    Total
                  </th>

                  <th class="px-6 py-4 font-medium">
                    Status
                  </th>

                </tr>
              </thead>

              <tbody class="text-sm">

                <!-- Rental 1 -->
                <tr class="border-b border-gray-100">

                  <td class="px-6 py-5">
                    <p class="font-medium text-gray-700">
                      PC-A12-458
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                      Hari ini
                    </p>
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    PC Gaming 01
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    2 Jam
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    14:00
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    16:00
                  </td>

                  <td class="px-6 py-5 font-medium text-gray-700">
                    Rp20.000
                  </td>

                  <td class="px-6 py-5">
                    <span
                      class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"
                    >
                      Aktif
                    </span>
                  </td>

                </tr>

                <!-- Rental 2 -->
                <tr class="border-b border-gray-100">

                  <td class="px-6 py-5">
                    <p class="font-medium text-gray-700">
                      PS-B04-231
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                      Hari ini
                    </p>
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    PlayStation 02
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    3 Jam
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    11:00
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    14:00
                  </td>

                  <td class="px-6 py-5 font-medium text-gray-700">
                    Rp45.000
                  </td>

                  <td class="px-6 py-5">
                    <span
                      class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600"
                    >
                      Selesai
                    </span>
                  </td>

                </tr>

                <!-- Rental 3 -->
                <tr class="border-b border-gray-100">

                  <td class="px-6 py-5">
                    <p class="font-medium text-gray-700">
                      PC-C18-729
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                      Hari ini
                    </p>
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    PC Gaming 05
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    4 Jam
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    10:00
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    14:00
                  </td>

                  <td class="px-6 py-5 font-medium text-gray-700">
                    Rp40.000
                  </td>

                  <td class="px-6 py-5">
                    <span
                      class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"
                    >
                      Aktif
                    </span>
                  </td>

                </tr>

                <!-- Rental 4 -->
                <tr>

                  <td class="px-6 py-5">
                    <p class="font-medium text-gray-700">
                      PS-A02-112
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                      Hari ini
                    </p>
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    PlayStation 01
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    2 Jam
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    08:00
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    10:00
                  </td>

                  <td class="px-6 py-5 font-medium text-gray-700">
                    Rp30.000
                  </td>

                  <td class="px-6 py-5">
                    <span
                      class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600"
                    >
                      Selesai
                    </span>
                  </td>

                </tr>

              </tbody>

            </table>

          </div>

        </div>

      </div>
    </main>

  </div>
</template>