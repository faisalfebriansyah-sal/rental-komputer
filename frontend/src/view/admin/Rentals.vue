<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { echo } from "@/lib/echo";
import AdminSidebar from "../../components/admin/AdminSidebar.vue";
import AdminHeader from "../../components/admin/AdminHeader.vue";

const rentals = ref([]);
const loading = ref(true);
const errorMessage = ref("");

const showModal = ref(false);

const pelanggan = ref([]);
const perangkat = ref([]);

const form = ref({
     pelanggan_id: "",
     perangkat_id: "",
     durasi: 1,
});

const formError = ref("");
const saving = ref(false);

const activeFilter = ref("semua");
const searchQuery = ref("");

const getRentals = async (isSilent = false) => {
     if (!isSilent) {
          loading.value = true;
     }
     errorMessage.value = "";

     try {
          const token = localStorage.getItem("token");

          const response = await fetch(
               "http://127.0.0.1:8000/api/sesi_rental",
               {
                    method: "GET",
                    headers: {
                         Accept: "application/json",
                         Authorization: `Bearer ${token}`,
                    },
               }
          );

          const result = await response.json();

          if (!response.ok) {
               if (!isSilent) {
                    errorMessage.value =
                         result.message || "Gagal mengambil data rental.";
               }
               return;
          }

          rentals.value = result.data || [];
     } catch (error) {
          console.error(error);
          if (!isSilent) {
               errorMessage.value = "Tidak dapat terhubung ke server.";
          }
     } finally {
          if (!isSilent) {
               loading.value = false;
          }
     }
};

const getPelanggan = async () => {
     try {
          const token = localStorage.getItem("token");

          const response = await fetch(
               "http://127.0.0.1:8000/api/pelanggan",
               {
                    headers: {
                         Accept: "application/json",
                         Authorization: `Bearer ${token}`,
                    },
               }
          );

          const result = await response.json();

          if (!response.ok) {
               console.error(result.message);
               return;
          }

          pelanggan.value = result.data || [];
     } catch (error) {
          console.error(error);
     }
};

const getPerangkatTersedia = async () => {
     try {
          const token = localStorage.getItem("token");

          const response = await fetch(
               "http://127.0.0.1:8000/api/perangkat",
               {
                    headers: {
                         Accept: "application/json",
                         Authorization: `Bearer ${token}`,
                    },
               }
          );

          const result = await response.json();

          if (!response.ok) {
               console.error(result.message);
               return;
          }

          perangkat.value = (result.data || []).filter(
               (item) => item.status === "tersedia"
          );
     } catch (error) {
          console.error(error);
     }
};



const filteredRentals = computed(() => {
     return rentals.value.filter((rental) => {
          const statusMatch =
               activeFilter.value === "semua" ||
               rental.status === activeFilter.value;

          const searchMatch =
               rental.kode_sesi
                    ?.toLowerCase()
                    .includes(searchQuery.value.toLowerCase());

          return statusMatch && searchMatch;
     });
});

const rentalAktif = computed(() => {
     return rentals.value.filter(
          (rental) => rental.status === "aktif"
     ).length;
});

const selesaiHariIni = computed(() => {
     const today = new Date().toISOString().split("T")[0];

     return rentals.value.filter((rental) => {
          if (rental.status !== "selesai") {
               return false;
          }

          if (!rental.waktu_selesai) {
               return false;
          }

          return rental.waktu_selesai.startsWith(today);
     }).length;
});

const totalPendapatan = computed(() => {
     return rentals.value.reduce((total, rental) => {
          return total + Number(rental.harga || 0);
     }, 0);
});

const formatRupiah = (value) => {
     return Number(value || 0).toLocaleString("id-ID");
};

const formatTime = (datetime) => {
     if (!datetime) {
          return "-";
     }

     return new Date(datetime).toLocaleTimeString("id-ID", {
          hour: "2-digit",
          minute: "2-digit",
     });
};

const formatDate = (datetime) => {
     if (!datetime) {
          return "-";
     }

     return new Date(datetime).toLocaleDateString("id-ID", {
          day: "2-digit",
          month: "2-digit",
          year: "numeric",
     });
};

const setFilter = (filter) => {
     activeFilter.value = filter;
};

const openModal = async () => {
     form.value = {
          pelanggan_id: "",
          perangkat_id: "",
          durasi: 1,
     };

     formError.value = "";

     await Promise.all([
          getPelanggan(),
          getPerangkatTersedia(),
     ]);

     showModal.value = true;
};

