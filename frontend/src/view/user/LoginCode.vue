<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { Gamepad2, Ticket } from 'lucide-vue-next';

const router = useRouter();

const rentalCode = ref('');
const errorMessage = ref('');
const loading = ref(false);

const submitCode = async () => {
  errorMessage.value = '';

  if (!rentalCode.value.trim()) {
    errorMessage.value = 'Kode rental wajib diisi.';
    return;
  }

  loading.value = true;

  try {
    const response = await fetch(
      'http://127.0.0.1:8000/api/rental/verify-code',
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          kode_sesi: rentalCode.value.trim()
        })
      }
    );

    const result = await response.json();

    if (!response.ok) {
      errorMessage.value = result.message || 'Kode rental tidak valid.';
      return;
    }

    // Simpan data sesi untuk halaman confirmation
    sessionStorage.setItem(
      'rentalSession',
      JSON.stringify(result.data)
    );

    // Kalau kode benar
    router.push('/rental/confirmation');

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
            class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#4682A9] text-lg text-white"
          >
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

        <RouterLink
          to="/"
          class="text-sm font-medium text-slate-500 transition hover:text-[#4682A9]"
        >
          Kembali
        </RouterLink>

      </nav>
    </header>


    <!-- Content -->
    <main class="flex min-h-[calc(100vh-90px)] items-center justify-center px-6">

      <section class="w-full max-w-md">

        <!-- Icon -->
        <div
          class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#91C8E4]/40 text-3xl"
        >
          <Ticket :size="36" color="#4682A9" />
        </div>

        <!-- Heading -->
        <div class="mt-6 text-center">
          <h2 class="text-3xl font-bold text-[#4682A9]">
            Masukkan Kode Rental
          </h2>

          <p class="mt-3 text-sm leading-6 text-slate-500">
            Masukkan kode yang tertera pada struk rental
            untuk mengakses sesi kamu.
          </p>
        </div>


        <!-- Card -->
        <div
          class="mt-8 rounded-3xl border border-[#749BC2]/20 bg-white p-7 shadow-sm"
        >

          <label
            for="rental-code"
            class="text-sm font-semibold text-slate-700"
          >
            Kode Rental
          </label>

          <input
            id="rental-code"
            v-model="rentalCode"
            type="text"
            placeholder="Contoh: PC-A12-458"
            class="mt-2 w-full rounded-xl border border-slate-200 bg-[#F6F4EB]/40 px-4 py-3 text-center font-medium tracking-widest text-slate-700 outline-none transition placeholder:tracking-normal placeholder:text-slate-400 focus:border-[#4682A9] focus:ring-4 focus:ring-[#91C8E4]/30"
          />

          <button
            @click="submitCode"
            type="button"
            :disabled="loading"
            class="mt-5 flex w-full items-center justify-center rounded-xl bg-[#4682A9] py-3.5 text-sm font-semibold text-white transition hover:bg-[#749BC2] disabled:cursor-not-allowed disabled:opacity-60"
          >
            {{ loading ? 'Memeriksa...' : 'Lanjutkan' }}
          </button>
          <p
            v-if="errorMessage"
            class="mt-3 text-center text-sm text-red-500"
          >
            {{ errorMessage }}
          </p>

        </div>


        <!-- Help -->
        <p class="mt-6 text-center text-xs leading-5 text-slate-400">
          Kode rental dapat ditemukan pada struk yang
          diberikan setelah melakukan pembayaran.
        </p>

      </section>

    </main>

  </div>
</template>