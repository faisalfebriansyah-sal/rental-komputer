<script setup>
import { ref, onMounted } from "vue";
import AdminSidebar from "../../components/admin/AdminSidebar.vue";
import AdminHeader from "../../components/admin/AdminHeader.vue";

const perangkat = ref([]);
const loading = ref(true);
const errorMessage = ref("");

const showModal = ref(false);

const jenisPerangkat = ref([]);

const form = ref({
  jenis_id: "",
  name: "",
  status: "tersedia",
});

const showEditModal = ref(false);
const editingId = ref(null);

const editForm = ref({
  jenis_id: "",
  name: "",
  status: "",
});

const editError = ref("");
const updating = ref(false);

const formError = ref("");
const saving = ref(false);

const getPerangkat = async () => {
  loading.value = true;
  errorMessage.value = "";

  try {
    const token = localStorage.getItem("token");

    const response = await fetch(
      "http://127.0.0.1:8000/api/perangkat",
      {
        method: "GET",
        headers: {
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
        },
      }
    );

    const result = await response.json();

    if (!response.ok) {
      errorMessage.value =
        result.message || "Gagal mengambil data perangkat.";
      return;
    }

    perangkat.value = result.data || [];
  } catch (error) {
    console.error(error);
    errorMessage.value = "Tidak dapat terhubung ke server.";
  } finally {
    loading.value = false;
  }
};

const getJenisPerangkat = async () => {
  try {
    const token = localStorage.getItem("token");

    const response = await fetch(
      "http://127.0.0.1:8000/api/jenis_perangkat",
      {
        method: "GET",
        headers: {
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
        },
      }
    );

    const result = await response.json();

    if (!response.ok) {
      console.error(result.message);
      return;
    }

    jenisPerangkat.value = result.data || [];
  } catch (error) {
    console.error(error);
  }
};

const openModal = () => {
  form.value = {
    jenis_id: "",
    name: "",
    status: "tersedia",
  };

  formError.value = "";
  showModal.value = true;
};


const createPerangkat = async () => {
  formError.value = "";

  if (!form.value.jenis_id || !form.value.name || !form.value.status) {
    formError.value = "Semua field wajib diisi.";
    return;
  }

  saving.value = true;

  try {
    const token = localStorage.getItem("token");

    const response = await fetch(
      "http://127.0.0.1:8000/api/perangkat",
      {
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({
          jenis_id: form.value.jenis_id,
          name: form.value.name,
          status: form.value.status,
        }),
      }
    );

    const result = await response.json();

    if (!response.ok) {
      formError.value =
        result.message || "Gagal menambahkan perangkat.";
      return;
    }

    showModal.value = false;

    await getPerangkat();
  } catch (error) {
    console.error(error);
    formError.value = "Tidak dapat terhubung ke server.";
  } finally {
    saving.value = false;
  }
};

const openEditModal = (item) => {
  editingId.value = item.id;

  editForm.value = {
    jenis_id: item.jenis_id,
    name: item.name,
    status: item.status,
  };

  editError.value = "";
  showEditModal.value = true;
};

const updatePerangkat = async () => {
  editError.value = "";

  if (
    !editForm.value.jenis_id ||
    !editForm.value.name ||
    !editForm.value.status
  ) {
    editError.value = "Semua field wajib diisi.";
    return;
  }

  updating.value = true;

  try {
    const token = localStorage.getItem("token");

    const response = await fetch(
      `http://127.0.0.1:8000/api/perangkat/${editingId.value}`,
      {
        method: "PUT",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({
          jenis_id: editForm.value.jenis_id,
          name: editForm.value.name,
          status: editForm.value.status,
        }),
      }
    );

    const result = await response.json();

    if (!response.ok) {
      editError.value =
        result.message || "Gagal memperbarui perangkat.";
      return;
    }

    showEditModal.value = false;

    await getPerangkat();
  } catch (error) {
    console.error(error);
    editError.value = "Tidak dapat terhubung ke server.";
  } finally {
    updating.value = false;
  }
};

const deletePerangkat = async (item) => {
  const yakin = confirm(
    `Hapus perangkat "${item.name}"?`
  );

  if (!yakin) {
    return;
  }

  try {
    const token = localStorage.getItem("token");

    const response = await fetch(
      `http://127.0.0.1:8000/api/perangkat/${item.id}`,
      {
        method: "DELETE",
        headers: {
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
        },
      }
    );

    const result = await response.json();

    if (!response.ok) {
      alert(result.message || "Gagal menghapus perangkat.");
      return;
    }

    await getPerangkat();
  } catch (error) {
    console.error(error);
    alert("Tidak dapat terhubung ke server.");
  }
};

