<template>
    <AppLayout title="Crimes">
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
            <!-- Toolbar -->
            <div class="card">
                <Toolbar class="mb-6">
                    <template #start>
                        <Button
                            label="New"
                            icon="pi pi-plus"
                            class="mr-2"
                            @click="openNew"
                        />
                        <Button
                            label="Delete"
                            icon="pi pi-trash"
                            severity="danger"
                            outlined
                            @click="confirmDeleteSelected"
                            :disabled="
                                !selectedCrimes || !selectedCrimes.length
                            "
                        />
                    </template>
                    <template #end>
                        <Button
                            label="Export"
                            icon="pi pi-upload"
                            severity="secondary"
                            @click="exportCSV($event)"
                        />
                    </template>
                </Toolbar>

                <!-- Toast Notifications -->
                <Toast />

                <!-- Data Table -->
                <DataTable
                    v-if="crimes.length > 0"
                    ref="dt"
                    v-model:selection="selectedCrimes"
                    :value="crimes"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} crimes"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Crimes</h4>
                            <IconField>
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText
                                    v-model="filters['global'].value"
                                    placeholder="Search..."
                                />
                            </IconField>
                        </div>
                    </template>
                    <Column
                        selectionMode="multiple"
                        style="width: 3rem"
                        :exportable="false"
                    ></Column>
                    <Column
                        field="name"
                        header="Crime Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="description"
                        header="Description"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="penalty"
                        header="Penalty"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        header="Action"
                        :exportable="false"
                        style="min-width: 12rem"
                    >
                        <template #body="slotProps">
                            <Button
                                icon="pi pi-pencil"
                                outlined
                                rounded
                                class="mr-2"
                                @click="editCrime(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteCrime(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Crimes Found</h2>
                </div>
            </div>

            <!-- Add/Edit Crime Dialog -->
            <Dialog
                v-model:visible="crimeDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Crime' : 'Add New Crime'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            Crime Name
                        </label>
                        <InputText
                            id="name"
                            v-model.trim="crime.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !crime.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !crime.name"
                            class="text-red-500"
                        >
                            Crime Name is required.
                        </small>
                    </div>

                    <div class="col-12">
                        <label for="description" class="block font-bold mb-3"
                            >Description</label
                        >
                        <Textarea
                            id="description"
                            v-model="crime.description"
                            required="true"
                            rows="3"
                            cols="20"
                            autofocus
                            :invalid="submitted && !crime.description"
                            fluid
                        />
                        <small
                            v-if="submitted && !crime.description"
                            class="text-red-500"
                        >
                            Crime Description is required.
                        </small>
                    </div>
                    <div>
                        <label for="penalty" class="block font-bold mb-3">
                            Penalty
                        </label>
                        <InputText
                            id="penalty"
                            v-model.trim="crime.penalty"
                            required="true"
                            autofocus
                            :invalid="submitted && !crime.penalty"
                            fluid
                        />
                        <small
                            v-if="submitted && !crime.penalty"
                            class="text-red-500"
                        >
                            Penalty is required.
                        </small>
                    </div>
                </div>
                <template #footer>
                    <Button
                        label="Cancel"
                        icon="pi pi-times"
                        text
                        @click="hideDialog"
                    />
                    <div>
                        <ProgressSpinner
                            v-if="loading"
                            style="width: 30px; height: 30px"
                            strokeWidth="4"
                            fill="transparent"
                            animationDuration=".5s"
                            aria-label="Custom ProgressSpinner"
                        />
                        <Button
                            v-else
                            label="Save"
                            icon="pi pi-check"
                            @click="saveCrime"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Crime Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteCrimeDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="crime">
                        Are you sure you want to delete <b>{{ crime.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteCrimeDialog = false"
                    />
                    <div>
                        <ProgressSpinner
                            v-if="loading"
                            style="width: 30px; height: 30px"
                            strokeWidth="4"
                            fill="transparent"
                            animationDuration=".5s"
                            aria-label="Custom ProgressSpinner"
                        />
                        <Button
                            v-else
                            label="Yes"
                            icon="pi pi-check"
                            @click="deleteCrime"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Crime Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteCrimesDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected crimes?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteCrimesDialog = false"
                    />
                    <div>
                        <ProgressSpinner
                            v-if="loading"
                            style="width: 30px; height: 30px"
                            strokeWidth="4"
                            fill="transparent"
                            animationDuration=".5s"
                            aria-label="Custom ProgressSpinner"
                        />
                        <Button
                            v-else
                            label="Yes"
                            icon="pi pi-check"
                            text
                            @click="deleteSelectedCrimes"
                        />
                    </div>
                </template>
            </Dialog>
        </div>
    </AppLayout>
</template>
<script setup>
import { ref, onMounted } from "vue";
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
import Textarea from "primevue/textarea";
import ProgressSpinner from "primevue/progressspinner";
import Breadcrumb from "primevue/breadcrumb";
import { Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const toast = useToast();

// Reactive State Variables
const dt = ref();
const crimeDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteCrimeDialog = ref(false);
const deleteCrimesDialog = ref(false);
const crime = ref({});
const selectedCrimes = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Crimes" }]);

const props = defineProps({
    crimes: Array,
});

onMounted(async () => {
    console.log(crimes);
});

const crimes = ref(props.crimes);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    crime.value = {};
    submitted.value = false;
    crimeDialog.value = true;
};

const hideDialog = () => {
    crimeDialog.value = false;
    submitted.value = false;
};

const saveCrime = async () => {
    submitted.value = true;
    if (crime?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (crime.value.id) {
                const response = await axios.put(
                    `/crimes/${crime.value.id}`,
                    crime.value
                );
                updateCrime(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Crime Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post("/crimes", crime.value);
                crimes.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Crime Created",
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
            crimeDialog.value = false;
        }
    }
};

const editCrime = (crimeData) => {
    editDialog.value = true;
    crime.value = { ...crimeData };
    crimeDialog.value = true;
};

const deleteCrime = async () => {
    loading.value = true;
    try {
        await axios.delete(`/crimes/${crime.value.id}`);
        crimes.value = crimes.value.filter((r) => r.id !== crime.value.id);
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Crime Deleted",
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
        deleteCrimeDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteCrime = (crimeData) => {
    crime.value = crimeData;
    deleteCrimeDialog.value = true;
};

const deleteSelectedCrimes = async () => {
    const ids = selectedCrimes.value.map((crime) => crime.id);
    loading.value = true;
    try {
        await axios.post("/crimes/bulk-delete", { ids });
        crimes.value = crimes.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Crimes Deleted",
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
        deleteCrimesDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteCrimesDialog.value = true;
};

const updateCrime = (updatedCrime) => {
    const index = crimes.value.findIndex((r) => r.id === updatedCrime.id);
    if (index !== -1) {
        crimes.value[index] = updatedCrime;
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
::v-deep(.p-datatable-tbody > tr:hover) {
    background-color: #f0f0f0 !important;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
</style>
