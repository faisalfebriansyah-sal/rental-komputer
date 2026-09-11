<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import AdminSidebar from "../../components/admin/AdminSidebar.vue";
import AdminHeader from "../../components/admin/AdminHeader.vue";
import {
     Monitor,
     DollarSign,
     TrendingUp,
     MonitorPlay,
     MonitorCheck,
     LucideMonitorCheck,
} from "lucide-vue-next";

const perangkat = ref([]);
const rentals = ref([]);
const pembayaran = ref([]);

const now = ref(new Date());

let timer = null;

const getSisaDurasi = (rental) => {
     if (
          rental.status !== "aktif" ||
          !rental.waktu_mulai ||
          !rental.durasi
     ) {
          return null;
     }

     const waktuMulai = new Date(rental.waktu_mulai);

     // durasi rental dalam jam
     const waktuSelesai = new Date(
          waktuMulai.getTime() +
          Number(rental.durasi) * 60 * 60 * 1000
     );

     const sisaMs = waktuSelesai.getTime() - now.value.getTime();

     if (sisaMs <= 0) {
          return "Selesai";
     }

     const totalMenit = Math.floor(sisaMs / (1000 * 60));

     const jam = Math.floor(totalMenit / 60);
     const menit = totalMenit % 60;

     if (jam > 0) {
          return `${jam} jam ${menit} menit`;
     }

     return `${menit} menit`;
};

const loading = ref(true);
const errorMessage = ref("");

const getDashboardData = async () => {
     loading.value = true;
     errorMessage.value = "";

     try {
          const token = localStorage.getItem("token");

          const headers = {
               Accept: "application/json",
               Authorization: `Bearer ${token}`,
          };

          const [
               perangkatResponse,
               rentalResponse,
               pembayaranResponse
          ] = await Promise.all([
               fetch("http://127.0.0.1:8000/api/perangkat", {
                    headers,
               }),

               fetch("http://127.0.0.1:8000/api/sesi_rental", {
                    headers,
               }),

               fetch("http://127.0.0.1:8000/api/pembayaran", {
                    headers,
               }),
          ]);

          const perangkatResult = await perangkatResponse.json();
          const rentalResult = await rentalResponse.json();
          const pembayaranResult = await pembayaranResponse.json();

          if (!perangkatResponse.ok) {
               throw new Error(
                    perangkatResult.message || "Gagal mengambil data perangkat."
               );
          }

          if (!rentalResponse.ok) {
               throw new Error(
                    rentalResult.message || "Gagal mengambil data rental."
               );
          }

          if (!pembayaranResponse.ok) {
               throw new Error(
                    pembayaranResult.message || "Gagal mengambil data pembayaran."
               );
          }

          perangkat.value = perangkatResult.data || [];
          rentals.value = rentalResult.data || [];
          pembayaran.value = pembayaranResult.data || [];

     } catch (error) {
          console.error(error);
          errorMessage.value =
               error.message || "Tidak dapat terhubung ke server.";
     } finally {
          loading.value = false;
     }
};


// =========================
// STATISTIK PERANGKAT
// =========================

const totalPerangkat = computed(() => {
     return perangkat.value.length;
});

const perangkatDigunakan = computed(() => {
     return perangkat.value.filter(
          (item) => item.status === "digunakan"
     ).length;
});

const perangkatTersedia = computed(() => {
     return perangkat.value.filter(
          (item) => item.status === "tersedia"
     ).length;
});


// =========================
// RENTAL AKTIF
// =========================

const rentalAktif = computed(() => {
     return rentals.value.filter(
          (item) => item.status === "aktif"
     ).length;
});

const getRentalAktif = (perangkatId) => {
     return rentals.value.find(
          (rental) =>
               rental.perangkat_id === perangkatId &&
               rental.status === "aktif"
     );
};


// =========================
// PENDAPATAN HARI INI
// =========================

const pendapatanHariIni = computed(() => {
     const today = new Date();

     const tahun = today.getFullYear();
     const bulan = String(today.getMonth() + 1).padStart(2, "0");
     const tanggal = String(today.getDate()).padStart(2, "0");

     const todayString = `${tahun}-${bulan}-${tanggal}`;

     return pembayaran.value
          .filter((payment) => {
               return (
                    payment.status === "lunas" &&
                    payment.waktu_bayar?.startsWith(todayString)
               );
          })
          .reduce((total, payment) => {
               return total + Number(payment.jumlah || 0);
          }, 0);
});


// =========================
// TRANSAKSI HARI INI
// =========================

const transaksiHariIni = computed(() => {
     const today = new Date();

     const tahun = today.getFullYear();
     const bulan = String(today.getMonth() + 1).padStart(2, "0");
     const tanggal = String(today.getDate()).padStart(2, "0");

     const todayString = `${tahun}-${bulan}-${tanggal}`;

     return pembayaran.value.filter((payment) => {
          return (
               payment.status === "lunas" &&
               payment.waktu_bayar?.startsWith(todayString)
          );
     }).length;
});


