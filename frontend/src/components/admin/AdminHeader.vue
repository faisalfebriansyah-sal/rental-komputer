<script setup>
import { onMounted, onUnmounted, ref } from "vue";
import { Moon, Sun } from "lucide-vue-next";

defineProps({
  title: {
    type: String,
    default: 'Dashboard',
  },
});

const date = new Date();
const isDarkMode = ref(false);

const applyTheme = (darkMode) => {
  isDarkMode.value = darkMode;
  document.body.classList.toggle("dark-mode", darkMode);
  localStorage.setItem("admin-theme", darkMode ? "dark" : "light");
};

const toggleTheme = () => {
  applyTheme(!isDarkMode.value);
};

onMounted(() => {
  document.body.classList.add("admin-page");
  applyTheme(localStorage.getItem("admin-theme") === "dark");
});

onUnmounted(() => {
  document.body.classList.remove("admin-page", "dark-mode");
});
</script>

<template>
  <header class="flex items-center justify-between border-b border-gray-200 bg-white px-6 py-5 sm:px-8">
    <div>
      <p class="text-sm text-gray-500">
        {{ date.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
      </p>

      <h1 class="mt-1 text-2xl font-bold text-gray-800">
        {{ title }}
      </h1>
    </div>

    <div class="flex items-center gap-3">
      <button type="button" @click="toggleTheme"
        class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-gray-600 transition hover:bg-gray-200"
        :aria-label="isDarkMode ? 'Aktifkan mode terang' : 'Aktifkan mode gelap'"
        :title="isDarkMode ? 'Mode terang' : 'Mode gelap'">
        <Sun v-if="isDarkMode" :size="18" :stroke-width="1.8" />
        <Moon v-else :size="18" :stroke-width="1.8" />
      </button>

      <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#91C8E4]/30 font-semibold text-[#4682A9]">
        A
      </div>
    </div>
  </header>
</template>
