<script setup>
import { ref } from "vue";
import { useRouter, useRoute } from "vue-router";

const router = useRouter();
const route = useRoute();

const username = ref("");
const password = ref("");
const rememberMe = ref(false);
const loading = ref(false)

const login = async () => {
  loading.value = true;

  try {
    const response = await fetch("http://localhost:8000/api/login", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        username: username.value,
        password: password.value,
      }),
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message || "Login gagal");
    }

    localStorage.setItem("token", data.token);

    router.push("/admin/dashboard");
  } catch (error) {
    console.error(error);
    alert(error.message);
  } finally {
    loading.value = false;
  }
};

const logout = () => {
  localStorage.removeItem("token");
  route.push("/admin/login");
}
</script>


<template>
  <div class="min-h-screen bg-[#F6F4EB] flex items-center justify-center px-6">

    <div
      class="w-full max-w-5xl overflow-hidden rounded-3xl bg-white shadow-xl shadow-[#4682A9]/10 grid md:grid-cols-2">

      <!-- Left Panel -->
      <div class="hidden md:flex flex-col justify-between bg-[#4682A9] p-10 text-white">
        <div>
          <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/15 text-xl">
              🎮
            </div>

            <div>
              <p class="text-sm font-semibold">
                RENTAL PC & PS
              </p>
              <p class="text-xs text-white/70">
                Admin Panel
              </p>
            </div>
          </div>

          <div class="mt-24">
            <h1 class="text-4xl font-bold leading-tight">
              Kelola rental
              <br />
              dengan lebih mudah.
            </h1>

            <p class="mt-5 max-w-sm text-sm leading-6 text-white/75">
              Pantau perangkat, rental, transaksi, dan aktivitas
              pelanggan dari satu tempat.
            </p>
          </div>
        </div>

        <p class="text-xs text-white/60">
          Admin Management System
        </p>
      </div>

      <!-- Login Form -->
      <div class="p-8 sm:p-12 md:p-14">

        <div class="mx-auto max-w-md">

          <!-- Mobile Logo -->
          <div class="mb-10 flex items-center gap-3 md:hidden">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#4682A9] text-xl">
              🎮
            </div>

            <div>
              <p class="text-sm font-semibold text-[#4682A9]">
                RENTAL PC & PS
              </p>
              <p class="text-xs text-gray-500">
                Admin Panel
              </p>
            </div>
          </div>

          <!-- Heading -->
          <div>
            <p class="text-sm font-medium text-[#4682A9]">
              Selamat datang kembali
            </p>

            <h2 class="mt-2 text-3xl font-bold text-gray-800">
              Login Admin
            </h2>

            <p class="mt-3 text-sm leading-6 text-gray-500">
              Masuk ke panel admin untuk mengelola sistem rental.
            </p>
          </div>

          <!-- Form -->
          <div class="mt-9 space-y-5">

            <!-- Username -->
            <div>
              <label for="username" class="mb-2 block text-sm font-medium text-gray-700">
                Username
              </label>

              <input id="username" v-model="username" type="text" placeholder="Masukkan username"
                class="w-full rounded-xl border border-gray-200 bg-[#F6F4EB]/50 px-4 py-3.5 text-sm outline-none transition placeholder:text-gray-400 focus:border-[#4682A9] focus:ring-4 focus:ring-[#91C8E4]/30" />
            </div>

            <!-- Password -->
            <div>
              <label for="password" class="mb-2 block text-sm font-medium text-gray-700">
                Password
              </label>

              <input id="password" v-model="password" type="password" placeholder="Masukkan password"
                class="w-full rounded-xl border border-gray-200 bg-[#F6F4EB]/50 px-4 py-3.5 text-sm outline-none transition placeholder:text-gray-400 focus:border-[#4682A9] focus:ring-4 focus:ring-[#91C8E4]/30" />
            </div>

            <!-- Remember -->
            <div class="flex items-center justify-between text-sm">

              <label class="flex items-center gap-2 text-gray-500">
                <input v-model="rememberMe" type="checkbox" class="h-4 w-4 rounded border-gray-300 accent-[#4682A9]" />

                Ingat saya
              </label>

              <button type="button" class="font-medium text-[#4682A9] hover:text-[#749BC2]">
                Lupa password?
              </button>

            </div>

            <!-- Login Button -->
            <button type="button" @click="login" :disabled="loading"
              class="w-full rounded-xl bg-[#4682A9] py-3.5 text-sm font-semibold text-white shadow-lg shadow-[#4682A9]/20 transition hover:bg-[#749BC2] hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-70">
              <span v-if="loading" class="flex items-center justify-center gap-2">
                <span class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>

                Memproses...
              </span>

              <span v-else>
                Login
              </span>
            </button>

          </div>

          <!-- Back -->
          <div class="mt-8 text-center">
            <RouterLink to="/" class="text-sm text-gray-500 transition hover:text-[#4682A9]">
              ← Kembali ke halaman utama
            </RouterLink>
          </div>

        </div>
      </div>

    </div>
  </div>
</template>