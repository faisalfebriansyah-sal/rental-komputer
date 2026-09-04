<script setup>
import { ref, computed, onMounted } from "vue";
import AdminSidebar from "../../components/admin/AdminSidebar.vue";
import AdminHeader from "../../components/admin/AdminHeader.vue";

import {
  Users,
  Plus,
  Search,
  MoreVertical,
  UserRound,
} from "lucide-vue-next";

const customers = ref([]);
const loading = ref(false);
const error = ref("");

//---------ADD---------
const showModal = ref(false);
const form = ref({
  name: "",
  no_hp: "",
});

//---------EDIT--------

const showEditModal = ref(false);
const editingCustomer = ref(null);
const updating = ref(false);
const editError = ref("");

const editForm = ref({
  name: "",
  no_hp: "",
});

//----Delete----
const showDeleteModal = ref(false);
const deletingCustomer = ref(null);
const deleting = ref(false);
const deleteError = ref("");


const saving = ref(false);
const formError = ref("");
const search = ref("");

const getCustomers = async () => {
  loading.value = true;
  error.value = "";

  try {
    const token = localStorage.getItem("token");

    const response = await fetch(
      "http://127.0.0.1:8000/api/pelanggan",
      {
        method: "GET",
        headers: {
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
        },
      }
    );

    const result = await response.json();

    console.log("Response pelanggan:", result);

    if (!response.ok) {
      throw new Error(
        result.message || "Gagal mengambil data pelanggan"
      );
    }

    customers.value = result.data || [];

  } catch (err) {
    console.error("Error pelanggan:", err);
    error.value = err.message;
  } finally {
    loading.value = false;
  }
};


const filteredCustomers = computed(() => {
  const keyword = search.value.toLowerCase().trim();

  if (!keyword) {
    return customers.value;
  }

  return customers.value.filter((customer) => {
    const name = customer.name?.toLowerCase() || "";
    const noHp = customer.no_hp || "";

    return (
      name.includes(keyword) ||
      noHp.includes(keyword)
    );
  });
});

//-------
//fungsi add
//-----

const addCustomer = async () => {
  saving.value = true
  formError.value = ""

  try {
    const token = localStorage.getItem("token")

    const response = await fetch(
      "http://127.0.0.1:8000/api/pelanggan",
      {
        method: "POST",
        headers: {
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json"
        },

        body: JSON.stringify({
          name: form.value.name,
          no_hp: form.value.no_hp,
        }),
      }
    );

    const result = await response.json();

    console.log("Response tambah pelanggan", result);

    if (!response.ok) {
      throw new Error(
        result.message || "Gagal tambah pelanggan"
      )
    }

    //tutup modal
    showModal.value = false

    //reset form
    form.value.name = ""
    form.value.no_hp = ""

    //refresh data
    await getCustomers()

    alert("Pelanggan berhasil ditambahkan")

  } catch (err) {
    formError.value = err.message || "Terjadi kesalahan"
  } finally {
    saving.value = false
  }
}

//--------------FUNGSI modal EDIT--------

const openEditModal = (customer) => {
  editingCustomer.value = customer

  editForm.value = {
    name: customer.name,
    no_hp: customer.no_hp,
  };

  editError.value = "";
  showEditModal.value = true;
}

//---------FUNGSI UPDATE--------
const updateCustomer = async () => {
  updating.value = true;
  editError.value = "";

  try {
    const token = localStorage.getItem("token");

    const response = await fetch(
      `http://127.0.0.1:8000/api/pelanggan/${editingCustomer.value.id}`,
      {
        method: "PUT",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({
          name: editForm.value.name,
          no_hp: editForm.value.no_hp,
        }),
      }
    );

    const result = await response.json();

    console.log("Response edit pelanggan:", result);

    if (!response.ok) {
      throw new Error(
        result.message || "Gagal mengedit pelanggan"
      );
    }

    showEditModal.value = false;
    editingCustomer.value = null;

    await getCustomers();

  } catch (err) {
    console.error("Error edit pelanggan:", err);
    editError.value = err.message;
  } finally {
    updating.value = false;
  }
};

//---- modal delete----
const openDeleteModal = (customer) => {
  deletingCustomer.value = customer;
  deleteError.value = ""
  showDeleteModal.value = true
}

