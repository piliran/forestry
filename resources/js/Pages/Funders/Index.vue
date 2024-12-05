<template>
    <AppLayout title="Funders">
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
                                !selectedFunders ||
                                !selectedFunders.length
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
                    v-if="funders.length > 0"
                    ref="dt"
                    v-model:selection="selectedFunders"
                    :value="funders"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Funders"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Funders</h4>
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
                        header="Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="organization"
                        header="Organization"
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
                        field="email"
                        header="Email"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="address"
                        header="Address"
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
                                @click="editFunder(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteFunder(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Funders Found</h2>
                </div>
            </div>

            <!-- Add/Edit Funder Dialog -->
            <Dialog
                v-model:visible="funderDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Funder' : 'Add New Funder'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">                    
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            Name
                        </label>
                        <InputText
                            id="name"
                            v-model.trim="funder.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !funder.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !funder.name"
                            class="text-red-500"
                        >
                            Name is required.
                        </small>
                    </div>
                    <div>
                        <label for="organization" class="block font-bold mb-3">
                            Organization
                        </label>
                        <InputText
                            id="organization"
                            v-model.trim="funder.organization"
                            required="true"
                            autofocus
                            :invalid="submitted && !funder.organization"
                            fluid
                        />
                        <small
                            v-if="submitted && !funder.organization"
                            class="text-red-500"
                        >
                            Organization is required.
                        </small>
                    </div>
                    <div>
                        <label for="phone" class="block font-bold mb-3">
                            Phone
                        </label>
                        <InputText
                            id="phone"
                            v-model.trim="funder.phone"
                            required="true"
                            autofocus
                            :invalid="submitted && !funder.phone"
                            fluid
                        />
                        <small
                            v-if="submitted && !funder.phone"
                            class="text-red-500"
                        >
                            Phone is required.
                        </small>
                    </div>
                    <div>
                        <label for="email" class="block font-bold mb-3">
                            Email
                        </label>
                        <InputText
                            id="email"
                            v-model.trim="funder.email"
                            required="true"
                            autofocus
                            :invalid="submitted && !funder.email"
                            fluid
                        />
                        <small
                            v-if="submitted && !funder.email"
                            class="text-red-500"
                        >
                            Email is required.
                        </small>
                    </div>
                    
                    <div>
                        <label for="address" class="block font-bold mb-3">
                            Address
                        </label>
                        <Textarea
                            id="address"
                            v-model="funder.address"
                            required="true"
                            rows="3"
                            cols="20"
                            autofocus
                            :invalid="submitted && !funder.address"
                            fluid
                            placeholder="Enter Address"
                        />
                        <small
                            v-if="submitted && !funder.address"
                            class="text-red-500"
                        >
                            Address is required.
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
                            @click="saveFunder"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Funder Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteFunderDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="funder">
                        Are you sure you want to delete
                        <b>{{ funder.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteFunderDialog = false"
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
                            @click="deleteFunder"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Funder Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteFundersDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected
                        Funders?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteFundersDialog = false"
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
                            @click="deleteSelectedFunder"
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
import Textarea from 'primevue/textarea';
import ProgressSpinner from "primevue/progressspinner";
import Breadcrumb from "primevue/breadcrumb";
import { Link } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const toast = useToast();

// Reactive State Variables
const dt = ref();
const funderDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteFunderDialog = ref(false);
const deleteFundersDialog = ref(false);
const funder = ref({});
const selectedFunders= ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    funders: Array,
});

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Funder" }]);

const funders = ref(props.funders);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    funder.value = {};
    submitted.value = false;
    funderDialog.value = true;
};

const hideDialog = () => {
    funderDialog.value = false;
    submitted.value = false;
};

const saveFunder = async () => {
    submitted.value = true;
    if (funder?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (funder.value.id) {
                const response = await axios.put(
                    `/funders/${funder.value.id}`,
                    funder.value
                );
                updateFunder(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Funder Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post(
                    "/funders",
                    funder.value
                );
                funders.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Funder Created",
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
            funderDialog.value = false;
        }
    }
};

const editFunder = (funderData) => {
    editDialog.value = true;
    funder.value = { ...funderData };
    funderDialog.value = true;
};

const deleteFunder = async () => {
    loading.value = true;
    try {
        await axios.delete(`/funders/${funder.value.id}`);
        funders.value = funders.value.filter(
            (r) => r.id !== funder.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Funder Deleted",
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
        deleteFunderDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteFunder = (funderData) => {
    funder.value = funderData;
    deleteFunderDialog.value = true;
};

const deleteSelectedFunder = async () => {
    const ids = selectedFunders.value.map((funder) => funder.id);
    loading.value = true;
    try {
        await axios.post("/funders/bulk-delete", { ids });
        funders.value = funders.value.filter(
            (r) => !ids.includes(r.id)
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Funders Deleted",
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
        deleteFundersDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteFundersDialog.value = true;
};

const updateFunder = (updatedFunder) => {
    const index = funders.value.findIndex(
        (r) => r.id === updatedFunder.id
    );
    if (index !== -1) {
        funders.value[index] = updatedFunder;
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
