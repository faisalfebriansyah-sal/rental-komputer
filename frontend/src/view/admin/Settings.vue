<script setup>
import AdminSidebar from "../../components/admin/AdminSidebar.vue";
import AdminHeader from "../../components/admin/AdminHeader.vue";
</script>



<template>
  <div class="min-h-screen bg-[#F6F4EB]">

    <!-- Sidebar -->
   <AdminSidebar/>
    <!-- Main -->
    <main class="lg:ml-64">

      <!-- Header -->
     <AdminHeader title="Pengaturan" />

      <!-- Content -->
      <div class="p-6 sm:p-8">

        <div>
          <h2 class="text-xl font-semibold text-gray-800">
            Pengaturan Sistem
          </h2>

          <p class="mt-1 text-sm text-gray-500">
            Kelola informasi rental dan konfigurasi dasar sistem.
          </p>
        </div>

        <!-- Profile -->
        <section class="mt-8 rounded-2xl bg-white p-6 shadow-sm">

          <div>
            <h3 class="font-semibold text-gray-800">
              Profil Admin
            </h3>

            <p class="mt-1 text-xs text-gray-500">
              Informasi akun administrator.
            </p>
          </div>

          <div class="mt-6 grid gap-5 md:grid-cols-2">

            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">
                Nama
              </label>

              <input
                type="text"
                value="Admin"
                class="w-full rounded-xl border border-gray-200 bg-[#F6F4EB]/40 px-4 py-3 text-sm outline-none focus:border-[#4682A9] focus:ring-4 focus:ring-[#91C8E4]/20"
              />
            </div>

            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">
                Username
              </label>

              <input
                type="text"
                value="admin"
                class="w-full rounded-xl border border-gray-200 bg-[#F6F4EB]/40 px-4 py-3 text-sm outline-none focus:border-[#4682A9] focus:ring-4 focus:ring-[#91C8E4]/20"
              />
            </div>

          </div>

          <button
            type="button"
            class="mt-6 rounded-xl bg-[#4682A9] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#749BC2]"
          >
            Simpan Perubahan
          </button>

        </section>

        <!-- Rental Information -->
        <section class="mt-6 rounded-2xl bg-white p-6 shadow-sm">

          <div>
            <h3 class="font-semibold text-gray-800">
              Informasi Rental
            </h3>

            <p class="mt-1 text-xs text-gray-500">
              Informasi yang digunakan oleh sistem rental.
            </p>
          </div>

          <div class="mt-6 space-y-5">

            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">
                Nama Rental
              </label>

              <input
                type="text"
                value="Rental PC & PS"
                class="w-full rounded-xl border border-gray-200 bg-[#F6F4EB]/40 px-4 py-3 text-sm outline-none focus:border-[#4682A9] focus:ring-4 focus:ring-[#91C8E4]/20"
              />
            </div>

            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">
                Alamat
              </label>

              <textarea
                rows="3"
                placeholder="Masukkan alamat rental"
                class="w-full resize-none rounded-xl border border-gray-200 bg-[#F6F4EB]/40 px-4 py-3 text-sm outline-none placeholder:text-gray-400 focus:border-[#4682A9] focus:ring-4 focus:ring-[#91C8E4]/20"
              ></textarea>
            </div>

          </div>

          <button
            type="button"
            class="mt-6 rounded-xl bg-[#4682A9] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#749BC2]"
          >
            Simpan Informasi
          </button>

        </section>

        <!-- Pricing -->
        <section class="mt-6 rounded-2xl bg-white p-6 shadow-sm">

          <div>
            <h3 class="font-semibold text-gray-800">
              Harga Rental
            </h3>

            <p class="mt-1 text-xs text-gray-500">
              Pengaturan harga berdasarkan jenis perangkat.
            </p>
          </div>

          <div class="mt-6 grid gap-5 md:grid-cols-2">

            <!-- PC -->
            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">
                Harga PC / Jam
              </label>

              <div class="relative">

                <span
                  class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400"
                >
                  Rp
                </span>

                <input
                  type="number"
                  value="10000"
                  class="w-full rounded-xl border border-gray-200 bg-[#F6F4EB]/40 py-3 pl-11 pr-4 text-sm outline-none focus:border-[#4682A9] focus:ring-4 focus:ring-[#91C8E4]/20"
                />

              </div>
            </div>

            <!-- PS -->
            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">
                Harga PlayStation / Jam
              </label>

              <div class="relative">

                <span
                  class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400"
                >
                  Rp
                </span>

                <input
                  type="number"
                  value="15000"
                  class="w-full rounded-xl border border-gray-200 bg-[#F6F4EB]/40 py-3 pl-11 pr-4 text-sm outline-none focus:border-[#4682A9] focus:ring-4 focus:ring-[#91C8E4]/20"
                />

              </div>
            </div>

          </div>

          <button
            type="button"
            class="mt-6 rounded-xl bg-[#4682A9] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#749BC2]"
          >
            Simpan Harga
          </button>

        </section>

      </div>
    </main>

  </div>
</template>