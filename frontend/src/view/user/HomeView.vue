<template>
  <div class="min-h-screen bg-[#F6F4EB] text-slate-800">

    <!-- Navbar -->
    <header class="px-6 py-5">
      <nav class="mx-auto flex max-w-6xl items-center justify-between">

        <!-- Logo -->
        <div class="flex items-center gap-3">
          <div
            class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#4682A9] text-white"
          >
            🎮
          </div>

          <div>
            <h1 class="text-lg font-bold text-[#4682A9]">
              RENTAL
            </h1>
            <p class="-mt-1 text-xs font-medium tracking-wider text-slate-500">
              PC 
            </p>
          </div>
        </div>

        <!-- Admin -->
        <button
          class="rounded-lg px-4 py-2 text-sm font-medium text-[#4682A9] transition hover:bg-[#91C8E4]/30"
        >
          Admin
        </button>

      </nav>
    </header>


    <!-- Hero -->
    <main class="px-6">

      <section
        class="mx-auto flex min-h-[calc(100vh-100px)] max-w-6xl items-center justify-center"
      >

        <div class="w-full max-w-3xl text-center">

          <!-- Small badge -->
          <div
            class="mx-auto mb-6 inline-flex items-center gap-2 rounded-full bg-[#91C8E4]/30 px-4 py-2 text-sm font-medium text-[#4682A9]"
          >
            <span class="h-2 w-2 rounded-full bg-[#4682A9]"></span>
            Rental PC
          </div>

          <!-- Heading -->
          <h2
            class="text-5xl font-bold leading-tight tracking-tight text-[#4682A9] md:text-6xl"
          >
            Main tanpa ribet.
          </h2>

          <p
            class="mx-auto mt-5 max-w-xl text-base leading-7 text-slate-500 md:text-lg"
          >
            Masukkan kode rental dari struk untuk mengakses
            dan memulai sesi kamu.
          </p>

          <!-- Button -->
          <RouterLink
            to="/rental/code"   
            class="mt-9 inline-block rounded-xl bg-[#4682A9] px-8 py-4 text-sm font-semibold text-white shadow-lg shadow-[#4682A9]/20 transition hover:bg-[#749BC2] hover:shadow-xl"
          >
            Masukkan Kode Rental
          </RouterLink>


          <!-- Features -->
          <div class="mt-20 grid grid-cols-1 gap-4 sm:grid-cols-3">

            <!-- Feature 1 -->
            <div
              class="rounded-2xl border border-[#749BC2]/20 bg-white/60 p-5"
            >
              <div class="mb-3 text-2xl">
                🖥️
              </div>

              <h3 class="font-semibold text-slate-800">
                PC Gaming
              </h3>

              <p class="mt-1 text-sm text-slate-500">
                Nikmati perangkat yang siap digunakan.
              </p>
            </div>


            <!-- Feature 2 -->
            <div
              class="rounded-2xl border border-[#749BC2]/20 bg-white/60 p-5"
            >
              <div class="mb-3 text-2xl">
                🎮
              </div>

              <h3 class="font-semibold text-slate-800">
                GameStation
              </h3>

              <p class="mt-1 text-sm text-slate-500">
                Pilih perangkat dan mulai bermain.
              </p>
            </div>


            <!-- Feature 3 -->
            <div
              class="rounded-2xl border border-[#749BC2]/20 bg-white/60 p-5"
            >
              <div class="mb-3 text-2xl">
                ⚡
              </div>

              <h3 class="font-semibold text-slate-800">
                Praktis
              </h3>

              <p class="mt-1 text-sm text-slate-500">
                Akses sesi rental dengan mudah.
              </p>
            </div>

          </div>

          <!-- Help -->
          <p class="mt-8 text-xs text-slate-400">
            Butuh bantuan? Hubungi staff rental.
          </p>

        </div>

      </section>

    </main>

  </div>
</template>