<template>
    <AppLayout title="Species">
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
                                !selectedSpecies || !selectedSpecies.length
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
                    v-if="species.length > 0"
                    ref="dt"
                    v-model:selection="selectedSpecies"
                    :value="species"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Species"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Species</h4>
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
                        header="Species Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="category.name"
                        header="Category"
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
                        field="unplanted_seedlings_count"
                        header="Unplanted Seedlings Count"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="planted_seedlings_count"
                        header="Planted Seedlings Count"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="unmatured_specie_count"
                        header="Unmatured Specie Count"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="matured_specie_count"
                        header="Matured Specie Count"
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
                                @click="editSpecie(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteSpecie(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Species Found</h2>
                </div>
            </div>

            <!-- Add/Edit Specie Dialog -->
            <Dialog
                v-model:visible="specieDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Specie' : 'Add New Specie'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            Specie Name
                        </label>
                        <InputText
                            id="name"
                            v-model.trim="specie.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !specie.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !specie.name"
                            class="text-red-500"
                        >
                            Specie Name is required.
                        </small>
                    </div>

                    <div class="col-12 md:col-6">
                        <label
                            for="speciesCategory"
                            class="block font-bold mb-2"
                            >Category</label
                        >
                        <Select
                            id="id"
                            v-model="specie.specie_cat_id"
                            :options="speciesCategories"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Specie Category"
                            fluid
                            filter
                        />

                        <small
                            v-if="submitted && !specie.specie_cat_id"
                            class="text-red-500"
                        >
                            Category is required.
                        </small>
                    </div>
                    <div>
                        <label for="description" class="block font-bold mb-3">
                            Description
                        </label>

                        <Textarea
                            id="description"
                            v-model.trim="specie.description"
                            required="true"
                            rows="3"
                            cols="20"
                            autofocus
                            :invalid="submitted && !specie.description"
                            fluid
                            placeholder="Enter Description"
                        />
                        <small
                            v-if="submitted && !specie.description"
                            class="text-red-500"
                        >
                            Description is required.
                        </small>
                    </div>
                    <div>
                        <label
                            for="planted_seedlings_count"
                            class="block font-bold mb-3"
                        >
                            Planted Seedlings Count
                        </label>
                        <InputText
                            id="planted_seedlings_count"
                            v-model.trim="specie.planted_seedlings_count"
                            required="true"
                            autofocus
                            :invalid="
                                submitted && !specie.planted_seedlings_count
                            "
                            fluid
                        />
                        <small
                            v-if="submitted && !specie.planted_seedlings_count"
                            class="text-red-500"
                        >
                            Planted Seedlings Count is required.
                        </small>
                    </div>
                    <div>
                        <label
                            for="unplanted_seedlings_count"
                            class="block font-bold mb-3"
                        >
                            Unplanted Seedlings Count
                        </label>
                        <InputText
                            id="uplanted_seedlings_count"
                            v-model.trim="specie.unplanted_seedlings_count"
                            required="true"
                            autofocus
                            :invalid="
                                submitted && !specie.unplanted_seedlings_count
                            "
                            fluid
                        />
                        <small
                            v-if="
                                submitted && !specie.unplanted_seedlings_count
                            "
                            class="text-red-500"
                        >
                            Unplanted Seedlings Count is required.
                        </small>
                    </div>
                    <div>
                        <label
                            for="matured_specie_count"
                            class="block font-bold mb-3"
                        >
                            Matured Specie Count
                        </label>
                        <InputText
                            id="matured_specie_count"
                            v-model.trim="specie.matured_specie_count"
                            required="true"
                            autofocus
                            :invalid="submitted && !specie.matured_specie_count"
                            fluid
                        />
                        <small
                            v-if="submitted && !specie.matured_specie_count"
                            class="text-red-500"
                        >
                            Matured Specie Count is required.
                        </small>
                    </div>
                    <div>
                        <label
                            for="unmatured_specie_count"
                            class="block font-bold mb-3"
                        >
                            Unmatured Specie Count
                        </label>
                        <InputText
                            id="unmatured_specie_count"
                            v-model.trim="specie.unmatured_specie_count"
                            required="true"
                            autofocus
                            :invalid="
                                submitted && !specie.unmatured_specie_count
                            "
                            fluid
                        />
                        <small
                            v-if="submitted && !specie.unmatured_specie_count"
                            class="text-red-500"
                        >
                            Unmatured Specie Count is required.
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
                            @click="saveSpecie"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Specie Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteSpecieDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="specie">
                        Are you sure you want to delete
                        <b>{{ specie.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteSpecieDialog = false"
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
                            @click="deleteSpecie"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Specie Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteSpeciesDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected Species?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteSpecieDialog = false"
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
                            @click="deleteSelectedSpecies"
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
const specieDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteSpecieDialog = ref(false);
const deleteSpeciesDialog = ref(false);
const specie = ref({});
const selectedSpecies = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    species: Array,
    speciesCategories: Array,
});

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Species" }]);

const species = ref(props.species);

const speciesCategories = ref(props.speciesCategories);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    specie.value = {};
    submitted.value = false;
    specieDialog.value = true;
};

const hideDialog = () => {
    specieDialog.value = false;
    submitted.value = false;
};

const saveSpecie = async () => {
    submitted.value = true;
    if (specie?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (specie.value.id) {
                const response = await axios.put(
                    `/species-list/${specie.value.id}`,
                    specie.value
                );
                updateSpecies(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Species Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post(
                    "/species-list",
                    specie.value
                );
                species.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Specie Created",
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
            specieDialog.value = false;
        }
    }
};

const editSpecie = (specieData) => {
    editDialog.value = true;
    specie.value = { ...specieData };
    specieDialog.value = true;
};

const deleteSpecie = async () => {
    loading.value = true;
    try {
        await axios.delete(`/species-list/${specie.value.id}`);
        species.value = species.value.filter((r) => r.id !== specie.value.id);
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Species Deleted",
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
        deleteSpecieDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSpecie = (specieData) => {
    specie.value = specieData;
    deleteSpecieDialog.value = true;
};

const deleteSelectedSpecies = async () => {
    const ids = selectedSpecies.value.map((specie) => specie.id);
    loading.value = true;
    try {
        await axios.post("/species-list/bulk-delete", { ids });
        species.value = species.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Species Deleted",
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
        deleteSpeciesDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteSpeciesDialog.value = true;
};

const updateSpecies = (updatedSpecies) => {
    const index = species.value.findIndex((r) => r.id === updatedSpecies.id);
    if (index !== -1) {
        species.value[index] = updatedSpecies; // Corrected from `updatedSpecie`
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