const createRental = async () => {
     formError.value = "";

     if (
          !form.value.pelanggan_id ||
          !form.value.perangkat_id ||
          !form.value.durasi
     ) {
          formError.value = "Semua field wajib diisi.";
          return;
     }

     saving.value = true;

     try {
          const token = localStorage.getItem("token");

          const response = await fetch(
               "http://127.0.0.1:8000/api/sesi_rental",
               {
                    method: "POST",
                    headers: {
                         Accept: "application/json",
                         "Content-Type": "application/json",
                         Authorization: `Bearer ${token}`,
                    },
                    body: JSON.stringify({
                         pelanggan_id: form.value.pelanggan_id,
                         perangkat_id: form.value.perangkat_id,
                         durasi: form.value.durasi,
                    }),
               }
          );

          const result = await response.json();

          if (!response.ok) {
               formError.value =
                    result.message || "Gagal membuat rental.";
               return;
          }

          showModal.value = false;

          await getRentals();

     } catch (error) {
          console.error(error);
          formError.value = "Tidak dapat terhubung ke server.";
     } finally {
          saving.value = false;
     }
};

onMounted(() => {
     getRentals();

     // Dengarkan broadcast real-time dari Laravel Reverb
     echo.channel("rentals").listen(".rental.updated", () => {
          getRentals(true);
     });
});

