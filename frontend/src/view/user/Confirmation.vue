<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { RouterLink } from 'vue-router';
import { Gamepad2, Monitor, CircleCheck } from 'lucide-vue-next';

const router = useRouter();

const loading = ref(false);
const errorMessage = ref('');

const rental = ref(null);

// Ambil data rental dari sessionStorage
const sessionData = sessionStorage.getItem('rentalSession');

if (sessionData) {
     try {
          rental.value = JSON.parse(sessionData);
     } catch (error) {
          console.error(error);
          errorMessage.value = 'Data rental tidak valid.';
     }
}

// Format rupiah
const formatRupiah = (value) => {
     return Number(value || 0).toLocaleString('id-ID');
};

const startSession = async () => {
     errorMessage.value = '';
     loading.value = true;

     try {
          if (!rental.value) {
               errorMessage.value = 'Data rental tidak ditemukan.';
               return;
          }

          const response = await fetch(
               `http://127.0.0.1:8000/api/rental/start-session/${rental.value.id}`,
               {
                    method: 'POST',
                    headers: {
                         'Accept': 'application/json'
                    }
               }
          );

          const result = await response.json();

          if (!response.ok) {
               errorMessage.value =
                    result.message || 'Gagal memulai sesi rental.';
               return;
          }

          sessionStorage.setItem(
               'rentalSession',
               JSON.stringify(result.data)
          );

          router.push('/rental/session');

     } catch (error) {
          console.error(error);
          errorMessage.value = 'Tidak dapat terhubung ke server.';
     } finally {
          loading.value = false;
     }
};
</script>
<template>
     <div class="min-h-screen bg-[#F6F4EB]">

          <!-- Navbar -->
          <header class="px-6 py-5">
               <nav class="mx-auto flex max-w-6xl items-center justify-between">

                    <RouterLink to="/" class="flex items-center gap-3">
                         <div
                              class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#4682A9] text-lg text-white">
                              <Gamepad2 :size="22" />
                         </div>

                         <div>
                              <h1 class="text-base font-bold text-[#4682A9]">
                                   PLAY
                              </h1>

                              <p class="-mt-1 text-[10px] font-medium tracking-widest text-slate-500">
                                   Point
                              </p>
                         </div>
                    </RouterLink>

                    <RouterLink to="/rental/code"
                         class="text-sm font-medium text-slate-500 transition hover:text-[#4682A9]">
                         Kembali
                    </RouterLink>

               </nav>
          </header>


          <!-- Content -->
          <main class="flex min-h-[calc(100vh-90px)] items-center justify-center px-6 py-10">

               <section class="w-full max-w-lg">

                    <!-- Heading -->
                    <div class="text-center">

                         <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#91C8E4]/40">
                              <CircleCheck :size="38" color="#4682A9" stroke-width="2" />
                         </div>

                         <h2 class="mt-6 text-3xl font-bold text-[#4682A9]">
                              Konfirmasi Rental
                         </h2>

                         <p class="mt-3 text-sm text-slate-500">
                              Periksa kembali detail rental kamu sebelum memulai sesi.
                         </p>

                    </div>


                    <!-- Rental Card -->
                    <div class="mt-8 overflow-hidden rounded-3xl border border-[#749BC2]/20 bg-white shadow-sm">

                         <!-- Device -->
                         <div class="flex items-center gap-4 border-b border-slate-100 p-6">

                              <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#91C8E4]/30">
                                   <Monitor :size="30" color="#4682A9" />
                              </div>

                              <div>

                                   <p class="text-xs font-medium text-slate-400">
                                        Perangkat
                                   </p>

                                   <h3 class="mt-1 text-lg font-bold text-slate-800">
                                        {{ rental?.perangkat?.name || '-' }}
                                        <template
                                             v-if="rental?.perangkat?.jenis_perangkat?.name || rental?.perangkat?.jenisPerangkat?.name">
                                             - {{ (rental?.perangkat?.jenis_perangkat?.name ||
                                                  rental?.perangkat?.jenisPerangkat?.name)?.replace('PC - ', '') }}
                                        </template>
                                   </h3>

                                   <p class="text-sm text-slate-500">
                                        {{ rental?.perangkat?.status || '-' }}
                                   </p>

                              </div>

                         </div>


                         <!-- Details -->
                         <div class="space-y-4 p-6">

                              <div class="flex items-center justify-between">

                                   <span class="text-sm text-slate-500">
                                        Durasi
                                   </span>

                                   <span class="text-sm font-semibold text-slate-800">
                                        {{ rental?.durasi || 0 }} Jam
                                   </span>

                              </div>


                              <div class="flex items-center justify-between">

                                   <span class="text-sm text-slate-500">
                                        Tarif
                                   </span>

                                   <span class="text-sm font-semibold text-slate-800">
                                        Rp{{ formatRupiah(
                                             Number(rental?.harga || 0) / Number(rental?.durasi || 1)
                                        ) }} / jam
                                   </span>

                              </div>


                              <div class="border-t border-slate-100 pt-4">

                                   <div class="flex items-center justify-between">

                                        <span class="font-semibold text-slate-700">
                                             Total
                                        </span>

                                        <span class="text-xl font-bold text-[#4682A9]">
                                             Rp{{ formatRupiah(rental?.harga) }}
                                        </span>

                                   </div>

                              </div>

                         </div>

                    </div>


                    <!-- Error -->
                    <p v-if="errorMessage" class="mt-4 text-center text-sm text-red-500">
                         {{ errorMessage }}
                    </p>


                    <!-- Start Button -->
                    <button @click="startSession" :disabled="loading" type="button"
                         class="mt-6 w-full rounded-xl bg-[#4682A9] py-4 text-sm font-semibold text-white shadow-lg shadow-[#4682A9]/20 transition hover:bg-[#749BC2] hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-60">
                         {{ loading ? 'Memulai Sesi...' : 'Mulai Sesi' }}
                    </button>


                    <!-- Notice -->
                    <p class="mt-5 text-center text-xs leading-5 text-slate-400">
                         Dengan memulai sesi, waktu rental akan mulai dihitung.
                    </p>

               </section>

          </main>

     </div>
</template>