<template>
    <AppLayout title="Countries">
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
                                !selectedCountries || !selectedCountries.length
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
                    v-if="countries.length > 0"
                    ref="dt"
                    v-model:selection="selectedCountries"
                    :value="countries"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} countries"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Countries</h4>
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
                        header="Country Name"
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
                                @click="editCountry(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteCountry(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Countries Found</h2>
                </div>
            </div>

            <!-- Add/Edit Countries Dialog -->
            <Dialog
                v-model:visible="countryDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Country' : 'Add New Country'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            Country Name
                        </label>
                        <InputText
                            id="name"
                            v-model.trim="country.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !country.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !country.name"
                            class="text-red-500"
                        >
                            Country Name is required.
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
                            @click="saveCountry"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Country Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteCountryDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="country">
                        Are you sure you want to delete
                        <b>{{ country.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteCountryDialog = false"
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
                            @click="deleteCountry"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Country Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteCountriesDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected countries?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteCountryDialog = false"
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
                            @click="deleteSelectedCountries"
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
const countryDialog = ref(false);
const editDialog = ref(false);

const loading = ref(false);
const deleteCountryDialog = ref(false);
const deleteCountriesDialog = ref(false);
const country = ref({});
const selectedCountries = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    countries: Array,
});

const countries = ref(props.countries);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    country.value = {};
    submitted.value = false;
    countryDialog.value = true;
};

const hideDialog = () => {
    countryDialog.value = false;
    submitted.value = false;
};

const saveCountry = async () => {
    submitted.value = true;
    if (country?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (country.value.id) {
                const response = await axios.put(
                    `/countries/${country.value.id}`,
                    country.value
                );
                updateCountry(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Country Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post("/countries", country.value);
                countries.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Country Created",
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
            countryDialog.value = false;
        }
    }
};

const editCountry = (countryData) => {
    editDialog.value = true;
    country.value = { ...countryData };
    countryDialog.value = true;
};

const deleteCountry = async () => {
    loading.value = true;
    try {
        await axios.delete(`/countries/${country.value.id}`);
        countries.value = countries.value.filter(
            (r) => r.id !== country.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Country Deleted",
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
        deleteCountryDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteCountry = (countryData) => {
    country.value = countryData;
    deleteCountryDialog.value = true;
};

const deleteSelectedCountries = async () => {
    const ids = selectedCountries.value.map((country) => country.id);
    loading.value = true;
    try {
        await axios.post("/countries/bulk-delete", { ids });
        countries.value = countries.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Countries Deleted",
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
        deleteCountriesDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteCountriesDialog.value = true;
};

const updateCountry = (updatedCountry) => {
    const index = countries.value.findIndex((r) => r.id === updatedCountry.id);
    if (index !== -1) {
        countries.value[index] = updatedCountry;
    }
};

const exportCSV = () => {
    dt.value?.exportCSV();
};
</script>
