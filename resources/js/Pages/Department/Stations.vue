<template>
    <AppLayout title="Stations">
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
                            :disabled="
                                !selectedStations || !selectedStations.length
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
                    v-if="stations.length > 0"
                    ref="dt"
                    v-model:selection="selectedStations"
                    :value="stations"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} stations"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Stations</h4>
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
                        header="Station Name"
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
                        field="email"
                        header="Email"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="chairperson"
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
                                @click="editStation(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteStation(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Stations Found</h2>
                </div>
            </div>

            <!-- Add/Edit Station Dialog -->
            <Dialog
                v-model:visible="stationDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Station' : 'Add New Station'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            Station Name
                        </label>
                        <InputText
                            id="name"
                            v-model.trim="station.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !station.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !station.name"
                            class="text-red-500"
                        >
                            Station Name is required.
                        </small>
                    </div>
                    <div>
                        <label for="location" class="block font-bold mb-3">
                            Location
                        </label>
                        <InputText
                            id="location"
                            v-model.trim="station.location"
                            required="true"
                            autofocus
                            :invalid="submitted && !station.location"
                            fluid
                        />
                        <small
                            v-if="submitted && !station.location"
                            class="text-red-500"
                        >
                            Location is required.
                        </small>
                    </div>

                    <div>
                        <label for="email" class="block font-bold mb-3">
                            Email
                        </label>
                        <InputText
                            id="email"
                            v-model.trim="station.email"
                            required="true"
                            autofocus
                            :invalid="submitted && !station.email"
                            fluid
                        />
                        <small
                            v-if="submitted && !station.email"
                            class="text-red-500"
                        >
                            Email is required.
                        </small>
                    </div>
                    <div>
                        <label for="chairperson" class="block font-bold mb-3">
                            Chairperson
                        </label>
                        <InputText
                            id="chairperson"
                            v-model.trim="station.chairperson"
                            required="true"
                            autofocus
                            :invalid="submitted && !station.chairperson"
                            fluid
                        />
                        <small
                            v-if="submitted && !station.chairperson"
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
                            @click="saveStation"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Station Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteStationDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="station">
                        Are you sure you want to delete <b>{{ station.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteStationDialog = false"
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
                            @click="deleteStation"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Station Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteStationsDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected Stations?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteStationDialog = false"
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
                            @click="deleteselectedStations"
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
const stationDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteStationDialog = ref(false);
const deleteStationsDialog = ref(false);
const station = ref({});
const selectedStations = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    stations: Array,
});

onMounted(async () => {
    console.log(stations);
});

const stations = ref(props.stations);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    station.value = {};
    submitted.value = false;
    stationDialog.value = true;
};

const hideDialog = () => {
    stationDialog.value = false;
    submitted.value = false;
};

const saveStation = async () => {
    submitted.value = true;
    if (station?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (station.value.id) {
                const response = await axios.put(
                    `/stations/${station.value.id}`,
                    station.value
                );
                updateStation(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Station Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post("/stations", station.value);
                stations.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Stations Created",
                    life: 3000,
                });
            }
        } catch (err) {
            console.error(err);
        } finally {
            loading.value = false;
            stationDialog.value = false;
        }
    }
};

const editStation = (stationData) => {
    editDialog.value = true;
    station.value = { ...stationData };
    stationDialog.value = true;
};

const deleteStation = async () => {
    loading.value = true;
    try {
        await axios.delete(`/stations/${station.value.id}`);
        stations.value = stations.value.filter(
            (r) => r.id !== station.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Station Deleted",
            life: 3000,
        });
    } catch (err) {
        console.error(err);
    } finally {
        deleteStationDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteStation = (stationData) => {
    station.value = stationData;
    deleteStationDialog.value = true;
};

const deleteSelectedStations = async () => {
    const ids = selectedStations.value.map((stations) => station.id);
    loading.value = true;
    try {
        await axios.post("/stations/bulk-delete", { ids });
        stations.value = stations.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Stations Deleted",
            life: 3000,
        });
    } catch (err) {
        console.error(err);
    } finally {
        deleteStationsDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteStationsDialog.value = true;
};

const updateStation = (updatedStation) => {
    const index = stations.value.findIndex((r) => r.id === updatedStation.id);
    if (index !== -1) {
        stations.value[index] = updatedStation;
    }
};

const exportCSV = () => {
    dt.value?.exportCSV();
};
</script>
