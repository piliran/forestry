<template>
    <AppLayout title="Encroached Areas">
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
                                !selectedEncroaches || !selectedEncroaches.length
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
                    v-if="encroaches.length > 0"
                    ref="dt"
                    v-model:selection="selectedEncroaches"
                    :value="encroaches"
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
                            <h4 class="m-0">Manage Encroached Areas</h4>
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
                        field="area.name"
                        header="Area"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="encroached.latitude"
                        header="Latitude"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="encroached.longitude"
                        header="Longitude"
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
                                @click="editEncroached(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteEncroached(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Encroached Areas Found</h2>
                </div>
            </div>

            <!-- Add/Edit Encroached Area Dialog -->
            <Dialog
                v-model:visible="encroachedDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Encroached Area' : 'Add New Encroached Area'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">                    
                    <div class="col-12 md:col-6">
                        <label for="encroached" class="block font-bold mb-2"
                            >Area Name</label
                        >
                        <Select
                            id="id"
                            v-model="encroached.area_id"
                            :options="areas"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Area"
                            fluid
                        />
                        <small
                            v-if="submitted && !encroached.area_id"
                            class="text-red-500"
                        >
                            Area is required.
                        </small>
                    </div>
                    <div>
                        <label for="latitude" class="block font-bold mb-3">
                            Latitude
                        </label>
                        <InputText
                            id="latitude"
                            v-model.trim="encroached.latitude"
                            required="true"
                            autofocus
                            :invalid="submitted && !encroached.latitude"
                            fluid
                        />
                        <small
                            v-if="submitted && !encroached.latitude"
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
                            id="longitude"
                            v-model.trim="encroached.longitude"
                            required="true"
                            autofocus
                            :invalid="submitted && !encroached.longitude"
                            fluid
                        />
                        <small
                            v-if="submitted && !encroached.longitude"
                            class="text-red-500"
                        >
                            Longitude is required.
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
                            @click="saveEncroached"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Encroached Area Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteEncroachedDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="encroached">
                        Are you sure you want to delete
                        <b>{{ encroached.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteEncroachedDialog = false"
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
                            @click="deleteEncroached"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Encroached Areas Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteEncroachesDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected Encroached Areas?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteEncroachedDialog = false"
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
                            @click="deleteSelectedEncroaches"
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
const encroachedDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteEncroachedDialog = ref(false);
const deleteEncroachesDialog = ref(false);
const encroached = ref({});
const selectedEncroaches = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    encroaches: Array,
    areas: Array,
});

const encroaches = ref(props.encroaches);
const areas = ref(props.areas);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    encroached.value = {};
    submitted.value = false;
    encroachedDialog.value = true;
};

const hideDialog = () => {
    encroachedDialog.value = false;
    submitted.value = false;
};

const saveEncroached = async () => {
    submitted.value = true;
    if (encroached?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (encroached.value.id) {
                const response = await axios.put(
                    `/encroached-areas/${encroached.value.id}`,
                    encroached.value
                );
                updateEncroached(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Encroached Area Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post("/encroached-areas", encroached.value);
                encroaches.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Encroached Area Created",
                    life: 3000,
                });
            }
        } catch (err) {
            if (err.response && err.response.status === 422) {
                // Display validation errors
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
            encroachedDialog.value = false;
        }
    }
};

const editEncroached = (encroachedData) => {
    editDialog.value = true;
    encroached.value = { ...encroachedData };
    encroachedDialog.value = true;
};

const deleteEncroached = async () => {
    loading.value = true;
    try {
        await axios.delete(`/encroached-areas/${encroached.value.id}`);
        encroaches.value = encroaches.value.filter(
            (r) => r.id !== encroached.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Encroached Area Deleted",
            life: 3000,
        });
    } catch (err) {
        if (err.response && err.response.status === 422) {
            // Display validation errors
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
        } else {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "An unexpected error occurred.",
                life: 5000,
            });
        }
    } finally {
        deleteEncroachedDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteEncroached = (encroachedData) => {
    encroached.value = encroachedData;
    deleteEncroachedDialog.value = true;
};

const deleteSelectedEncroaches = async () => {
    const ids = selectedEncroaches.value.map((encroached) => encroached.id);
    loading.value = true;
    try {
        await axios.post("/encroached-areas/bulk-delete", { ids });
        districts.value = encroaches.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Encroached Areas Deleted",
            life: 3000,
        });
    } catch (err) {
        if (err.response && err.response.status === 422) {
            // Display validation errors
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
        } else {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "An unexpected error occurred.",
                life: 5000,
            });
        }
    } finally {
        deleteEncroachesDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteEncroachesDialog.value = true;
};

const updateEncroached = (updatedEncroached) => {
    const index = encroaches.value.findIndex((r) => r.id === updatedEncroached.id);
    if (index !== -1) {
        encroaches.value[index] = updatedEncroached;
    }
};

const exportCSV = () => {
    dt.value?.exportCSV();
};
</script>
