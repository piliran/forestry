<!-- component -->
<template>
    <AppLayout title="Suspect Details">
        <div>
            <div class="-mt-6 inline-block bg-transparent">
                <Breadcrumb :home="home" :model="breadCumbItems">
                    <template #item="{ item, props }">
                        <Link
                            v-if="item.route"
                            :href="item.route"
                            preserve-scroll
                            v-bind="props.action"
                        >
                            <span :class="[item.icon, 'text-color']" />
                            <span class="text-primary font-semibold">{{
                                item.label
                            }}</span>
                        </Link>
                        <a
                            v-else
                            :href="item.url"
                            :target="item.target"
                            v-bind="props.action"
                        >
                            <span
                                class="text-surface-700 dark:text-surface-0"
                                >{{ item.label }}</span
                            >
                        </a>
                    </template>
                </Breadcrumb>
            </div>
            <div class="h-full">
                <div
                    class="my-4 flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4"
                >
                    <!-- Suspect Personal Information Card -->
                    <div class="flex flex-col lg:w-1/2 gap:8">
                        <div class="flex-1 bg-white rounded-lg shadow-xl p-8">
                            <h4 class="text-xl text-gray-900 font-bold">
                                Suspect Personal Information
                            </h4>

                            <div class="flex flex-col items-center">
                                <img
                                    :src="suspect.suspect_photo_path"
                                    class="mt-5 h-40 w-40 border-4 border-white rounded-full"
                                />
                            </div>

                            <ul class="mt-2 text-gray-700">
                                <li class="flex border-y py-2">
                                    <span class="font-bold w-24"
                                        >Full name:</span
                                    >
                                    <span class="text-gray-700">{{
                                        suspect.name || "N/A"
                                    }}</span>
                                </li>
                                <li class="flex border-b py-2">
                                    <span class="font-bold w-24"
                                        >National ID:</span
                                    >
                                    <span class="text-gray-700">{{
                                        suspect.national_id || "N/A"
                                    }}</span>
                                </li>
                                <li className="flex border-b py-2">
                                    <span className="font-bold w-24">Age:</span>
                                    <span className="text-gray-700">
                                        {{
                                            // Calculate age if `suspect.age` exists, otherwise display "N/A"
                                            (() => {
                                                if (!suspect.age) return "N/A";
                                                const birthDate = new Date(
                                                    suspect.age
                                                );
                                                const today = new Date();
                                                let age =
                                                    today.getFullYear() -
                                                    birthDate.getFullYear();
                                                const monthDifference =
                                                    today.getMonth() -
                                                    birthDate.getMonth();

                                                // Adjust if the birthday hasn't occurred yet this year
                                                if (
                                                    monthDifference < 0 ||
                                                    (monthDifference === 0 &&
                                                        today.getDate() <
                                                            birthDate.getDate())
                                                ) {
                                                    age--;
                                                }
                                                return age;
                                            })()
                                        }}
                                    </span>
                                </li>

                                <li class="flex border-b py-2">
                                    <span class="font-bold w-24"
                                        >District:</span
                                    >
                                    <span class="text-gray-700">{{
                                        suspect.district?.name || "N/A"
                                    }}</span>
                                </li>
                                <li class="flex border-b py-2">
                                    <span class="font-bold w-24">T/A:</span>
                                    <span class="text-gray-700">{{
                                        suspect.TA || "N/A"
                                    }}</span>
                                </li>
                                <li class="flex border-b py-2">
                                    <span class="font-bold w-24">Village:</span>
                                    <span class="text-gray-700">{{
                                        suspect.village || "N/A"
                                    }}</span>
                                </li>
                            </ul>
                        </div>

                        <div
                            class="flex-1 bg-white rounded-lg shadow-xl p-8 mt-4"
                        >
                            <h4 class="text-xl text-gray-900 font-bold">
                                Offenses
                            </h4>
                            <ul class="mt-4 space-y-3 text-gray-700">
                                <li
                                    v-for="(
                                        offense, index
                                    ) in suspect.offenses || []"
                                    :key="index"
                                    class="flex items-center"
                                >
                                    <span class="font-bold mr-2"
                                        >{{ index + 1 }}.</span
                                    >
                                    <span>{{ offense.name || "N/A" }}</span>
                                </li>
                            </ul>
                        </div>

                        <div
                            class="flex-1 bg-white rounded-lg shadow-xl p-8 mt-4"
                        >
                            <h4 class="text-xl text-gray-900 font-bold">
                                Activity Progress
                            </h4>
                            <div class="relative mt-6">
                                <!-- Progress Bar Background -->
                                <div class="h-1 bg-gray-300 rounded-full">
                                    <!-- Progress Bar Indicator -->
                                    <div
                                        class="h-1 bg-green-500 rounded-full"
                                        :style="{
                                            width: progressPercentage,
                                        }"
                                    ></div>
                                </div>
                                <!-- Status Labels -->
                                <div
                                    class="flex justify-between mt-4 text-sm text-gray-600"
                                >
                                    <span>Under Investigation</span>
                                    <span>Arrested</span>
                                    <span>Released</span>
                                    <span>Court Pending</span>
                                    <span>Acquitted</span>
                                    <span>Convicted</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Offenses and Additional Information -->
                    <div class="flex flex-col lg:w-1/2 space-y-4">
                        <!-- Offenses Card -->

                        <!-- Station and Operation Details Card -->
                        <div class="flex-1 bg-white rounded-lg shadow-xl p-8">
                            <h4 class="text-xl text-gray-900 font-bold">
                                Station and Operation Details
                            </h4>
                            <ul class="mt-4 space-y-3 text-gray-700">
                                <li class="flex border-b py-2">
                                    <span class="font-bold w-40">Station:</span>
                                    <span class="text-gray-700">{{
                                        props.suspect.station || "N/A"
                                    }}</span>
                                </li>
                                <li class="flex border-b py-2">
                                    <span class="font-bold w-40"
                                        >Operation:</span
                                    >
                                    <span class="text-gray-700">{{
                                        props.suspect.operations[0]?.name ||
                                        "N/A"
                                    }}</span>
                                </li>
                                <li class="flex border-b py-2">
                                    <span class="font-bold w-40"
                                        >Date Caught:</span
                                    >
                                    <span class="text-gray-700">{{
                                        props.suspect.created_at || "N/A"
                                    }}</span>
                                </li>
                                <li class="flex border-b py-2">
                                    <span class="font-bold w-40"
                                        >Arresting Officer:</span
                                    >
                                    <span class="text-gray-700">{{
                                        props.suspect.arresting_officer || "N/A"
                                    }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="flex-1 bg-white rounded-lg shadow-xl p-8">
                            <h4 class="text-xl text-gray-900 font-bold">
                                Arrest Details
                            </h4>
                            <ul class="mt-4 space-y-3 text-gray-700">
                                <li class="flex border-b py-2">
                                    <span class="font-bold w-40"
                                        >Case Number:</span
                                    >
                                    <span class="text-gray-700">"N/A"</span>
                                </li>

                                <li class="flex border-b py-2">
                                    <span class="font-bold w-40"
                                        >Date Arrested:</span
                                    >
                                    <span class="text-gray-700">"N/A"</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Court Details Card -->
                        <div class="flex-1 bg-white rounded-lg shadow-xl p-8">
                            <h4 class="text-xl text-gray-900 font-bold">
                                Court Details
                            </h4>
                            <ul class="mt-4 space-y-3 text-gray-700">
                                <li class="flex border-b py-2">
                                    <span class="font-bold w-40"
                                        >Court Name:</span
                                    >
                                    <span class="text-gray-700">"N/A"</span>
                                </li>
                                <li class="flex border-b py-2">
                                    <span class="font-bold w-40"
                                        >Case Number:</span
                                    >
                                    <span class="text-gray-700">"N/A"</span>
                                </li>
                                <li class="flex border-b py-2">
                                    <span class="font-bold w-40">Judge:</span>
                                    <span class="text-gray-700">"N/A"</span>
                                </li>
                                <li class="flex border-b py-2">
                                    <span class="font-bold w-40"
                                        >Next Hearing Date:</span
                                    >
                                    <span class="text-gray-700">"N/A"</span>
                                </li>
                                <li class="flex border-b py-2">
                                    <span class="font-bold w-40">Remarks:</span>
                                    <span class="text-gray-700">"N/A"</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-xl p-8">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xl text-gray-900 font-bold">
                            Confiscated Items
                        </h4>
                        <a href="#" title="View All">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-gray-500 hover:text-gray-700"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"
                                ></path>
                            </svg>
                        </a>
                    </div>

                    <div
                        class="grid grid-cols-2 md:grid-cols-6 gap-4 py-4 md:py-8"
                    >
                        <div
                            v-for="(file, index) in props.suspect.files || []"
                            :key="index"
                        >
                            <img
                                class="h-40 w-40 rounded-lg"
                                :src="`http://127.0.0.1:8000/storage/${file.file}`"
                                alt="Confiscated Item"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script setup>
import { ref, onMounted, computed } from "vue";
import { FilterMatchMode } from "@primevue/core/api";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";

import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Toolbar from "primevue/toolbar";
import Dialog from "primevue/dialog";
import InputIcon from "primevue/inputicon";
import InputText from "primevue/inputtext";
import IconField from "primevue/iconfield";
import Select from "primevue/select";
import Image from "primevue/image";
import ProgressSpinner from "primevue/progressspinner";
import Breadcrumb from "primevue/breadcrumb";
import { Link } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const toast = useToast();

// Reactive State Variables
const dt = ref();
const departmentDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteDepartmentDialog = ref(false);
const deleteDepartmentsDialog = ref(false);
const department = ref({});
const selectedDepartments = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    suspect: Object,
});
// console.log(props.suspect.age);

