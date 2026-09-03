<template>
  <div class="min-h-screen bg-[#F6F4EB]">

    <!-- Navbar -->
    <header class="px-6 py-5">
      <nav class="mx-auto flex max-w-6xl items-center justify-between">

        <div class="flex items-center gap-3">
          <div
            class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#4682A9] text-lg text-white"
          >
            🎮
          </div>

          <div>
            <h1 class="text-base font-bold text-[#4682A9]">
              RENTAL
            </h1>

            <p class="-mt-1 text-[10px] font-medium tracking-widest text-slate-500">
              PC & PS
            </p>
          </div>
        </div>

        <div
          class="flex items-center gap-2 rounded-full bg-[#91C8E4]/30 px-3 py-2"
        >
          <span class="h-2 w-2 rounded-full bg-green-500"></span>

          <span class="text-xs font-semibold text-[#4682A9]">
            Sesi Aktif
          </span>
        </div>

      </nav>
    </header>


    <!-- Content -->
    <main class="flex min-h-[calc(100vh-90px)] items-center justify-center px-6 py-10">

      <section class="w-full max-w-2xl">

        <!-- Heading -->
        <div class="text-center">

          <p class="text-sm font-medium text-[#749BC2]">
            Sesi sedang berlangsung
          </p>

          <h2 class="mt-2 text-3xl font-bold text-[#4682A9] md:text-4xl">
            PC 01 - VIP
          </h2>

        </div>


        <!-- Timer Card -->
        <div
          class="mt-8 rounded-3xl border border-[#749BC2]/20 bg-white p-8 text-center shadow-sm"
        >

          <p class="text-sm font-medium text-slate-400">
            Waktu tersisa
          </p>

          <div
            class="mt-4 text-6xl font-bold tracking-tight text-[#4682A9] md:text-7xl"
          >
            01:42:36
          </div>

          <p class="mt-3 text-xs text-slate-400">
            1 jam 42 menit 36 detik
          </p>

        </div>


        <!-- Information -->
        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

          <div
            class="rounded-2xl border border-[#749BC2]/20 bg-white p-5"
          >
            <p class="text-xs text-slate-400">
              Waktu Mulai
            </p>

            <p class="mt-2 font-semibold text-slate-800">
              14:00
            </p>
          </div>


          <div
            class="rounded-2xl border border-[#749BC2]/20 bg-white p-5"
          >
            <p class="text-xs text-slate-400">
              Waktu Selesai
            </p>

            <p class="mt-2 font-semibold text-slate-800">
              16:00
            </p>
          </div>


          <div
            class="rounded-2xl border border-[#749BC2]/20 bg-white p-5"
          >
            <p class="text-xs text-slate-400">
              Durasi
            </p>

            <p class="mt-2 font-semibold text-slate-800">
              2 Jam
            </p>
          </div>

        </div>


        <!-- Status -->
        <div
          class="mt-5 flex items-center justify-between rounded-2xl border border-[#749BC2]/20 bg-white px-5 py-4"
        >

          <div>
            <p class="text-xs text-slate-400">
              Total Rental
            </p>

            <p class="mt-1 text-lg font-bold text-[#4682A9]">
              Rp20.000
            </p>
          </div>

          <div class="text-right">
            <p class="text-xs text-slate-400">
              Status
            </p>

            <p class="mt-1 text-sm font-semibold text-green-600">
              ● Sedang Berjalan
            </p>
          </div>

        </div>


        <!-- Notice -->
        <div
          class="mt-6 rounded-2xl bg-[#91C8E4]/20 px-5 py-4 text-center"
        >
          <p class="text-sm leading-6 text-[#4682A9]">
            Jangan matikan atau restart perangkat selama sesi rental
            masih berlangsung.
          </p>
        </div>

      </section>

    </main>

  </div>
</template>