onUnmounted(() => {
     echo.leaveChannel("rentals");
});
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
                    <div class="flex items-center justify-between">
                         <div>
                              <h1 class="text-2xl font-bold text-gray-800">
                                   Daftar Rental
                              </h1>
                              <p class="mt-1 text-sm text-gray-500">
                                   Kelola sesi rental PC.
                              </p>
                         </div>

                         <button type="button" @click="openModal"
                              class="rounded-xl bg-[#4682A9] px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-[#3b7194]">
                              + Tambah Rental
                         </button>
                    </div>

                    <!-- Summary -->
                    <div class="mt-8 grid gap-5 sm:grid-cols-3">

                         <div class="rounded-2xl bg-white p-5 shadow-sm">
                              <p class="text-sm text-gray-500">
                                   Rental Aktif
                              </p>

                              <p class="mt-3 text-3xl font-bold text-gray-800">
                                   {{ rentalAktif }}
                              </p>
                         </div>

                         <div class="rounded-2xl bg-white p-5 shadow-sm">
                              <p class="text-sm text-gray-500">
                                   Selesai Hari Ini
                              </p>

                              <p class="mt-3 text-3xl font-bold text-gray-800">
                                   {{ selesaiHariIni }}
                              </p>
                         </div>

                         <div class="rounded-2xl bg-white p-5 shadow-sm">
                              <p class="text-sm text-gray-500">
                                   Total Pendapatan
                              </p>

                              <p class="mt-3 text-2xl font-bold text-gray-800">
                                   Rp{{ formatRupiah(totalPendapatan) }}
                              </p>
                         </div>

                    </div>

                  <!-- Filter -->
                    <div
                         class="mt-8 flex flex-col gap-4 rounded-2xl bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">

                         <div class="flex flex-wrap gap-2">

                              <button @click="setFilter('semua')" :class="activeFilter === 'semua'
                                        ? 'bg-[#4682A9] text-white'
                                        : 'text-gray-500 hover:bg-gray-100'
                                   " class="rounded-lg px-4 py-2 text-sm font-medium transition">
                                   Semua
                              </button>

                              <button @click="setFilter('aktif')" :class="activeFilter === 'aktif'
                                        ? 'bg-[#4682A9] text-white'
                                        : 'text-gray-500 hover:bg-gray-100'
                                   " class="rounded-lg px-4 py-2 text-sm font-medium transition">
                                   Aktif
                              </button>

                              <button @click="setFilter('selesai')" :class="activeFilter === 'selesai'
                                        ? 'bg-[#4682A9] text-white'
                                        : 'text-gray-500 hover:bg-gray-100'
                                   " class="rounded-lg px-4 py-2 text-sm font-medium transition">
                                   Selesai
                              </button>

                         </div>

                         <input v-model="searchQuery" type="text" placeholder="Cari kode rental..."
                              class="w-full rounded-xl border border-gray-200 bg-[#F6F4EB]/40 px-4 py-2.5 text-sm outline-none transition placeholder:text-gray-400 focus:border-[#4682A9] focus:ring-4 focus:ring-[#91C8E4]/20 sm:w-64" />

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
                                                  Username
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

                                        <!-- Loading -->
                                        <tr v-if="loading">
                                             <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                                  Memuat data rental...
                                             </td>
                                        </tr>

                                        <!-- Error -->
                                        <tr v-else-if="errorMessage">
                                             <td colspan="8" class="px-6 py-10 text-center text-red-500">
                                                  {{ errorMessage }}
                                             </td>
                                        </tr>

                                        <!-- Tidak ada data -->
                                        <tr v-else-if="filteredRentals.length === 0">
                                             <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                                  Tidak ada data rental.
                                             </td>
                                        </tr>

                                        <!-- Data rental -->
                                        <tr v-else v-for="(rental, index) in filteredRentals" :key="rental.id"
                                             class="border-b border-gray-100 last:border-0">

                                             <!-- Kode -->
                                             <td class="px-6 py-5">
                                                  <p class="font-medium text-gray-700">
                                                       {{ rental.kode_sesi }}
                                                  </p>

                                                  <p class="mt-1 text-xs text-gray-400">
                                                       {{ formatDate(rental.created_at) }}
                                                  </p>
                                             </td>

                                             <!-- Username -->
                                             <td class="px-6 py-5 text-gray-600">
                                                  {{ rental.pelanggan?.name || "-" }}
                                             </td>

                                             <!-- Perangkat -->
                                             <td class="px-6 py-5 text-gray-600">
                                                  {{ rental.perangkat?.name || "-" }}
                                                  
                                                  {{ rental.perangkat?.jenis_perangkat?.name || "-" }}
                                             </td>

                                             <!-- Durasi -->
                                             <td class="px-6 py-5 text-gray-600">
                                                  {{ rental.durasi }} Jam
                                             </td>

                                             <!-- Mulai -->
                                             <td class="px-6 py-5 text-gray-600">
                                                  {{ formatTime(rental.waktu_mulai) }}
                                             </td>

                                             <!-- Selesai -->
                                             <td class="px-6 py-5 text-gray-600">
                                                  {{ formatTime(rental.waktu_selesai) }}
                                             </td>

                                             <!-- Total -->
                                             <td class="px-6 py-5 font-medium text-gray-700">
                                                  Rp{{ formatRupiah(rental.harga) }}
                                             </td>

                                             <!-- Status -->
                                             <td class="px-6 py-5">

                                                  <span class="rounded-full px-3 py-1 text-xs font-medium" :class="{
                                                       'bg-green-100 text-green-700':
                                                            rental.status === 'aktif',

                                                       'bg-gray-100 text-gray-600':
                                                            rental.status === 'selesai',

                                                       'bg-yellow-100 text-yellow-700':
                                                            rental.status === 'menunggu'
                                                  }">
                                                       {{
                                                            rental.status === "aktif"
                                                                 ? "Aktif"
                                                       : rental.status === "selesai"
                                                       ? "Selesai"
                                                       : "Menunggu"
                                                       }}
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

     <!-- Modal Tambah Rental -->
     <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
          <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">

               <!-- Header -->
               <div class="flex items-center justify-between">
                    <div>
                         <h3 class="text-lg font-semibold text-gray-800">
                              Tambah Rental
                         </h3>

                         <p class="mt-1 text-sm text-gray-500">
                              Buat sesi rental baru.
                         </p>
                    </div>

                    <button @click="showModal = false" type="button" class="text-xl text-gray-400 hover:text-gray-600">
                         ×
                    </button>
               </div>

               <!-- Error -->
               <div v-if="formError" class="mt-5 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-600">
                    {{ formError }}
               </div>

               <!-- Form -->
               <div class="mt-6 space-y-5">

                    <!-- Pelanggan -->
                    <div>
                         <label class="mb-2 block text-sm font-medium text-gray-700">
                              Username Pelanggan
                         </label>

                         <select v-model="form.pelanggan_id"
                              class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#4682A9] focus:ring-4 focus:ring-[#91C8E4]/20">
                              <option value="">
                                   Pilih pelanggan
                              </option>

                              <option v-for="item in pelanggan" :key="item.id" :value="item.id">
                                   {{ item.name }}
                              </option>
                         </select>
                    </div>

                    <!-- Perangkat -->
                    <div>
                         <label class="mb-2 block text-sm font-medium text-gray-700">
                              Perangkat
                         </label>

                         <select v-model="form.perangkat_id"
                              class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#4682A9] focus:ring-4 focus:ring-[#91C8E4]/20">
                              <option value="">
                                   Pilih perangkat
                              </option>

                              <option v-for="item in perangkat" :key="item.id" :value="item.id">
                                   {{ item.name }} - {{ item.jenis_perangkat?.name || "Tanpa Jenis" }}
                              </option>
                         </select>

                         <p v-if="perangkat.length === 0" class="mt-2 text-xs text-red-500">
                              Tidak ada perangkat yang tersedia.
                         </p>
                    </div>

                    <!-- Durasi -->
                    <div>
                         <label class="mb-2 block text-sm font-medium text-gray-700">
                              Durasi
                         </label>

                         <select v-model="form.durasi"
                              class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#4682A9] focus:ring-4 focus:ring-[#91C8E4]/20">
                              <option :value="1">1 Jam</option>
                              <option :value="2">2 Jam</option>
                              <option :value="3">3 Jam</option>
                              <option :value="4">4 Jam</option>
                              <option :value="5">5 Jam</option>
                         </select>
                    </div>

               </div>

               <!-- Footer -->
               <div class="mt-7 flex justify-end gap-3">

                    <button type="button" @click="showModal = false"
                         class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-200">
                         Batal
                    </button>

                    <button type="button" @click="createRental" :disabled="saving"
                         class="rounded-xl bg-[#4682A9] px-5 py-2.5 text-sm font-medium text-white hover:bg-[#3b7194] disabled:cursor-not-allowed disabled:opacity-50">
                         {{ saving ? "Menyimpan..." : "Tambah Rental" }}
                    </button>

               </div>

          </div>
     </div>
</template>