const progressMap = {
    "Under Investigation": "10%",
    Arrested: "30%",
    "Released Without Charge": "50%",
    "Court Hearing Pending": "70%",
    Acquitted: "90%",
    Convicted: "100%",
};

// Compute progress percentage based on status
const progressPercentage = computed(() => {
    return progressMap[props.suspect.status] || "0%";
});

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([
    { label: "Suspects", route: "/suspects" },
    { label: "Suspect Details" },
]);

const departments = ref(props.departments);
const users = ref(props.users);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    department.value = {};
    submitted.value = false;
    departmentDialog.value = true;
};

const hideDialog = () => {
    departmentDialog.value = false;
    submitted.value = false;
};

const saveDepartment = async () => {
    submitted.value = true;
    if (department?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (department.value.id) {
                const response = await axios.put(
                    `/department/${department.value.id}`,
                    department.value
                );
                updateDepartment(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Department Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post(
                    "/department",
                    department.value
                );
                departments.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Department Created",
                    life: 3000,
                });
            }
        } catch (err) {
            if (err.response && err.response.status === 422) {
                const errors = err.response.data.errors;
                for (const [field, messages] of Object.entries(errors)) {
                    messages.forEach((message) => {
                        toast.add({
                            severity: "error",
                            summary: "Validation Error",
                            detail: message,
                            life: 5000,
                        });
                    });
                }
            } else if (err.response && err.response.status === 403) {
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: "You are not allowed to perform this action",
                    life: 5000,
                });
            } else {
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: "An unexpected error occurred.",
                    life: 5000,
                });
            }
        } finally {
            loading.value = false;
            departmentDialog.value = false;
        }
    }
};

