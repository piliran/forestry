<template>
    <AppLayout title="Districts">
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
                                !selectedDistricts || !selectedDistricts.length
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
                    v-if="districts.length > 0"
                    ref="dt"
                    v-model:selection="selectedDistricts"
                    :value="districts"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} districts"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Districts</h4>
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
                        header="District Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="zone.name"
                        header="Zone"
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
                                @click="editDistrict(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteDistrict(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Districts Found</h2>
                </div>
            </div>

            <!-- Add/Edit District Dialog -->
            <Dialog
                v-model:visible="districtDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit District' : 'Add New District'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            District Name
                        </label>

                        <Select
                            id="name"
                            v-model="district.name"
                            :options="districtsArray"
                            filter
                            optionLabel="name"
                            placeholder="Select a District"
                            class="w-full"
                        >
                        </Select>
                        <small
                            v-if="submitted && !district.name"
                            class="text-red-500"
                        >
                            District Name is required.
                        </small>
                    </div>

                    <div class="col-12 md:col-6">
                        <label for="district" class="block font-bold mb-2"
                            >Zone</label
                        >
                        <Select
                            id="id"
                            v-model="district.zone_id"
                            :options="zones"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select zone"
                            fluid
                            filter
                        />
                        <small
                            v-if="submitted && !district.zone_id"
                            class="text-red-500"
                        >
                            Zone Name is required.
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
                            @click="saveDistrict"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single District Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteDistrictDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="district">
                        Are you sure you want to delete
                        <b>{{ district.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteDistrictDialog = false"
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
                            @click="deleteDistrict"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple District Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteDistrictsDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected Districts?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteDistrictDialog = false"
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
                            @click="deleteSelectedDistricts"
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
import ProgressSpinner from "primevue/progressspinner";
import Breadcrumb from "primevue/breadcrumb";
import { Link } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const toast = useToast();

// Reactive State Variables
const dt = ref();
const districtDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteDistrictDialog = ref(false);
const deleteDistrictsDialog = ref(false);
const district = ref({});
const selectedDistricts = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    districts: Array,
    zones: Array,
});

const districts = ref(props.districts);
const zones = ref(props.zones);

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Districts" }]);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    district.value = {};
    submitted.value = false;
    districtDialog.value = true;
};

const hideDialog = () => {
    districtDialog.value = false;
    submitted.value = false;
};

const saveDistrict = async () => {
    submitted.value = true;
    if (district?.value?.name?.name?.trim()) {
        loading.value = true;
        try {
            if (district.value.id) {
                const response = await axios.put(
                    `/districts/${district.value.id}`,
                    district.value
                );
                updateDistr(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "District Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post("/districts", {
                    name: district.value.name.name,
                    zone_id: district.value.zone_id,
                });
                districts.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "District Created",
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
            districtDialog.value = false;
        }
    }
};

const editDistrict = (districtData) => {
    editDialog.value = true;
    district.value = { ...districtData };
    districtDialog.value = true;
};

const deleteDistrict = async () => {
    loading.value = true;
    try {
        await axios.delete(`/districts/${district.value.id}`);
        districts.value = districts.value.filter(
            (r) => r.id !== district.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "District Deleted",
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
        deleteDistrictDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteDistrict = (districtData) => {
    district.value = districtData;
    deleteDistrictDialog.value = true;
};

const deleteSelectedDistricts = async () => {
    const ids = selectedDistricts.value.map((district) => district.id);
    loading.value = true;
    try {
        await axios.post("/districts/bulk-delete", { ids });
        districts.value = districts.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Districts Deleted",
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
        deleteDistrictsDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteDistrictsDialog.value = true;
};

const updateDistrict = (updatedDistrict) => {
    const index = districts.value.findIndex((r) => r.id === updatedDistrict.id);
    if (index !== -1) {
        districts.value[index] = updatedDistrict;
    }
};

const exportCSV = () => {
    dt.value?.exportCSV();
};

const districtsArray = ref([
    { name: "Balaka" },
    { name: "Blantyre" },
    { name: "Chikwawa" },
    { name: "Chiradzulu" },
    { name: "Chitipa" },
    { name: "Dedza" },
    { name: "Dowa" },
    { name: "Karonga" },
    { name: "Kasungu" },
    { name: "Likoma" },
    { name: "Lilongwe" },
    { name: "Machinga" },
    { name: "Mangochi" },
    { name: "Mchinji" },
    { name: "Mulanje" },
    { name: "Mwanza" },
    { name: "Mzimba" },
    { name: "Neno" },
    { name: "Ntcheu" },
    { name: "Ntchisi" },
    { name: "Phalombe" },
    { name: "Rumphi" },
    { name: "Salima" },
    { name: "Thyolo" },
    { name: "Zomba" },
    { name: "Nsanje" },
]);
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
