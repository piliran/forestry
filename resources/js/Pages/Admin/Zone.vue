<template>
    <AppLayout title="Zones">
        <div>
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
                            :disabled="!selectedZones || !selectedZones.length"
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
                    v-if="zones.length > 0"
                    ref="dt"
                    v-model:selection="selectedZones"
                    :value="zones"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Zones"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Zones</h4>
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
                        header="Zone Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="department.name"
                        header="Department"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="phone"
                        header="Phone"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="location"
                        header="Location"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="website"
                        header="Website"
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
                                @click="editZone(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteZone(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Zones Found</h2>
                </div>
            </div>

            <!-- Add/Edit Zone Dialog -->
            <Dialog
                v-model:visible="zoneDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Zone' : 'Add New Zone'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            Zone Name
                        </label>
                        <InputText
                            id="name"
                            v-model.trim="zone.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !zone.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !zone.name"
                            class="text-red-500"
                        >
                            Zone Name is required.
                        </small>
                    </div>

                    <div class="col-12 md:col-6">
                        <label for="district" class="block font-bold mb-2"
                            >Department</label
                        >
                        <Select
                            id="id"
                            v-model="zone.department_id"
                            :options="departments"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Department"
                            fluid
                        />
                        <small
                            v-if="submitted && !zone.department_id"
                            class="text-red-500"
                        >
                            Department Name is required.
                        </small>
                    </div>
                    <div>
                        <label for="phone" class="block font-bold mb-3">
                            Phone
                        </label>
                        <InputText
                            id="phone"
                            v-model.trim="zone.phone"
                            required="true"
                            autofocus
                            :invalid="submitted && !zone.phone"
                            fluid
                        />
                        <small
                            v-if="submitted && !zone.phone"
                            class="text-red-500"
                        >
                            Phone is required.
                        </small>
                    </div>
                    <div>
                        <label for="location" class="block font-bold mb-3">
                            Location
                        </label>
                        <InputText
                            id="location"
                            v-model.trim="zone.location"
                            required="true"
                            autofocus
                            :invalid="submitted && !zone.location"
                            fluid
                        />
                        <small
                            v-if="submitted && !zone.location"
                            class="text-red-500"
                        >
                            Location is required.
                        </small>
                    </div>

                    <div>
                        <label for="website" class="block font-bold mb-3">
                            Website
                        </label>
                        <InputText
                            id="email"
                            v-model.trim="zone.website"
                            required="true"
                            autofocus
                            :invalid="submitted && !zone.website"
                            fluid
                        />
                        <small
                            v-if="submitted && !zone.website"
                            class="text-red-500"
                        >
                            Website is required.
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
                            @click="saveZone"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Zone Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteZoneDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="zone">
                        Are you sure you want to delete
                        <b>{{ zone.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteZoneDialog = false"
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
                            @click="deleteZone"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Zone Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteZonesDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected Zones?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteZoneDialog = false"
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
                            @click="deleteSelectedZones"
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

import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const toast = useToast();

// Reactive State Variables
const dt = ref();
const zoneDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteZoneDialog = ref(false);
const deleteZonesDialog = ref(false);
const zone = ref({});
const selectedZones = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    zones: Array,
    departments: Array,
});

const zones = ref(props.zones);
const departments = ref(props.departments);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    zone.value = {};
    submitted.value = false;
    zoneDialog.value = true;
};

const hideDialog = () => {
    zoneDialog.value = false;
    submitted.value = false;
};

const saveZone = async () => {
    submitted.value = true;
    if (zone?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (zone.value.id) {
                const response = await axios.put(
                    `/zones/${zone.value.id}`,
                    zone.value
                );
                updateZone(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Zone Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post("/zones", zone.value);
                zones.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Zone Created",
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
            zoneDialog.value = false;
        }
    }
};

const editZone = (zoneData) => {
    editDialog.value = true;
    zone.value = { ...zoneData };
    zoneDialog.value = true;
};

const deleteZone = async () => {
    loading.value = true;
    try {
        await axios.delete(`/zones/${zone.value.id}`);
        zones.value = zones.value.filter((r) => r.id !== zone.value.id);
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Zone Deleted",
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
        deleteZoneDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteZone = (zoneData) => {
    zone.value = zoneData;
    deleteZoneDialog.value = true;
};

const deleteSelectedZones = async () => {
    const ids = selectedZones.value.map((zone) => zone.id);
    loading.value = true;
    try {
        await axios.post("/zones/bulk-delete", { ids });
        zones.value = zones.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Zones Deleted",
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
        deleteZonesDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteZonesDialog.value = true;
};

const updateZone = (updatedZone) => {
    const index = zones.value.findIndex((r) => r.id === updatedZone.id);
    if (index !== -1) {
        zones.value[index] = updatedZone;
    }
};

const exportCSV = () => {
    dt.value?.exportCSV();
};
</script>
