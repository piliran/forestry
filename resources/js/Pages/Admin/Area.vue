<template>
    <AppLayout title="Areas">
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
                            :disabled="!selectedAreas || !selectedAreas.length"
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
                    v-if="areas.length > 0"
                    ref="dt"
                    v-model:selection="selectedAreas"
                    :value="areas"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} areas"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Areas</h4>
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
                        header="Area Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        header="Station"
                        field="station.name"
                        sortable
                        style="min-width: 12rem"
                    ></Column>
                    <Column
                        field="location"
                        header="Location"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="latitude"
                        header="Latitude"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="longitude"
                        header="Longitude"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="contact_person"
                        header="Chairperson"
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
                                @click="editArea(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteArea(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Areas Found</h2>
                </div>
            </div>

            <!-- Add/Edit Area Dialog -->
            <Dialog
                v-model:visible="areaDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Area' : 'Add New Area'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            Area Name
                        </label>
                        <InputText
                            id="name"
                            v-model.trim="area.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !area.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !area.name"
                            class="text-red-500"
                        >
                            Area Name is required.
                        </small>
                    </div>
                    <div>
                        <label for="station" class="block font-bold mb-3">
                            Station
                        </label>
                        <Select
                            id="station"
                            v-model="area.station_id"
                            :options="stations"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Station"
                            fluid
                        />
                    </div>
                    <div>
                        <label for="code" class="block font-bold mb-3">
                            Code
                        </label>
                        <InputText
                            id="code"
                            v-model.trim="area.code"
                            required="true"
                            autofocus
                            :invalid="submitted && !area.code"
                            fluid
                        />
                        <small
                            v-if="submitted && !area.code"
                            class="text-red-500"
                        >
                            Code is required.
                        </small>
                    </div>
                    <div>
                        <label for="location" class="block font-bold mb-3">
                            Location
                        </label>
                        <InputText
                            id="location"
                            v-model.trim="area.location"
                            required="true"
                            autofocus
                            :invalid="submitted && !area.location"
                            fluid
                        />
                        <small
                            v-if="submitted && !area.location"
                            class="text-red-500"
                        >
                            Location is required.
                        </small>
                    </div>
                    <div>
                        <label for="latitude" class="block font-bold mb-3">
                            Latitude
                        </label>
                        <InputText
                            id="location"
                            v-model.trim="area.latitude"
                            required="true"
                            autofocus
                            :invalid="submitted && !area.latitude"
                            fluid
                        />
                        <small
                            v-if="submitted && !area.latitude"
                            class="text-red-500"
                        >
                            Latitude is required.
                        </small>
                    </div>
                    <div>
                        <label for="longitude" class="block font-bold mb-3">
                            Longitude
                        </label>
                        <InputText
                            id="location"
                            v-model.trim="area.longitude"
                            required="true"
                            autofocus
                            :invalid="submitted && !area.longitude"
                            fluid
                        />
                        <small
                            v-if="submitted && !area.longitude"
                            class="text-red-500"
                        >
                            Longitude is required.
                        </small>
                    </div>
                    <div>
                        <label for="contact_person" class="block font-bold mb-3">
                            Chairperson
                        </label>
                        <InputText
                            id="contact_person"
                            v-model.trim="area.contact_person"
                            required="true"
                            autofocus
                            :invalid="submitted && !area.contact_person"
                            fluid
                        />
                        <small
                            v-if="submitted && !area.contact_person"
                            class="text-red-500"
                        >
                            Chairperson is required.
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
                            @click="saveArea"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Area Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteAreaDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="area">
                        Are you sure you want to delete <b>{{ area.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteAreaDialog = false"
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
                            @click="deleteArea"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Area Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteAreasDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected areas?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteAreasDialog = false"
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
                            @click="deleteSelectedAreas"
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
const areaDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteAreaDialog = ref(false);
const deleteAreasDialog = ref(false);
const area = ref({});
const selectedAreas = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    areas: Array,
    stations: Array,
});

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Areas" }]);

const areas = ref(props.areas);
const stations = ref(props.stations);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    area.value = {};
    submitted.value = false;
    areaDialog.value = true;
};

const hideDialog = () => {
    areaDialog.value = false;
    submitted.value = false;
};

const saveArea = async () => {
    submitted.value = true;
    if (area?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (area.value.id) {
                const response = await axios.put(
                    `/area-list/${area.value.id}`,
                    area.value
                );

                updateArea(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Area Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post("/area-list", area.value);

                areas.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Area Created",
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
            areaDialog.value = false;
        }
    }
};

const editArea = (areaData) => {
    editDialog.value = true;
    area.value = { ...areaData };
    areaDialog.value = true;
};

const deleteArea = async () => {
    loading.value = true;
    try {
        await axios.delete(`/area-list/${area.value.id}`);
        areas.value = areas.value.filter((r) => r.id !== area.value.id);
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Area Deleted",
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
        deleteAreaDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteArea = (areaData) => {
    area.value = areaData;
    deleteAreaDialog.value = true;
};

const deleteSelectedAreas = async () => {
    const ids = selectedAreas.value.map((area) => area.id);
    loading.value = true;
    try {
        await axios.post("/area-list/bulk-delete", { ids });
        areas.value = areas.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Areas Deleted",
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
        deleteAreasDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteAreasDialog.value = true;
};

const updateArea = (updatedArea) => {
    const index = areas.value.findIndex((r) => r.id === updatedArea.id);
    if (index !== -1) {
        areas.value[index] = updatedArea;
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
