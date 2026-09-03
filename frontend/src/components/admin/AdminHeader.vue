<script setup>
defineProps({
  title: {
    type: String,
    default: 'Dashboard',
  },
});

const date = new Date();
</script>

<template>
  <header
    class="flex items-center justify-between border-b border-gray-200 bg-white px-6 py-5 sm:px-8"
  >
    <div>
      <p class="text-sm text-gray-500">
        {{ date.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
      </p>

      <h1 class="mt-1 text-2xl font-bold text-gray-800">
        {{ title }}
      </h1>
    </div>

    <div
      class="flex h-10 w-10 items-center justify-center rounded-full bg-[#91C8E4]/30 font-semibold text-[#4682A9]"
    >
      A
    </div>
  </header>
</template>