onMounted(() => {
  getPerangkat();
  getJenisPerangkat();
});
</script>
<template>
  <div class="min-h-screen bg-[#F6F4EB]">

    <!-- Sidebar -->
    <AdminSidebar />

    <!-- Main -->
    <main class="lg:ml-64">

      <!-- Header -->
      <AdminHeader title="Perangkat" />

      <!-- Content -->
      <div class="p-6 sm:p-8">

        <!-- Title -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

          <div>
            <h2 class="text-xl font-semibold text-gray-800">
              Daftar Perangkat
            </h2>

            <p class="mt-1 text-sm text-gray-500">
              Kelola seluruh PC dan PlayStation rental.
            </p>
          </div>

          <button type="button" @click="openModal"
            class="rounded-xl bg-[#4682A9] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-[#4682A9]/20 transition hover:bg-[#749BC2]">
            + Tambah Perangkat
          </button>

        </div>

        <!-- Filter -->
        <div class="mt-8 flex flex-wrap gap-3">

          <button class="rounded-xl bg-[#4682A9] px-5 py-2.5 text-sm font-medium text-white">
            Semua
          </button>

          <button
            class="rounded-xl bg-white px-5 py-2.5 text-sm font-medium text-gray-600 shadow-sm transition hover:bg-gray-50">
            PC
          </button>

          <button
            class="rounded-xl bg-white px-5 py-2.5 text-sm font-medium text-gray-600 shadow-sm transition hover:bg-gray-50">
            PlayStation
          </button>

        </div>

        <!-- Device Cards -->

        <!-- Loading -->
        <div v-if="loading" class="mt-6 text-center text-sm text-gray-500">
          Memuat data perangkat...
        </div>

        <!-- Error -->
        <div v-else-if="errorMessage" class="mt-6 rounded-xl bg-red-50 p-4 text-sm text-red-600">
          {{ errorMessage }}
        </div>

        <!-- Data perangkat -->
        <div v-else-if="perangkat.length > 0" class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
          <div v-for="item in perangkat" :key="item.id"
            class="rounded-2xl bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <div class="flex items-start justify-between">

              <!-- Info perangkat -->
              <div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#91C8E4]/25 text-2xl">
                  🖥️
                </div>

                <h3 class="mt-4 font-semibold text-gray-800">
                  {{ item.name }}
                </h3>

                <p class="mt-1  text-xs text-gray-400">
                  {{ item.jenis_perangkat?.name || "Jenis tidak tersedia" }}
                </p>

                <p class="mt-3  text-sm text-gray-600">
                  Rp
                  {{
                    Number(
                      item.jenis_perangkat?.harga_per_jam || 0
                    ).toLocaleString("id-ID")
                  }}
                  /jam
                </p>
              </div>

              <!-- Status -->
              <span class="rounded-full px-3 py-1 text-xs font-medium capitalize" :class="{
                'bg-green-100 text-green-700':
                  item.status === 'tersedia',

                'bg-blue-100 text-blue-700':
                  item.status === 'digunakan' || item.status === 'aktif',

                'bg-yellow-100 text-yellow-700':
                  item.status === 'maintenance',

                'bg-gray-100 text-gray-700':
                  !['tersedia', 'digunakan', 'aktif', 'maintenance'].includes(item.status)
              }">
                {{ item.status }}

              </span>

              <div class="mt-5 flex gap-2">
                <button type="button" @click="openEditModal(item)"
                  class="flex-1 rounded-xl bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-100">
                  Edit
                </button>

                <button type="button" @click="deletePerangkat(item)"
                  class="flex-1 rounded-xl bg-red-50 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100">
                  Hapus
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Data kosong -->
        <div v-else class="mt-6 rounded-xl bg-white p-8 text-center text-sm text-gray-500">
          Belum ada perangkat.
        </div>

      </div>
    </main>

    <!-- Modal Tambah Perangkat -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
      <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

        <!-- Header Modal -->
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-gray-800">
              Tambah Perangkat
            </h3>

            <p class="mt-1 text-sm text-gray-500">
              Tambahkan perangkat PC baru.
            </p>
          </div>

          <button type="button" @click="showModal = false" class="text-xl text-gray-400 hover:text-gray-600">
            ×
          </button>
        </div>

        <!-- Error -->
        <div v-if="formError" class="mt-4 rounded-xl bg-red-50 p-3 text-sm text-red-600">
          {{ formError }}
        </div>

        <!-- Form -->
        <div class="mt-6 space-y-4">

          <!-- Nama -->
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Nama Perangkat
            </label>

            <input v-model="form.name" type="text" placeholder="Contoh: PC 06"
              class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none transition focus:border-[#4682A9] focus:ring-2 focus:ring-[#4682A9]/20" />
          </div>

          <!-- Jenis -->
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Jenis Perangkat
            </label>

            <select v-model="form.jenis_id"
              class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#4682A9] focus:ring-2 focus:ring-[#4682A9]/20">
              <option value="" disabled>
                Pilih jenis perangkat
              </option>

              <option v-for="jenis in jenisPerangkat" :key="jenis.id" :value="jenis.id">
                {{ jenis.name }} -
                Rp {{ Number(jenis.harga_per_jam).toLocaleString("id-ID") }}/jam
              </option>
            </select>
          </div>

          <!-- Status -->
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Status
            </label>

            <select v-model="form.status"
              class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#4682A9] focus:ring-2 focus:ring-[#4682A9]/20">
              <option value="tersedia">
                Tersedia
              </option>

              <option value="maintenance">
                Maintenance
              </option>
            </select>
          </div>

        </div>

        <!-- Buttons -->
        <div class="mt-6 flex justify-end gap-3">

          <button type="button" @click="showModal = false"
            class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-200">
            Batal
          </button>

          <button type="button" @click="createPerangkat" :disabled="saving"
            class="rounded-xl bg-[#4682A9] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#749BC2] disabled:cursor-not-allowed disabled:opacity-50">
            {{ saving ? "Menyimpan..." : "Simpan" }}
          </button>

        </div>

      </div>
    </div>

    <!-- Modal Edit Perangkat -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
      <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

        <!-- Header -->
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-gray-800">
              Edit Perangkat
            </h3>

            <p class="mt-1 text-sm text-gray-500">
              Perbarui informasi perangkat.
            </p>
          </div>

          <button type="button" @click="showEditModal = false" class="text-xl text-gray-400 hover:text-gray-600">
            ×
          </button>
        </div>

        <!-- Error -->
        <div v-if="editError" class="mt-4 rounded-xl bg-red-50 p-3 text-sm text-red-600">
          {{ editError }}
        </div>

        <!-- Form -->
        <div class="mt-6 space-y-4">

          <!-- Nama -->
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Nama Perangkat
            </label>

            <input v-model="editForm.name" type="text"
              class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none transition focus:border-[#4682A9] focus:ring-2 focus:ring-[#4682A9]/20" />
          </div>

          <!-- Jenis -->
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Jenis Perangkat
            </label>

            <select v-model="editForm.jenis_id"
              class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#4682A9] focus:ring-2 focus:ring-[#4682A9]/20">
              <option value="" disabled>
                Pilih jenis perangkat
              </option>

              <option v-for="jenis in jenisPerangkat" :key="jenis.id" :value="jenis.id">
                {{ jenis.name }} -
                Rp {{ Number(jenis.harga_per_jam).toLocaleString("id-ID") }}/jam
              </option>
            </select>
          </div>

          <!-- Status -->
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Status
            </label>

            <select v-model="editForm.status"
              class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#4682A9] focus:ring-2 focus:ring-[#4682A9]/20">
              <option value="tersedia">
                Tersedia
              </option>

              <option value="maintenance">
                Maintenance
              </option>

              <option value="digunakan">
                Digunakan
              </option>
            </select>
          </div>

        </div>

        <!-- Buttons -->
        <div class="mt-6 flex justify-end gap-3">

          <button type="button" @click="showEditModal = false"
            class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-200">
            Batal
          </button>

          <button type="button" @click="updatePerangkat" :disabled="updating"
            class="rounded-xl bg-[#4682A9] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#749BC2] disabled:cursor-not-allowed disabled:opacity-50">
            {{ updating ? "Menyimpan..." : "Simpan Perubahan" }}
          </button>

        </div>

      </div>
    </div>
  </div>
</template>