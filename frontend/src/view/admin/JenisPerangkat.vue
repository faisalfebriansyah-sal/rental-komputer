<script setup>
import { ref, onMounted } from "vue";
import AdminSidebar from "../../components/admin/AdminSidebar.vue";
import AdminHeader from "../../components/admin/AdminHeader.vue";

const jenisPerangkat = ref([]);
const loading = ref(true);
const errorMessage = ref("");

const showModal = ref(false);
const editingJenis = ref(null);

const form = ref({
    name: "",
    harga_per_jam: "",
});

const formError = ref("");
const saving = ref(false);

const getJenisPerangkat = async () => {
    loading.value = true;
    errorMessage.value = "";

    try {
        const token = localStorage.getItem("token");

        const response = await fetch(
            "http://127.0.0.1:8000/api/jenis_perangkat",
            {
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${token}`,
                },
            }
        );

        const result = await response.json();

        if (!response.ok) {
            errorMessage.value =
                result.message || "Gagal mengambil data jenis perangkat.";
            return;
        }

        jenisPerangkat.value = result.data || [];
    } catch (error) {
        console.error(error);
        errorMessage.value = "Tidak dapat terhubung ke server.";
    } finally {
        loading.value = false;
    }
};

const formatRupiah = (value) => {
    return Number(value || 0).toLocaleString("id-ID");
};

const openTambahModal = () => {
    editingJenis.value = null;

    form.value = {
        name: "",
        harga_per_jam: "",
    };

    formError.value = "";
    showModal.value = true;
};

const openEditModal = (item) => {
    editingJenis.value = item;

    form.value = {
        name: item.name,
        harga_per_jam: item.harga_per_jam,
    };

    formError.value = "";
    showModal.value = true;
};

const saveJenisPerangkat = async () => {
    formError.value = "";

    if (!form.value.name || !form.value.harga_per_jam) {
        formError.value = "Nama jenis dan harga per jam wajib diisi.";
        return;
    }

    if (Number(form.value.harga_per_jam) < 0) {
        formError.value = "Harga tidak boleh kurang dari 0.";
        return;
    }

    saving.value = true;

    try {
        const token = localStorage.getItem("token");

        const isEdit = editingJenis.value !== null;

        const url = isEdit
            ? `http://127.0.0.1:8000/api/jenis_perangkat/${editingJenis.value.id}`
            : "http://127.0.0.1:8000/api/jenis_perangkat";

        const response = await fetch(url, {
            method: isEdit ? "PUT" : "POST",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`,
            },
            body: JSON.stringify({
                name: form.value.name,
                harga_per_jam: Number(form.value.harga_per_jam),
            }),
        });

        const result = await response.json();

        if (!response.ok) {
            formError.value =
                result.message || "Gagal menyimpan jenis perangkat.";
            return;
        }

        showModal.value = false;

        await getJenisPerangkat();
    } catch (error) {
        console.error(error);
        formError.value = "Tidak dapat terhubung ke server.";
    } finally {
        saving.value = false;
    }
};

const deleteJenisPerangkat = async (id) => {
    if (!confirm("Yakin ingin menghapus jenis perangkat ini?")) {
        return;
    }

    try {
        const token = localStorage.getItem("token");

        const response = await fetch(
            `http://127.0.0.1:8000/api/jenis_perangkat/${id}`,
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
            alert(
                result.message ||
                "Jenis perangkat tidak dapat dihapus."
            );
            return;
        }

        await getJenisPerangkat();
    } catch (error) {
        console.error(error);
        alert("Tidak dapat terhubung ke server.");
    }
};

onMounted(() => {
    getJenisPerangkat();
});
</script>

<template>
    <div class="min-h-screen bg-[#f8f6ee]">
        <AdminSidebar />

        <div class="ml-64">
            <AdminHeader />

            <main class="p-8">

                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">
                            Jenis Perangkat
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Kelola jenis rental dan harga per jam.
                        </p>
                    </div>

                    <button @click="openTambahModal"
                        class="rounded-xl bg-[#4682A9] px-5 py-3 text-sm font-semibold text-white shadow-md hover:bg-[#3d7395]">
                        + Tambah Jenis
                    </button>
                </div>

                <div v-if="errorMessage" class="mt-6 rounded-xl bg-red-50 px-5 py-4 text-sm text-red-600">
                    {{ errorMessage }}
                </div>

                <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm">

                    <div v-if="loading" class="animate-pulse">
                        <div class="border-b border-gray-100 px-6 py-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="h-4 w-16 rounded bg-gray-200"></div>
                                <div class="h-4 w-24 rounded bg-gray-200"></div>
                                <div class="ml-auto h-4 w-12 rounded bg-gray-200"></div>
                            </div>
                        </div>

                        <div v-for="index in 5" :key="index" class="border-b border-gray-100 px-6 py-5 last:border-0">
                            <div class="grid grid-cols-3 items-center gap-4">
                                <div class="h-4 w-32 rounded bg-gray-200"></div>
                                <div class="h-4 w-24 rounded bg-gray-200"></div>
                                <div class="ml-auto flex gap-4">
                                    <div class="h-4 w-10 rounded bg-gray-200"></div>
                                    <div class="h-4 w-12 rounded bg-gray-200"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else-if="jenisPerangkat.length === 0" class="px-6 py-12 text-center text-sm text-gray-400">
                        Belum ada jenis perangkat.
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-100 text-left text-sm text-gray-400">
                                    <th class="px-6 py-4">
                                        Jenis
                                    </th>

                                    <th class="px-6 py-4">
                                        Harga / Jam
                                    </th>

                                    <th class="px-6 py-4 text-right">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="item in jenisPerangkat" :key="item.id"
                                    class="border-b border-gray-100 last:border-0">
                                    <td class="px-6 py-5 font-medium text-gray-700">
                                        {{ item.name }}
                                    </td>

                                    <td class="px-6 py-5 text-gray-500">
                                        Rp{{ formatRupiah(item.harga_per_jam) }}
                                        /jam
                                    </td>

                                    <td class="px-6 py-5 text-right">
                                        <button @click="openEditModal(item)"
                                            class="mr-4 text-sm font-medium text-blue-600 hover:underline">
                                            Edit
                                        </button>

                                        <button @click="deleteJenisPerangkat(item.id)"
                                            class="text-sm font-medium text-red-500 hover:underline">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

            </main>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

                <h2 class="text-xl font-bold text-slate-800">
                    {{
                        editingJenis
                            ? "Edit Jenis Perangkat"
                            : "Tambah Jenis Perangkat"
                    }}
                </h2>

                <p class="mt-1 text-sm text-gray-400">
                    Masukkan nama jenis dan harga rental per jam.
                </p>

                <div v-if="formError" class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-600">
                    {{ formError }}
                </div>

                <div class="mt-6 space-y-4">

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Nama Jenis
                        </label>

                        <input v-model="form.name" type="text" placeholder="Contoh: VIP"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#4682A9]" />
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Harga Per Jam
                        </label>

                        <input v-model="form.harga_per_jam" type="number" min="0" placeholder="Contoh: 10000"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#4682A9]" />
                    </div>

                </div>

                <div class="mt-6 flex justify-end gap-3">

                    <button @click="showModal = false"
                        class="rounded-xl px-5 py-3 text-sm font-medium text-gray-500 hover:bg-gray-100">
                        Batal
                    </button>

                    <button @click="saveJenisPerangkat" :disabled="saving"
                        class="rounded-xl bg-[#4682A9] px-5 py-3 text-sm font-semibold text-white disabled:opacity-50">
                        {{ saving ? "Menyimpan..." : "Simpan" }}
                    </button>

                </div>

            </div>
        </div>
    </div>
</template>