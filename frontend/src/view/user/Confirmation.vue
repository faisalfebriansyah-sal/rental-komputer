<script setup>
import { RouterLink } from 'vue-router';
import { Gamepad2, Ticket } from 'lucide-vue-next';
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
          to="/rental/code"
          class="text-sm font-medium text-slate-500 transition hover:text-[#4682A9]"
        >
          Kembali
        </RouterLink>

      </nav>
    </header>


    <!-- Content -->
    <main class="flex min-h-[calc(100vh-90px)] items-center justify-center px-6 py-10">

      <section class="w-full max-w-lg">

        <!-- Heading -->
        <div class="text-center">
          <div
            class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#91C8E4]/40 text-3xl"
          >
            ✓
          </div>

          <h2 class="mt-6 text-3xl font-bold text-[#4682A9]">
            Konfirmasi Rental
          </h2>

          <p class="mt-3 text-sm text-slate-500">
            Periksa kembali detail rental kamu sebelum memulai sesi.
          </p>
        </div>


        <!-- Rental Card -->
        <div
          class="mt-8 overflow-hidden rounded-3xl border border-[#749BC2]/20 bg-white shadow-sm"
        >

          <!-- Device -->
          <div class="flex items-center gap-4 border-b border-slate-100 p-6">

            <div
              class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#91C8E4]/30 text-2xl"
            >
              🖥️
            </div>

            <div>
              <p class="text-xs font-medium text-slate-400">
                Perangkat
              </p>

              <h3 class="mt-1 text-lg font-bold text-slate-800">
                PC 01 - VIP
              </h3>



              <p class="text-sm text-[#4682A9]">
                Ready
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
                2 Jam
              </span>
            </div>

            <div class="flex items-center justify-between">
              <span class="text-sm text-slate-500">
                Tarif
              </span>

              <span class="text-sm font-semibold text-slate-800">
                Rp10.000 / jam
              </span>
            </div>

            <div class="border-t border-slate-100 pt-4">
              <div class="flex items-center justify-between">

                <span class="font-semibold text-slate-700">
                  Total
                </span>

                <span class="text-xl font-bold text-[#4682A9]">
                  Rp20.000
                </span>

              </div>
            </div>

          </div>

        </div>


        <!-- Start Button -->
        <button
          class="mt-6 w-full rounded-xl bg-[#4682A9] py-4 text-sm font-semibold text-white shadow-lg shadow-[#4682A9]/20 transition hover:bg-[#749BC2] hover:shadow-xl"
        >
          Mulai Sesi
        </button>


        <!-- Notice -->
        <p class="mt-5 text-center text-xs leading-5 text-slate-400">
          Dengan memulai sesi, waktu rental akan mulai dihitung.
        </p>

      </section>

    </main>

  </div>
</template>