//---- fungsi delete ----
const deleteCustomer = async () => {
  deleting.value = true;
  deleteError.value = "";

  try {
    const token = localStorage.getItem("token");

    const response = await fetch(
      `http://127.0.0.1:8000/api/pelanggan/${deletingCustomer.value.id}`,
      {
        method: "DELETE",
        headers: {
          Accept: "application/json",
          Authorization: `Bearer ${token}`,
        },
      }
    );

    const result = await response.json();

    console.log("Response hapus pelanggan:", result);

    if (!response.ok) {
      throw new Error(
        result.message || "Gagal menghapus pelanggan"
      );
    }

    showDeleteModal.value = false;
    showEditModal.value = false;

    deletingCustomer.value = null;
    editingCustomer.value = null;
    await getCustomers();

  } catch (err) {
    console.error("Error hapus pelanggan:", err);
    deleteError.value = err.message;
  } finally {
    deleting.value = false;
  }
};


onMounted(() => {
  getCustomers();
});
</script>

<template>
  <div class="min-h-screen bg-[#F6F4EB]">
    <AdminSidebar />

    <div class="ml-64">
      <AdminHeader />

      <main class="p-8">

        <!-- Header -->
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-slate-800">
              Pelanggan
            </h1>

            <p class="mt-1 text-sm text-slate-500">
              Kelola data pelanggan rental
            </p>
          </div>

          <button @click="showModal = true"
            class="flex items-center gap-2 rounded-xl bg-[#4682A9] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#3d7599]">
            <Plus :size="18" />
            Tambah Pelanggan
          </button>
        </div>

        <!-- Statistics -->
        <div class="mt-8 grid grid-cols-3 gap-5">

          <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
              <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#91C8E4]/30 text-[#4682A9]">
                <Users :size="21" />
              </div>

              <div>
                <p class="text-sm text-slate-500">
                  Total Pelanggan
                </p>

                <p class="mt-1 text-2xl font-bold text-slate-800">
                  {{ customers.length }}
                </p>
              </div>
            </div>
          </div>

          <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
              <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-600">
                <UserRound :size="21" />
              </div>

              <div>
                <p class="text-sm text-slate-500">
                  Pelanggan Hari Ini
                </p>

                <p class="mt-1 text-2xl font-bold text-slate-800">
                  24
                </p>
              </div>
            </div>
          </div>

          <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="flex items-center gap-4">
              <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-500">
                <Users :size="21" />
              </div>

              <div>
                <p class="text-sm text-slate-500">
                  Sedang Rental
                </p>

                <p class="mt-1 text-2xl font-bold text-slate-800">
                  8
                </p>
              </div>
            </div>
          </div>

        </div>

        <!-- Table -->
        <div class="mt-8 rounded-2xl bg-white shadow-sm">

          <!-- Search -->
          <div class="flex items-center justify-between border-b border-slate-100 p-5">
            <div>
              <h2 class="font-semibold text-slate-800">
                Daftar Pelanggan
              </h2>

              <p class="mt-1 text-xs text-slate-400">
                Data pelanggan yang terdaftar
              </p>
            </div>

            <div class="relative w-72">
              <Search :size="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />

              <input v-model="search" type="text" placeholder="Cari nama atau nomor HP..."
                class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-4 text-sm outline-none transition focus:border-[#4682A9]" />
            </div>
          </div>

          <!-- Table -->
          <div class="overflow-hidden">
            <table class="w-full text-left">

              <thead class="bg-slate-50">
                <tr>
                  <th class="px-6 py-4 text-xs font-semibold uppercase text-slate-500">
                    Pelanggan
                  </th>

                  <th class="px-6 py-4 text-xs font-semibold uppercase text-slate-500">
                    No. HP
                  </th>

                  <th class="px-6 py-4 text-xs font-semibold uppercase text-slate-500">
                    Terdaftar
                  </th>

                  <th class="px-6 py-4 text-right text-xs font-semibold uppercase text-slate-500">
                    Aksi
                  </th>
                </tr>
              </thead>

              <tbody>


                <!-- Loading (Skeleton) -->
                <template v-if="loading">
                  <tr v-for="i in 5" :key="i" class="animate-pulse border-t border-slate-100">
                    <!-- Kolom Nama/Profil -->
                    <td class="px-6 py-4">
                      <div class="flex items-center gap-3">
                        <!-- Lingkaran Avatar -->
                        <div class="h-10 w-10 rounded-full bg-slate-200"></div>
                        <div class="space-y-2">
                          <!-- Text Nama -->
                          <div class="h-4 w-32 rounded bg-slate-200"></div>
                          <!-- Text ID -->
                          <div class="h-3 w-16 rounded bg-slate-200"></div>
                        </div>
                      </div>
                    </td>

                    <!-- Kolom No. HP -->
                    <td class="px-6 py-4">
                      <div class="h-4 w-28 rounded bg-slate-200"></div>
                    </td>

                    <!-- Kolom Terdaftar (Badge) -->
                    <td class="px-6 py-4">
                      <div class="h-7 w-24 rounded-lg bg-slate-200"></div>
                    </td>

                    <!-- Kolom Aksi -->
                    <td class="px-6 py-4 text-right">
                      <div class="ml-auto h-8 w-8 rounded-lg bg-slate-200"></div>
                    </td>
                  </tr>
                </template>

                <!-- Error -->
                <tr v-else-if="error">
                  <td colspan="4" class="px-6 py-12 text-center">
                    <p class="text-sm font-medium text-red-500">
                      {{ error }}
                    </p>

                    <button @click="getCustomers"
                      class="mt-3 rounded-lg bg-[#4682A9] px-4 py-2 text-xs font-semibold text-white hover:bg-[#3d7599]">
                      Coba Lagi
                    </button>
                  </td>
                </tr>

                <!-- Empty Search -->
                <tr v-else-if="filteredCustomers.length === 0">
                  <td colspan="4" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                      <Search :size="32" class="text-slate-300" />

                      <p class="mt-3 text-sm font-medium text-slate-600">
                        Pelanggan tidak ditemukan
                      </p>

                      <p class="mt-1 text-xs text-slate-400">
                        Coba gunakan nama atau nomor HP lain.
                      </p>
                    </div>
                  </td>
                </tr>
                <tr v-for="customer in filteredCustomers" :key="customer.id"
                  class="border-t border-slate-100 transition hover:bg-slate-50">
                  <!-- Name -->
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-[#91C8E4]/30 text-sm font-bold text-[#4682A9]">
                        {{ customer.name.charAt(0).toUpperCase() }}
                      </div>

                      <div>
                        <p class="text-sm font-semibold text-slate-800">
                          {{ customer.name }}
                        </p>

                        <p class="text-xs text-slate-400">
                          ID #{{ customer.id }}
                        </p>
                      </div>
                    </div>
                  </td>

                  <!-- Phone -->
                  <td class="px-6 py-4 text-sm text-slate-600">
                    {{ customer.no_hp }}
                  </td>

                  <!-- Rental -->
                  <td class="px-6 py-4">
                    <span class="rounded-lg bg-[#91C8E4]/20 px-3 py-1.5 text-xs font-semibold text-[#4682A9]">
                      {{ new Date(customer.created_at).toLocaleDateString("id-ID", {
                        day: "2-digit", month: "2-digit",
                      year: "numeric"})
                      }}
                    </span>
                  </td>

                  <!-- Action -->
                  <td class="px-6 py-4 text-right ">
                    <button @click="openEditModal(customer)"
                      class="cursor-pointer rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                      <MoreVertical :size="18" />
                    </button>
                  </td>
                </tr>
              </tbody>

            </table>
          </div>

          <!-- Footer -->
          <div class="flex items-center justify-between border-t border-slate-100 px-6 py-4">
            <p class="text-xs text-slate-400">
              Menampilkan {{ filteredCustomers.length }} dari {{ customers.length }} pelanggan
            </p>

            <div class="flex gap-2">
              <button class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs text-slate-500">
                Sebelumnya
              </button>

              <button class="rounded-lg bg-[#4682A9] px-3 py-1.5 text-xs text-white">
                1
              </button>

              <button class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs text-slate-500">
                Berikutnya
              </button>
            </div>
          </div>

        </div>

      </main>
    </div>
  </div>

  <!-- Modal Tambah Pelanggan -->
  <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 px-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-lg font-bold text-slate-800">
            Tambah Pelanggan
          </h2>

          <p class="mt-1 text-sm text-slate-400">
            Masukkan data pelanggan baru
          </p>
        </div>

        <button @click="showModal = false"
          class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
          ✕
        </button>
      </div>

      <!-- Form -->
      <form @submit.prevent="addCustomer" class="mt-6 space-y-4">

        <!-- Nama -->
        <div>
          <label class="text-sm font-medium text-slate-700">
            Nama Pelanggan
          </label>

          <input v-model="form.name" type="text" placeholder="Masukkan nama pelanggan" required
            class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-[#4682A9] focus:ring-2 focus:ring-[#91C8E4]/30" />
        </div>

        <!-- No HP -->
        <div>
          <label class="text-sm font-medium text-slate-700">
            Nomor HP
          </label>

          <input v-model="form.no_hp" type="tel" placeholder="Contoh: 08xxxxxxx" required
            class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-[#4682A9] focus:ring-2 focus:ring-[#91C8E4]/30" />
        </div>

        <!-- Error -->
        <div v-if="formError" class="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-600">
          {{ formError }}
        </div>

        <!-- Button -->
        <div class="flex justify-end gap-3 pt-2">

          <button type="button" @click="showModal = false"
            class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
            Batal
          </button>

          <button type="submit" :disabled="saving"
            class="flex items-center gap-2 rounded-xl bg-[#4682A9] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#3d7599] disabled:cursor-not-allowed disabled:opacity-60">

            <span v-if="saving"
              class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>

            {{ saving ? "Menyimpan..." : "Simpan" }}

          </button>

        </div>

      </form>
    </div>
  </div>

  <!-- Modal Edit Pelanggan -->
  <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 px-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-lg font-bold text-slate-800">
            Edit Pelanggan
          </h2>

          <p class="mt-1 text-sm text-slate-400">
            Perbarui data pelanggan
          </p>
        </div>

        <button @click="showEditModal = false"
          class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
          ✕
        </button>
      </div>

      <form @submit.prevent="updateCustomer" class="mt-6 space-y-4">

        <!-- Nama -->
        <div>
          <label class="text-sm font-medium text-slate-700">
            Nama Pelanggan
          </label>

          <input v-model="editForm.name" type="text" required
            class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-[#4682A9] focus:ring-2 focus:ring-[#91C8E4]/30" />
        </div>

        <!-- No HP -->
        <div>
          <label class="text-sm font-medium text-slate-700">
            Nomor HP
          </label>

          <input v-model="editForm.no_hp" type="tel" required
            class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-[#4682A9] focus:ring-2 focus:ring-[#91C8E4]/30" />
        </div>

        <!-- Error -->
        <div v-if="editError" class="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-600">
          {{ editError }}
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-3 pt-2">

          <button type="button" @click="openDeleteModal(editingCustomer)"
            class="rounded-xl bg-red-50 px-5 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-100">
            Hapus
          </button>

          <button type="button" @click="showEditModal = false"
            class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
            Batal
          </button>

          <button type="submit" :disabled="updating"
            class="flex items-center gap-2 rounded-xl bg-[#4682A9] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#3d7599] disabled:cursor-not-allowed disabled:opacity-60">

            <span v-if="updating"
              class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>

            {{ updating ? "Menyimpan..." : "Simpan Perubahan" }}

          </button>

        </div>

      </form>
    </div>
  </div>

  <!-- delete modal -->
  <div v-if="showDeleteModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/40 px-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">

      <h2 class="text-lg font-bold text-slate-800">
        Hapus Pelanggan?
      </h2>

      <p class="mt-2 text-sm leading-6 text-slate-500">
        Data pelanggan
        <span class="font-semibold text-slate-700">
          {{ deletingCustomer?.name }}
        </span>
        akan dihapus secara permanen.
      </p>

      <div v-if="deleteError" class="mt-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-600">
        {{ deleteError }}
      </div>

      <div class="mt-6 flex justify-end gap-3">

        <button type="button" @click="showDeleteModal = false"
          class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">
          Batal
        </button>

        <button type="button" @click="deleteCustomer" :disabled="deleting"
          class="flex items-center gap-2 rounded-xl bg-red-500 px-5 py-3 text-sm font-semibold text-white hover:bg-red-600 disabled:cursor-not-allowed disabled:opacity-60">
          <span v-if="deleting"
            class="h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>

          {{ deleting ? "Menghapus..." : "Ya, Hapus" }}
        </button>

      </div>
    </div>
  </div>
</template>