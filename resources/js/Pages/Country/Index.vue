<template>
    <AppLayout title="Countries">
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
                    <Column header="#" headerStyle="width:3rem">
                        <template #body="slotProps">
                            {{ slotProps.index + 1 }}
                        </template>
                    </Column>
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

                        <Select
                            id="name"
                            v-model="country.name"
                            :options="countriesArray"
                            filter
                            optionLabel="name"
                            placeholder="Select Country"
                            class="w-full"
                        >
                        </Select>
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
import Breadcrumb from "primevue/breadcrumb";
import { Link } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const toast = useToast();

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Countries" }]);

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
    if (country?.value?.name?.name?.trim()) {
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
                const response = await axios.post("/countries", {
                    name: country.value.name.name,
                });
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

//const selectedCountry = ref();
const countriesArray = ref([
    { name: "Afghanistan", code: "AF", flag: "https://flagcdn.com/w40/af.png" },
    { name: "Albania", code: "AL", flag: "https://flagcdn.com/w40/al.png" },
    { name: "Algeria", code: "DZ", flag: "https://flagcdn.com/w40/dz.png" },
    { name: "Andorra", code: "AD", flag: "https://flagcdn.com/w40/ad.png" },
    { name: "Angola", code: "AO", flag: "https://flagcdn.com/w40/ao.png" },
    { name: "Argentina", code: "AR", flag: "https://flagcdn.com/w40/ar.png" },
    { name: "Australia", code: "AU", flag: "https://flagcdn.com/w40/au.png" },
    { name: "Austria", code: "AT", flag: "https://flagcdn.com/w40/at.png" },
    { name: "Bahamas", code: "BS", flag: "https://flagcdn.com/w40/bs.png" },
    { name: "Bahrain", code: "BH", flag: "https://flagcdn.com/w40/bh.png" },
    { name: "Bangladesh", code: "BD", flag: "https://flagcdn.com/w40/bd.png" },
    { name: "Belgium", code: "BE", flag: "https://flagcdn.com/w40/be.png" },
    { name: "Brazil", code: "BR", flag: "https://flagcdn.com/w40/br.png" },
    { name: "Bulgaria", code: "BG", flag: "https://flagcdn.com/w40/bg.png" },
    { name: "Cameroon", code: "CM", flag: "https://flagcdn.com/w40/cm.png" },
    { name: "Canada", code: "CA", flag: "https://flagcdn.com/w40/ca.png" },
    { name: "China", code: "CN", flag: "https://flagcdn.com/w40/cn.png" },
    { name: "Colombia", code: "CO", flag: "https://flagcdn.com/w40/co.png" },
    { name: "Croatia", code: "HR", flag: "https://flagcdn.com/w40/hr.png" },
    { name: "Cuba", code: "CU", flag: "https://flagcdn.com/w40/cu.png" },
    { name: "Denmark", code: "DK", flag: "https://flagcdn.com/w40/dk.png" },
    { name: "Egypt", code: "EG", flag: "https://flagcdn.com/w40/eg.png" },
    { name: "Ethiopia", code: "ET", flag: "https://flagcdn.com/w40/et.png" },
    { name: "Finland", code: "FI", flag: "https://flagcdn.com/w40/fi.png" },
    { name: "France", code: "FR", flag: "https://flagcdn.com/w40/fr.png" },
    { name: "Germany", code: "DE", flag: "https://flagcdn.com/w40/de.png" },
    { name: "Ghana", code: "GH", flag: "https://flagcdn.com/w40/gh.png" },
    { name: "Greece", code: "GR", flag: "https://flagcdn.com/w40/gr.png" },
    { name: "India", code: "IN", flag: "https://flagcdn.com/w40/in.png" },
    { name: "Indonesia", code: "ID", flag: "https://flagcdn.com/w40/id.png" },
    { name: "Ireland", code: "IE", flag: "https://flagcdn.com/w40/ie.png" },
    { name: "Israel", code: "IL", flag: "https://flagcdn.com/w40/il.png" },
    { name: "Italy", code: "IT", flag: "https://flagcdn.com/w40/it.png" },
    { name: "Japan", code: "JP", flag: "https://flagcdn.com/w40/jp.png" },
    { name: "Kenya", code: "KE", flag: "https://flagcdn.com/w40/ke.png" },
    { name: "Malawi", code: "MW", flag: "https://flagcdn.com/w40/mw.png" },

    { name: "Mexico", code: "MX", flag: "https://flagcdn.com/w40/mx.png" },
    { name: "Netherlands", code: "NL", flag: "https://flagcdn.com/w40/nl.png" },
    { name: "New Zealand", code: "NZ", flag: "https://flagcdn.com/w40/nz.png" },
    { name: "Nigeria", code: "NG", flag: "https://flagcdn.com/w40/ng.png" },
    { name: "Norway", code: "NO", flag: "https://flagcdn.com/w40/no.png" },
    { name: "Pakistan", code: "PK", flag: "https://flagcdn.com/w40/pk.png" },
    { name: "Philippines", code: "PH", flag: "https://flagcdn.com/w40/ph.png" },
    { name: "Poland", code: "PL", flag: "https://flagcdn.com/w40/pl.png" },
    { name: "Portugal", code: "PT", flag: "https://flagcdn.com/w40/pt.png" },
    { name: "Romania", code: "RO", flag: "https://flagcdn.com/w40/ro.png" },
    { name: "Russia", code: "RU", flag: "https://flagcdn.com/w40/ru.png" },
    {
        name: "Saudi Arabia",
        code: "SA",
        flag: "https://flagcdn.com/w40/sa.png",
    },
    {
        name: "South Africa",
        code: "ZA",
        flag: "https://flagcdn.com/w40/za.png",
    },
    { name: "South Korea", code: "KR", flag: "https://flagcdn.com/w40/kr.png" },
    { name: "Spain", code: "ES", flag: "https://flagcdn.com/w40/es.png" },
    { name: "Sweden", code: "SE", flag: "https://flagcdn.com/w40/se.png" },
    { name: "Switzerland", code: "CH", flag: "https://flagcdn.com/w40/ch.png" },
    { name: "Thailand", code: "TH", flag: "https://flagcdn.com/w40/th.png" },
    { name: "Turkey", code: "TR", flag: "https://flagcdn.com/w40/tr.png" },
    {
        name: "United Arab Emirates",
        code: "AE",
        flag: "https://flagcdn.com/w40/ae.png",
    },
    {
        name: "United Kingdom",
        code: "GB",
        flag: "https://flagcdn.com/w40/gb.png",
    },
    {
        name: "United States",
        code: "US",
        flag: "https://flagcdn.com/w40/us.png",
    },
    { name: "Vietnam", code: "VN", flag: "https://flagcdn.com/w40/vn.png" },
    { name: "Zambia", code: "ZM", flag: "https://flagcdn.com/w40/zm.png" },
    { name: "Zimbabwe", code: "ZW", flag: "https://flagcdn.com/w40/zw.png" },
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