const editDepartment = (departmentData) => {
    editDialog.value = true;
    department.value = { ...departmentData };
    departmentDialog.value = true;
};

const deleteDepartment = async () => {
    loading.value = true;
    try {
        await axios.delete(`/department/${department.value.id}`);
        departments.value = departments.value.filter(
            (r) => r.id !== department.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Department Deleted",
            life: 3000,
        });
    } catch (err) {
        if (err.response && err.response.status === 422) {
            const errors = err.response.data.errors;
            for (const [field, messages] of Object.entries(errors)) {
                messages.forEach((message) => {
                    toast.add({
                        severity: "error",
                        summary: "Validation Error",
                        detail: message,
                        life: 5000,
                    });
                });
            }
        } else if (err.response && err.response.status === 403) {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "You are not allowed to perform this action",
                life: 5000,
            });
        } else {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "An unexpected error occurred.",
                life: 5000,
            });
        }
    } finally {
        deleteDepartmentDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteDepartment = (departmentData) => {
    department.value = departmentData;
    deleteDepartmentDialog.value = true;
};

const deleteSelectedDepartment = async () => {
    const ids = selectedDepartments.value.map((department) => department.id);
    loading.value = true;
    try {
        await axios.post("/department/bulk-delete", { ids });
        departments.value = departments.value.filter(
            (r) => !ids.includes(r.id)
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Departments Deleted",
            life: 3000,
        });
    } catch (err) {
        if (err.response && err.response.status === 422) {
            const errors = err.response.data.errors;
            for (const [field, messages] of Object.entries(errors)) {
                messages.forEach((message) => {
                    toast.add({
                        severity: "error",
                        summary: "Validation Error",
                        detail: message,
                        life: 5000,
                    });
                });
            }
        } else if (err.response && err.response.status === 403) {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "You are not allowed to perform this action",
                life: 5000,
            });
        } else {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "An unexpected error occurred.",
                life: 5000,
            });
        }
    } finally {
        deleteDepartmentsDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteDepartmentsDialog.value = true;
};

const updateDepartment = (updatedDepartment) => {
    const index = departments.value.findIndex(
        (r) => r.id === updatedDepartment.id
    );
    if (index !== -1) {
        departments.value[index] = updatedDepartment;
    }
};

const exportCSV = () => {
    dt.value?.exportCSV();
};
</script>
<style scoped>
::v-deep(.p-breadcrumb) {
    background: transparent !important;
    box-shadow: none !important;
}
</style>

<!-- <script src="https://cdn.tailwindcss.com"></script> -->
<!-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script> -->
