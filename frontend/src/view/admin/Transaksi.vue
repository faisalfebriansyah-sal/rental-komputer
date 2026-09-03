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
     <AdminHeader title="Transaksi" />

      <!-- Content -->
      <div class="p-6 sm:p-8">

        <!-- Title -->
        <div>
          <h2 class="text-xl font-semibold text-gray-800">
            Riwayat Transaksi
          </h2>

          <p class="mt-1 text-sm text-gray-500">
            Pantau seluruh pembayaran rental.
          </p>
        </div>

        <!-- Summary -->
        <div class="mt-8 grid gap-5 sm:grid-cols-3">

          <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm text-gray-500">
              Transaksi Hari Ini
            </p>

            <p class="mt-3 text-3xl font-bold text-gray-800">
              24
            </p>
          </div>

          <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm text-gray-500">
              Pendapatan Hari Ini
            </p>

            <p class="mt-3 text-2xl font-bold text-gray-800">
              Rp480.000
            </p>
          </div>

          <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm text-gray-500">
              Pendapatan Bulan Ini
            </p>

            <p class="mt-3 text-2xl font-bold text-gray-800">
              Rp8.240.000
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
              Lunas
            </button>

            <button
              class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 transition hover:bg-gray-100"
            >
              Pending
            </button>

          </div>

          <input
            type="text"
            placeholder="Cari kode transaksi..."
            class="w-full rounded-xl border border-gray-200 bg-[#F6F4EB]/40 px-4 py-2.5 text-sm outline-none transition placeholder:text-gray-400 focus:border-[#4682A9] focus:ring-4 focus:ring-[#91C8E4]/20 sm:w-64"
          />

        </div>

        <!-- Table -->
        <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm">

          <div class="overflow-x-auto">

            <table class="w-full min-w-[900px] text-left">

              <thead>
                <tr class="border-b border-gray-100 text-xs text-gray-400">

                  <th class="px-6 py-4 font-medium">
                    ID Transaksi
                  </th>

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
                    Metode
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

                <!-- Transaction 1 -->
                <tr class="border-b border-gray-100">

                  <td class="px-6 py-5 font-medium text-gray-700">
                    TRX-00024
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    PC-A12-458
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    PC Gaming 01
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    2 Jam
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    Tunai
                  </td>

                  <td class="px-6 py-5 font-medium text-gray-700">
                    Rp20.000
                  </td>

                  <td class="px-6 py-5">
                    <span
                      class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"
                    >
                      Lunas
                    </span>
                  </td>

                </tr>

                <!-- Transaction 2 -->
                <tr class="border-b border-gray-100">

                  <td class="px-6 py-5 font-medium text-gray-700">
                    TRX-00023
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    PS-B04-231
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    PlayStation 02
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    3 Jam
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    QRIS
                  </td>

                  <td class="px-6 py-5 font-medium text-gray-700">
                    Rp45.000
                  </td>

                  <td class="px-6 py-5">
                    <span
                      class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"
                    >
                      Lunas
                    </span>
                  </td>

                </tr>

                <!-- Transaction 3 -->
                <tr class="border-b border-gray-100">

                  <td class="px-6 py-5 font-medium text-gray-700">
                    TRX-00022
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    PC-C18-729
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    PC Gaming 05
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    4 Jam
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    QRIS
                  </td>

                  <td class="px-6 py-5 font-medium text-gray-700">
                    Rp40.000
                  </td>

                  <td class="px-6 py-5">
                    <span
                      class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"
                    >
                      Lunas
                    </span>
                  </td>

                </tr>

                <!-- Transaction 4 -->
                <tr>

                  <td class="px-6 py-5 font-medium text-gray-700">
                    TRX-00021
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    PS-A02-112
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    PlayStation 01
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    2 Jam
                  </td>

                  <td class="px-6 py-5 text-gray-600">
                    Tunai
                  </td>

                  <td class="px-6 py-5 font-medium text-gray-700">
                    Rp30.000
                  </td>

                  <td class="px-6 py-5">
                    <span
                      class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600"
                    >
                      Lunas
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