// =========================
// RENTAL TERBARU
// =========================

const rentalTerbaru = computed(() => {
     return rentals.value.slice(0, 5);
});


// =========================
// FORMAT
// =========================

const formatRupiah = (value) => {
     return Number(value || 0).toLocaleString("id-ID");
};

const formatStatus = (status) => {
     if (status === "aktif") {
          return "Aktif";
     }

     if (status === "selesai") {
          return "Selesai";
     }

     if (status === "belum_main") {
          return "Belum Main";
     }

     return status;
};

const statusClass = (status) => {
     if (status === "aktif") {
          return "text-green-600";
     }

     if (status === "selesai") {
          return "text-gray-400";
     }

     return "text-yellow-600";
};


// =========================
// MOUNT
// =========================

onMounted(() => {
     getDashboardData();

     timer = setInterval(() => {
          now.value = new Date();
     }, 1000);
});

onUnmounted(() => {
     clearInterval(timer)
})
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
                    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">

                         <!-- Total Perangkat -->
                         <div class="rounded-2xl bg-white p-6 shadow-sm">
                              <template v-if="loading">
                                   <div class="animate-pulse">
                                        <div class="flex items-center justify-between">
                                             <div class="h-4 w-28 rounded bg-gray-200"></div>
                                             <div class="h-10 w-10 rounded-lg bg-gray-200"></div>
                                        </div>

                                        <div class="mt-5 h-8 w-16 rounded bg-gray-200"></div>
                                        <div class="mt-2 h-3 w-32 rounded bg-gray-200"></div>
                                   </div>
                              </template>

                              <template v-else>
                                   <div class="flex items-center justify-between">
                                        <p class="text-sm text-gray-500">
                                             Total Perangkat
                                        </p>

                                        <span class="rounded-lg bg-[#91C8E4]/25 px-3 py-2">
                                             <Monitor :size="18" :stroke-width="1.8" class="text-[#4682A9]" />
                                        </span>
                                   </div>

                                   <p class="mt-5 text-2xl font-bold text-gray-800">
                                        {{ totalPerangkat }}
                                   </p>

                                   <p class="mt-2 text-xs text-gray-500">
                                        Semua perangkat PC
                                   </p>
                              </template>
                         </div>


                         <!-- Sedang Rental -->
                         <div class="rounded-2xl bg-white p-6 shadow-sm">
                              <template v-if="loading">
                                   <div class="animate-pulse">
                                        <div class="flex items-center justify-between">
                                             <div class="h-4 w-28 rounded bg-gray-200"></div>
                                             <div class="h-10 w-10 rounded-lg bg-gray-200"></div>
                                        </div>

                                        <div class="mt-5 h-8 w-16 rounded bg-gray-200"></div>
                                        <div class="mt-2 h-3 w-32 rounded bg-gray-200"></div>
                                   </div>
                              </template>

                              <template v-else>
                                   <div class="flex items-center justify-between">
                                        <p class="text-sm text-gray-500">
                                             Sedang Rental
                                        </p>

                                        <span class="rounded-lg bg-green-100 px-3 py-2">
                                             <MonitorPlay :size="18" :stroke-width="1.8" class="text-green-700" />
                                        </span>
                                   </div>

                                   <p class="mt-5 text-2xl font-bold text-gray-800">
                                        {{ perangkatDigunakan }}
                                   </p>

                                   <p class="mt-2 text-xs text-green-600">
                                        {{ rentalAktif }} sesi aktif
                                   </p>
                              </template>

                         </div>


                         <!-- Tersedia -->
                         <div class="rounded-2xl bg-white p-6 shadow-sm">
                              <template v-if="loading">
                                   <div class="animate-pulse">
                                        <div class="flex items-center justify-between">
                                             <div class="h-4 w-28 rounded bg-gray-200"></div>
                                             <div class="h-10 w-10 rounded-lg bg-gray-200"></div>
                                        </div>

                                        <div class="mt-5 h-8 w-16 rounded bg-gray-200"></div>
                                        <div class="mt-2 h-3 w-32 rounded bg-gray-200"></div>
                                   </div>
                              </template>

                              <template v-else>
                                   <div class="flex items-center justify-between">
                                        <p class="text-sm text-gray-500">
                                             Perangkat Tersedia
                                        </p>

                                        <span class="rounded-lg bg-blue-100 px-3 py-2">
                                             <LucideMonitorCheck :size="18" :stroke-width="1.8" class="text-blue-700" />
                                        </span>
                                   </div>

                                   <p class="mt-5 text-2xl font-bold text-gray-800">
                                        {{ perangkatTersedia }}
                                   </p>

                                   <p class="mt-2 text-xs text-gray-500">
                                        Siap digunakan
                                   </p>
                              </template>

                         </div>


                         <!-- Pendapatan -->
                         <div class="rounded-2xl bg-white p-6 shadow-sm">

                              <template v-if="loading">
                                   <div class="animate-pulse">
                                        <div class="flex items-center justify-between">
                                             <div class="h-4 w-28 rounded bg-gray-200"></div>
                                             <div class="h-10 w-10 rounded-lg bg-gray-200"></div>
                                        </div>

                                        <div class="mt-5 h-8 w-16 rounded bg-gray-200"></div>
                                        <div class="mt-2 h-3 w-32 rounded bg-gray-200"></div>
                                   </div>
                              </template>

                              <template v-else>
                                   <div class="flex items-center justify-between">
                                        <p class="text-sm text-gray-500">
                                             Pendapatan Hari Ini
                                        </p>

                                        <span class="rounded-lg bg-[#F6F4EB] px-3 py-2">
                                             <TrendingUp :size="18" :stroke-width="1.8" class="text-[#4682A9]" />
                                        </span>
                                   </div>

                                   <p class="mt-5 text-2xl font-bold text-gray-800">
                                        Rp{{ formatRupiah(pendapatanHariIni) }}
                                   </p>

                                   <p class="mt-2 text-xs text-gray-500">
                                        {{ transaksiHariIni }} transaksi hari ini
                                   </p>
                              </template>

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
                                             </tr>
                                        </thead>
                                        <tbody class="text-sm">

                                             <!-- LOADING -->
                                             <template v-if="loading">

                                                  <tr v-for="i in 5" :key="i"
                                                       class="border-b border-gray-100 animate-pulse">
                                                       <td class="py-4">
                                                            <div class="h-4 w-28 rounded bg-gray-200"></div>
                                                       </td>

                                                       <td class="py-4">
                                                            <div class="h-4 w-24 rounded bg-gray-200"></div>
                                                       </td>

                                                       <td class="py-4">
                                                            <div class="h-4 w-32 rounded bg-gray-200"></div>
                                                       </td>
                                                  </tr>

                                             </template>

                                             <!-- DATA -->
                                             <template v-else>

                                                  <tr v-for="item in perangkat.slice(0, 5)" :key="item.id"
                                                       class="border-b border-gray-100">

                                                       <td class="py-4 font-medium text-gray-700">
                                                            {{ item.name }}
                                                       </td>

                                                       <td class="py-4 text-gray-500">
                                                            {{ item.jenis_perangkat?.name || "-" }}
                                                       </td>

                                                       <td class="py-4">

                                                            <template v-if="item.status === 'digunakan'">

                                                                 <div class="font-medium text-green-600">
                                                                      Digunakan
                                                                 </div>

                                                                 <div v-if="getRentalAktif(item.id)"
                                                                      class="text-sm text-gray-500">
                                                                      {{ getSisaDurasi(getRentalAktif(item.id)) }}
                                                                      tersisa
                                                                 </div>

                                                            </template>

                                                            <span v-else-if="item.status === 'tersedia'"
                                                                 class="font-medium text-yellow-600">
                                                                 Tersedia
                                                            </span>

                                                            <span v-else-if="item.status === 'maintenance'"
                                                                 class="font-medium text-red-600">
                                                                 Maintenance
                                                            </span>

                                                       </td>

                                                  </tr>

                                                  <tr v-if="perangkat.length === 0">
                                                       <td colspan="3" class="py-8 text-center text-sm text-gray-400">
                                                            Belum ada perangkat.
                                                       </td>
                                                  </tr>

                                             </template>

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

                                   <!-- LOADING -->
                                   <template v-if="loading">

                                        <div v-for="i in 5" :key="i"
                                             class="flex items-center justify-between animate-pulse">
                                             <div>
                                                  <div class="h-4 w-28 rounded bg-gray-200"></div>

                                                  <div class="mt-2 h-3 w-24 rounded bg-gray-200"></div>
                                             </div>

                                             <div class="h-3 w-14 rounded bg-gray-200"></div>
                                        </div>

                                   </template>

                                   <!-- DATA -->
                                   <template v-else>

                                        <div v-for="rental in rentalTerbaru" :key="rental.id"
                                             class="flex items-center justify-between">

                                             <div>
                                                  <p class="text-sm font-medium text-gray-700">
                                                       {{ rental.perangkat?.name || "-" }}
                                                  </p>

                                                  <p class="mt-1 text-xs text-gray-400">
                                                       Kode: {{ rental.kode_sesi }}
                                                  </p>
                                             </div>

                                             <span :class="statusClass(rental.status)" class="text-xs">
                                                  {{ formatStatus(rental.status) }}
                                             </span>

                                        </div>

                                        <p v-if="rentalTerbaru.length === 0"
                                             class="py-5 text-center text-sm text-gray-400">
                                             Belum ada rental.
                                        </p>

                                   </template>

                              </div>
                         </div>
                    </div>
               </div>
          </main>
     </div>
</template>