<template>
    <AppLayout title="Confiscates">
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
                                !selectedConfiscates || !selectedConfiscates.length
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
                    v-if="confiscates.length > 0"
                    ref="dt"
                    v-model:selection="selectedConfiscates"
                    :value="confiscates"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Confiscates"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Confiscates</h4>
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
                        field="item"
                        header="Item Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="quantity"
                        header="Quantity"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="suspect.name"
                        header="Suspect"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="encroached.area.name"
                        header="Encroached Area"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="Proof"
                        header="Proof"
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
                                @click="editConfiscate(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteConfiscate(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Confiscates Found</h2>
                </div>
            </div>

            <!-- Add/Edit Confiscates Dialog -->
            <Dialog
                v-model:visible="confiscateDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Confiscate' : 'Add New Confiscate'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            Item Name
                        </label>
                        <InputText
                            id="name"
                            v-model.trim="confiscate.item"
                            required="true"
                            autofocus
                            :invalid="submitted && !confiscate.itemm"
                            fluid
                        />
                        <small
                            v-if="submitted && !confiscate.item"
                            class="text-red-500"
                        >
                            Confiscate Item is required.
                        </small>
                    </div>

                    <div class="col-12 md:col-6">
                        <label for="confiscate" class="block font-bold mb-2"
                            >Suspect</label
                        >
                        <Select
                            id="id"
                            v-model="confiscate.suspect_id"
                            :options="suspects"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Suspect"
                            fluid
                        />
                        <small
                            v-if="submitted && !confiscate.suspect_id"
                            class="text-red-500"
                        >
                            Suspect Name is required.
                        </small>
                    </div>
                    <div class="col-12 md:col-6">
                        <label for="confiscate" class="block font-bold mb-2"
                            >Encroached Area</label
                        >
                        <Select
                            id="id"
                            v-model="confiscate.encroached_area_id"
                            :options="encroached_areas"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Encroached Area"
                            fluid
                        />
                        <small
                            v-if="submitted && !confiscate.encroached_area_id"
                            class="text-red-500"
                        >
                            Encroached Area is required.
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
                            @click="saveConfiscate"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Confiscate Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteConfiscateDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="confiscate">
                        Are you sure you want to delete
                        <b>{{ confiscate.item }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteConfiscateDialog = false"
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
                            @click="deleteConfiscate"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Confiscate Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteConfiscatesDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected Confiscates?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteConfiscateDialog = false"
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
                            @click="deleteSelectedConfiscates"
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
const confiscateDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteConfiscateDialog = ref(false);
const deleteConfiscatesDialog = ref(false);
const confiscate = ref({});
const selectedConfiscates = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    confiscates: Array,
    suspects: Array,
    encroached_areas: Array,
});

const confiscates = ref(props.confiscates);
const suspects = ref(props.suspects);
const encroached_areas = ref(props.encroached_areas)

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Confiscates" }]);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    confiscate.value = {};
    submitted.value = false;
    confiscateDialog.value = true;
};

const hideDialog = () => {
    confiscateDialog.value = false;
    submitted.value = false;
};

const saveConfiscate = async () => {
    submitted.value = true;
    if (confiscate?.value?.item?.trim()) {
        loading.value = true;
        try {
            if (confiscate.value.id) {
                const response = await axios.put(
                    `/confiscates/${confiscate.value.id}`,
                    confiscate.value
                );
                updateConfiscate(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Confiscate Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post("/confiscates", confiscate.value);
                confiscates.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Confiscate Created",
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
            confiscateDialog.value = false;
        }
    }
};

const editConfiscate = (confiscateData) => {
    editDialog.value = true;
    confiscate.value = { ...confiscateData };
    confiscateDialog.value = true;
};

const deleteConfiscate = async () => {
    loading.value = true;
    try {
        await axios.delete(`/confiscate-list/${confiscate.value.id}`);
        confiscates.value = confiscates.value.filter(
            (r) => r.id !== confiscate.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Confiscate Deleted",
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
        deleteConfiscateDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteConfiscate = (confiscateData) => {
    confiscate.value = confiscateData;
    deleteConfiscateDialog.value = true;
};

const deleteSelectedConfiscates = async () => {
    const ids = selectedConfiscates.value.map((confiscate) => confiscate.id);
    loading.value = true;
    try {
        await axios.post("/confiscate-list/bulk-delete", { ids });
        confiscates.value = confiscates.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Confiscate Deleted",
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
        deleteConfiscatesDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteConfiscatesDialog.value = true;
};

const updateConfiscate = (updatedConfiscate) => {
    const index = confiscates.value.findIndex((r) => r.id === updatedConfiscate.id);
    if (index !== -1) {
        confiscate.value[index] = updatedConfiscate;
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