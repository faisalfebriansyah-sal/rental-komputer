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
     <AdminHeader title="Perangkat" />

      <!-- Content -->
      <div class="p-6 sm:p-8">

        <!-- Title -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

          <div>
            <h2 class="text-xl font-semibold text-gray-800">
              Daftar Perangkat
            </h2>

            <p class="mt-1 text-sm text-gray-500">
              Kelola seluruh PC dan PlayStation rental.
            </p>
          </div>

          <button
            type="button"
            class="rounded-xl bg-[#4682A9] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-[#4682A9]/20 transition hover:bg-[#749BC2]"
          >
            + Tambah Perangkat
          </button>

        </div>

        <!-- Filter -->
        <div class="mt-8 flex flex-wrap gap-3">

          <button
            class="rounded-xl bg-[#4682A9] px-5 py-2.5 text-sm font-medium text-white"
          >
            Semua
          </button>

          <button
            class="rounded-xl bg-white px-5 py-2.5 text-sm font-medium text-gray-600 shadow-sm transition hover:bg-gray-50"
          >
            PC
          </button>

          <button
            class="rounded-xl bg-white px-5 py-2.5 text-sm font-medium text-gray-600 shadow-sm transition hover:bg-gray-50"
          >
            PlayStation
          </button>

        </div>

        <!-- Device Cards -->
        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">

          <!-- PC 01 -->
          <div class="rounded-2xl bg-white p-6 shadow-sm">

            <div class="flex items-start justify-between">

              <div>
                <div
                  class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#91C8E4]/25 text-2xl"
                >
                  🖥️
                </div>

                <h3 class="mt-4 font-semibold text-gray-800">
                  PC Gaming 01
                </h3>

                <p class="mt-1 text-xs text-gray-400">
                  PC-A01
                </p>
              </div>

              <span
                class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"
              >
                Tersedia
              </span>

            </div>

            <div class="mt-6 border-t border-gray-100 pt-5">

              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Harga</span>
                <span class="font-medium text-gray-700">
                  Rp10.000 / jam
                </span>
              </div>

            </div>

          </div>

          <!-- PC 02 -->
          <div class="rounded-2xl bg-white p-6 shadow-sm">

            <div class="flex items-start justify-between">

              <div>
                <div
                  class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#91C8E4]/25 text-2xl"
                >
                  🖥️
                </div>

                <h3 class="mt-4 font-semibold text-gray-800">
                  PC Gaming 02
                </h3>

                <p class="mt-1 text-xs text-gray-400">
                  PC-A02
                </p>
              </div>

              <span
                class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700"
              >
                Digunakan
              </span>

            </div>

            <div class="mt-6 border-t border-gray-100 pt-5">

              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Harga</span>
                <span class="font-medium text-gray-700">
                  Rp10.000 / jam
                </span>
              </div>

            </div>

          </div>

          <!-- PC 03 -->
          <div class="rounded-2xl bg-white p-6 shadow-sm">

            <div class="flex items-start justify-between">

              <div>
                <div
                  class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#91C8E4]/25 text-2xl"
                >
                  🖥️
                </div>

                <h3 class="mt-4 font-semibold text-gray-800">
                  PC Gaming 03
                </h3>

                <p class="mt-1 text-xs text-gray-400">
                  PC-A03
                </p>
              </div>

              <span
                class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700"
              >
                Maintenance
              </span>

            </div>

            <div class="mt-6 border-t border-gray-100 pt-5">

              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Harga</span>
                <span class="font-medium text-gray-700">
                  Rp10.000 / jam
                </span>
              </div>

            </div>

          </div>

          <!-- PS 01 -->
          <div class="rounded-2xl bg-white p-6 shadow-sm">

            <div class="flex items-start justify-between">

              <div>
                <div
                  class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#91C8E4]/25 text-2xl"
                >
                  🎮
                </div>

                <h3 class="mt-4 font-semibold text-gray-800">
                  PlayStation 01
                </h3>

                <p class="mt-1 text-xs text-gray-400">
                  PS-A01
                </p>
              </div>

              <span
                class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"
              >
                Tersedia
              </span>

            </div>

            <div class="mt-6 border-t border-gray-100 pt-5">

              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Harga</span>
                <span class="font-medium text-gray-700">
                  Rp15.000 / jam
                </span>
              </div>

            </div>

          </div>

          <!-- PS 02 -->
          <div class="rounded-2xl bg-white p-6 shadow-sm">

            <div class="flex items-start justify-between">

              <div>
                <div
                  class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#91C8E4]/25 text-2xl"
                >
                  🎮
                </div>

                <h3 class="mt-4 font-semibold text-gray-800">
                  PlayStation 02
                </h3>

                <p class="mt-1 text-xs text-gray-400">
                  PS-A02
                </p>
              </div>

              <span
                class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700"
              >
                Digunakan
              </span>

            </div>

            <div class="mt-6 border-t border-gray-100 pt-5">

              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Harga</span>
                <span class="font-medium text-gray-700">
                  Rp15.000 / jam
                </span>
              </div>

            </div>

          </div>

        </div>

      </div>
    </main>

  </div>
</template>