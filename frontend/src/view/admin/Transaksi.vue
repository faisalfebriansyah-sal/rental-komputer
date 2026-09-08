<script setup>
import { ref, computed, onMounted } from "vue";
import AdminSidebar from "../../components/admin/AdminSidebar.vue";
import AdminHeader from "../../components/admin/AdminHeader.vue";

const rentals = ref([]);
const pembayaran = ref([]);
const pelanggan = ref([]);

const loading = ref(true);
const errorMessage = ref("");

const activeFilter = ref("semua");
const searchQuery = ref("");

const processingId = ref(null);

/*
|--------------------------------------------------------------------------
| Ambil data rental + pembayaran
|--------------------------------------------------------------------------
*/
const getData = async () => {
  loading.value = true;
  errorMessage.value = "";

  try {
    const token = localStorage.getItem("token");

    const headers = {
      Accept: "application/json",
      Authorization: `Bearer ${token}`,
    };

    // Fetch rentals, payments, and customers concurrently
    const [rentalResponse, pembayaranResponse, pelangganResponse] = await Promise.all([
      fetch("http://127.0.0.1:8000/api/sesi_rental", { headers }),
      fetch("http://127.0.0.1:8000/api/pembayaran", { headers }),
      fetch("http://127.0.0.1:8000/api/pelanggan", { headers }),
    ]);

    const rentalResult = await rentalResponse.json();
    const pembayaranResult = await pembayaranResponse.json();
    const pelangganResult = await pelangganResponse.json();

    if (!rentalResponse.ok) {
      throw new Error(rentalResult.message || "Gagal mengambil data rental.");
    }
    if (!pembayaranResponse.ok) {
      throw new Error(pembayaranResult.message || "Gagal mengambil data pembayaran.");
    }
    if (!pelangganResponse.ok) {
      throw new Error(pelangganResult.message || "Gagal mengambil data pelanggan.");
    }

    rentals.value = rentalResult.data || [];
    pembayaran.value = pembayaranResult.data || [];
    pelanggan.value = pelangganResult.data || [];
  } catch (error) {
    console.error(error);

    errorMessage.value =
      error.message || "Tidak dapat terhubung ke server.";
  } finally {
    loading.value = false;
  }
};
onMounted(() => {
  getData();
});

/*
|--------------------------------------------------------------------------
| Gabungkan rental dengan pembayaran
|--------------------------------------------------------------------------
*/
const transaksi = computed(() => {
  return rentals.value.map((rental) => {
    const payment = pembayaran.value.find((item) => item.sesi_id === rental.id);
    const customer = pelanggan.value.find((c) => c.id === rental.pelanggan_id);

    return {
      ...rental,
      pembayaran: payment || null,
      pelanggan: customer || null,
    };
  });
});

/*
|--------------------------------------------------------------------------
| Filter + Search
|--------------------------------------------------------------------------
*/
const filteredTransaksi = computed(() => {
  return transaksi.value.filter((item) => {
    const keyword = searchQuery.value.toLowerCase();

    const searchMatch =
      item.kode_sesi?.toLowerCase().includes(keyword) ||
      item.perangkat?.name?.toLowerCase().includes(keyword) ||
      item.pelanggan?.username?.toLowerCase().includes(keyword);

    let statusMatch = true;

    if (activeFilter.value === "lunas") {
      statusMatch = item.pembayaran?.status === "lunas";
    }

    if (activeFilter.value === "belum") {
      statusMatch =
        !item.pembayaran ||
        item.pembayaran.status !== "lunas";
    }

    return searchMatch && statusMatch;
  });
});

/*
|--------------------------------------------------------------------------
| Tanggal
|--------------------------------------------------------------------------
*/
const getTodayString = () => {
  const today = new Date();

  const tahun = today.getFullYear();
  const bulan = String(today.getMonth() + 1).padStart(2, "0");
  const tanggal = String(today.getDate()).padStart(2, "0");

  return `${tahun}-${bulan}-${tanggal}`;
};

const getMonthString = () => {
  const today = new Date();

  const tahun = today.getFullYear();
  const bulan = String(today.getMonth() + 1).padStart(2, "0");

  return `${tahun}-${bulan}`;
};

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/
const transaksiHariIni = computed(() => {
  const today = getTodayString();

  return pembayaran.value.filter((item) => {
    return (
      item.status === "lunas" &&
      item.waktu_bayar?.startsWith(today)
    );
  }).length;
});

const pendapatanHariIni = computed(() => {
  const today = getTodayString();

  return pembayaran.value
    .filter((item) => {
      return (
        item.status === "lunas" &&
        item.waktu_bayar?.startsWith(today)
      );
    })
    .reduce((total, item) => {
      return total + Number(item.jumlah || 0);
    }, 0);
});

const pendapatanBulanIni = computed(() => {
  const month = getMonthString();

  return pembayaran.value
    .filter((item) => {
      return (
        item.status === "lunas" &&
        item.waktu_bayar?.startsWith(month)
      );
    })
    .reduce((total, item) => {
      return total + Number(item.jumlah || 0);
    }, 0);
});

/*
|--------------------------------------------------------------------------
| Format
|--------------------------------------------------------------------------
*/
const formatRupiah = (value) => {
  return Number(value || 0).toLocaleString("id-ID");
};

const formatDateTime = (datetime) => {
  if (!datetime) {
    return "-";
  }

  return new Date(datetime).toLocaleString("id-ID", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const formatTransaksiId = (id) => {
  if (!id) {
    return "-";
  }

  return `TRX-${String(id).padStart(5, "0")}`;
};

/*
|--------------------------------------------------------------------------
| Filter
|--------------------------------------------------------------------------
*/
const setFilter = (filter) => {
  activeFilter.value = filter;
};

/*
|--------------------------------------------------------------------------
| Bayar Cash
|--------------------------------------------------------------------------
*/
const bayarCash = async (rental) => {
  if (processingId.value) {
    return;
  }

  const confirmed = confirm(
    `Bayar cash sebesar Rp${formatRupiah(rental.harga)} untuk rental ${rental.kode_sesi}?`
  );

  if (!confirmed) {
    return;
  }

  processingId.value = rental.id;

  try {
    const token = localStorage.getItem("token");

    const response = await fetch(
      "http://127.0.0.1:8000/api/pembayaran",
      {
        method: "POST",

        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },

        body: JSON.stringify({
          sesi_id: rental.id,
        }),
      }
    );

    const result = await response.json();

    if (!response.ok) {
      alert(
        result.message || "Gagal mencatat pembayaran."
      );
      return;
    }

    alert(
      result.message ||
      "Pembayaran cash berhasil dicatat."
    );

    await getData();
  } catch (error) {
    console.error(error);

    alert("Tidak dapat terhubung ke server.");
  } finally {
    processingId.value = null;
  }
};

onMounted(() => {
  getData();
});
</script>

<template>
  <div class="min-h-screen bg-[#F6F4EB]">

    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main -->
    <main class="lg:ml-64">

      <!-- Header -->
      <AdminHeader title="Transaksi" />

      <!-- Content -->
      <div class="p-6 sm:p-8">

        <!-- Title -->
        <div>
          <h2 class="text-xl font-semibold text-gray-800">
            Riwayat Transaksi
          </h2>

          <p class="mt-1 text-sm text-gray-500">
            Pantau seluruh pembayaran rental PC.
          </p>
        </div>

        <!-- Error -->
        <div v-if="errorMessage" class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-600">
          {{ errorMessage }}
        </div>

        <!-- Summary -->
        <div class="mt-8 grid gap-5 sm:grid-cols-3">

          <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm text-gray-500">
              Transaksi Hari Ini
            </p>

            <p class="mt-3 text-3xl font-bold text-gray-800">
              {{ transaksiHariIni }}
            </p>
          </div>

          <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm text-gray-500">
              Pendapatan Hari Ini
            </p>

            <p class="mt-3 text-2xl font-bold text-gray-800">
              Rp{{ formatRupiah(pendapatanHariIni) }}
            </p>
          </div>

          <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm text-gray-500">
              Pendapatan Bulan Ini
            </p>

            <p class="mt-3 text-2xl font-bold text-gray-800">
              Rp{{ formatRupiah(pendapatanBulanIni) }}
            </p>
          </div>

        </div>

        <!-- Filter -->
        <div
          class="mt-8 flex flex-col gap-4 rounded-2xl bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">

          <div class="flex flex-wrap gap-2">

            <button @click="setFilter('semua')" class="rounded-lg px-4 py-2 text-sm font-medium transition" :class="activeFilter === 'semua'
              ? 'bg-[#4682A9] text-white'
              : 'text-gray-500 hover:bg-gray-100'
              ">
              Semua
            </button>

            <button @click="setFilter('lunas')" class="rounded-lg px-4 py-2 text-sm font-medium transition" :class="activeFilter === 'lunas'
              ? 'bg-[#4682A9] text-white'
              : 'text-gray-500 hover:bg-gray-100'
              ">
              Lunas
            </button>

            <button @click="setFilter('belum')" class="rounded-lg px-4 py-2 text-sm font-medium transition" :class="activeFilter === 'belum'
              ? 'bg-[#4682A9] text-white'
              : 'text-gray-500 hover:bg-gray-100'
              ">
              Belum Lunas
            </button>

          </div>

          <input v-model="searchQuery" type="text" placeholder="Cari kode rental..."
            class="w-full rounded-xl border border-gray-200 bg-[#F6F4EB]/40 px-4 py-2.5 text-sm outline-none transition placeholder:text-gray-400 focus:border-[#4682A9] focus:ring-4 focus:ring-[#91C8E4]/20 sm:w-64" />

        </div>

        <!-- Table -->
        <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm">

          <div class="overflow-x-auto">

            <table class="w-full min-w-[1000px] text-left">

              <thead>
                <tr class="border-b border-gray-100 text-xs text-gray-400">

                  <th class="px-6 py-4 font-medium">
                    ID Transaksi
                  </th>

                  <th class="px-6 py-4 font-medium">
                    Kode Rental
                  </th>

                  <th class="px-6 py-4 font-medium">
                    Pembayar
                  </th>

                  <th class="px-6 py-4 font-medium">
                    Perangkat
                  </th>

                  <th class="px-6 py-4 font-medium">
                    Durasi
                  </th>

                  <th class="px-6 py-4 font-medium">
                    Metode
                  </th>

                  <th class="px-6 py-4 font-medium">
                    Total
                  </th>

                  <th class="px-6 py-4 font-medium">
                    Status
                  </th>

                  <th class="px-6 py-4 font-medium">
                    Waktu Bayar
                  </th>

                  <th class="px-6 py-4 font-medium">
                    Aksi
                  </th>

                </tr>
              </thead>

              <tbody class="text-sm">

                <!-- Loading -->
                <tr v-if="loading">
                  <td colspan="9" class="px-6 py-10 text-center text-sm text-gray-400">
                    Memuat data transaksi...
                  </td>
                </tr>

                <!-- Empty -->
                <tr v-else-if="filteredTransaksi.length === 0">
                  <td colspan="10" class="px-6 py-10 text-center text-sm text-gray-400">
                    Belum ada transaksi.
                  </td>
                </tr>

                <!-- Data -->
                <tr v-for="item in filteredTransaksi" :key="item.id" class="border-b border-gray-100 last:border-0">

                  <!-- ID Transaksi -->
                  <td class="px-6 py-5 font-medium text-gray-700">
                    {{ formatTransaksiId(item.pembayaran?.id) }}
                  </td>

                  <!-- Kode Rental -->
                  <td class="px-6 py-5 text-gray-600">
                    {{ item.kode_sesi }}
                  </td>

                 <!-- Pembayar -->
                  <td class="px-6 py-5 text-gray-600">
                    {{ item.pelanggan?.name || "-" }}
                  </td>

                  <!-- Perangkat -->
                  <td class="px-6 py-5 text-gray-600">

                    <div>
                      {{ item.perangkat?.name || "-" }}
                    </div>

                    <div class="text-xs text-gray-400">
                      {{
                        item.perangkat?.jenis_perangkat?.name ||
                        "-"
                      }}
                    </div>

                  </td>

                  <!-- Durasi -->
                  <td class="px-6 py-5 text-gray-600">
                    {{ item.durasi }} Jam
                  </td>

                  <!-- Metode -->
                  <td class="px-6 py-5 text-gray-600">

                    <span v-if="item.pembayaran">
                      Cash
                    </span>

                    <span v-else class="text-gray-400">
                      -
                    </span>

                  </td>

                  <!-- Total -->
                  <td class="px-6 py-5 font-medium text-gray-700">
                    Rp{{ formatRupiah(item.harga) }}
                  </td>

                  <!-- Status -->
                  <td class="px-6 py-5">

                    <span v-if="
                      item.pembayaran?.status === 'lunas'
                    " class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                      Lunas
                    </span>

                    <span v-else class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                      Belum Lunas
                    </span>

                  </td>

                  <!-- Waktu Bayar -->
                  <td class="px-6 py-5 text-gray-600">
                    {{
                      formatDateTime(
                        item.pembayaran?.waktu_bayar
                      )
                    }}
                  </td>

                  <!-- Aksi -->
                  <td class="px-6 py-5">

                    <button v-if="
                      !item.pembayaran ||
                      item.pembayaran.status !== 'lunas'
                    " @click="bayarCash(item)" :disabled="processingId === item.id
                      "
                      class="rounded-lg bg-[#4682A9] px-4 py-2 text-xs font-medium text-white transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-50">
                      {{
                        processingId === item.id
                          ? "Memproses..."
                          : "Bayar Cash"
                      }}
                    </button>

                    <span v-else class="text-xs font-medium text-green-600">
                      Sudah Dibayar
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
</template>