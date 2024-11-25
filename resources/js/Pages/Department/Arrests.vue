<template>
    <AppLayout title="Arrests">
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
                                !selectedArrests || !selectedArrests.length
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
                    v-if="arrests.length > 0"
                    ref="dt"
                    v-model:selection="selectedArrests"
                    :value="arrests"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} arrests"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Arrests</h4>
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
                        field="description"
                        header="Description"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="date"
                        header="Date"
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
                        field="proof"
                        header="Proof"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="suspect.name"
                        header="Suspect Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="confiscate.item"
                        header="Confiscates"
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
                                @click="editArrest(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteArrest(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Arrests Found</h2>
                </div>
            </div>

            <!-- Add/Edit Arrests Dialog -->
            <Dialog
                v-model:visible="arrestDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Arrest' : 'Add New Arrest'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div class="col-12">
                        <label for="description" class="block font-bold mb-3"
                            >Description</label
                        >
                        <Textarea
                            id="description"
                            v-model="arrest.description"
                            required="true"
                            rows="3"
                            cols="20"
                            autofocus
                            :invalid="submitted && !arrest.description"
                            fluid
                        />
                        <small
                            v-if="submitted && !arrest.description"
                            class="text-red-500"
                        >
                            Arrest Description is required.
                        </small>
                    </div>

                    <div>
                        <label for="date" class="block font-bold mb-3">
                            Date
                        </label>

                        <DatePicker
                            fluid
                            v-model="arrest.date"
                            dateFormat="dd/mm/yy"
                            placeholder="YYYY-MM-DD"
                        />
                        <small
                            v-if="submitted && !arrest.date"
                            class="text-red-500"
                        >
                            Arrest Date is required.
                        </small>
                    </div>

                    <!-- <div>
                        <label for="location" class="block font-bold mb-3">
                            Location
                        </label>
                        <InputText
                            id="location"
                            v-model.trim="arrest.location"
                            required="true"
                            autofocus
                            :invalid="submitted && !arrest.location"
                            fluid
                        />
                        <small
                            v-if="submitted && !arrest.location"
                            class="text-red-500"
                        >
                            Location is required.
                        </small>
                    </div> -->
                    <!-- <div>
                        <label for="proof" class="block font-bold mb-3">
                            Proof
                        </label>
                        <InputText
                            id="proof"
                            v-model.trim="arrest.proof"
                            required="true"
                            autofocus
                            :invalid="submitted && !arrest.proof"
                            fluid
                        />
                        <small
                            v-if="submitted && !arrest.proof"
                            class="text-red-500"
                        >
                            Proof is required.
                        </small>
                    </div> -->

                    <div class="col-12 md:col-6">
                        <label for="suspects" class="block font-bold mb-2"
                            >Suspect</label
                        >
                        <Select
                            id="id"
                            v-model="arrest.suspect_id"
                            :options="suspects"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Suspect"
                            fluid
                        />
                        <small
                            v-if="submitted && !suspect.suspect_id"
                            class="text-red-500"
                        >
                            Suspect is required.
                        </small>
                    </div>

                    <div class="col-12 md:col-6">
                        <label for="confiscate" class="block font-bold mb-2"
                            >Confiscate</label
                        >
                        <Select
                            id="id"
                            v-model="arrest.confiscate_id"
                            :options="confiscates"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Confiscates"
                            fluid
                        />
                        <small
                            v-if="submitted && !confiscate.confiscate_id"
                            class="text-red-500"
                        >
                            Confiscate is required.
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
                            @click="saveArrest"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Arrest Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteArrestDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="arrest">
                        Are you sure you want to delete
                        <b>{{ arrest.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteArrestDialog = false"
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
                            @click="deleteArrest"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple DistrAArrestict Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteArrestsDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected Arrests?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteArrestDialog = false"
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
                            @click="deleteSelectedArrests"
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

import Textarea from "primevue/textarea";

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
import DatePicker from "primevue/datepicker";

import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const toast = useToast();

// Reactive State Variables
const dt = ref();
const arrestDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteArrestDialog = ref(false);
const deleteArrestsDialog = ref(false);
const arrest = ref({});
const selectedArrests = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    arrests: Array,
    suspects: Array,
    confiscates: Array,
});

const arrests = ref(props.arrests);
const suspects = ref(props.suspects);
const confiscates = ref(props.confiscates);

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Arrests" }]);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    arrest.value = {};
    submitted.value = false;
    arrestDialog.value = true;
};

const hideDialog = () => {
    arrestDialog.value = false;
    submitted.value = false;
};

const saveArrest = async () => {
    submitted.value = true;
    if (arrest?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (arrest.value.id) {
                const response = await axios.put(
                    `/arrests/${arrest.value.id}`,
                    arrest.value
                );
                updateArrest(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Arrest Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post("/arrests", arrest.value);
                arrests.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Arrest Created",
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
            arrestDialog.value = false;
        }
    }
};

const editArea = (arrestData) => {
    editDialog.value = true;
    arrest.value = { ...arrestData };
    arrestDialog.value = true;
};

const deleteArrest = async () => {
    loading.value = true;
    try {
        await axios.delete(`/arrests/${arest.value.id}`);
        arrests.value = arrests.value.filter((r) => r.id !== arrest.value.id);
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Arrest Deleted",
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
        deleteArrestDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteArrest = (arrestData) => {
    arrest.value = arrestData;
    deleteArrestDialog.value = true;
};

const deleteSelectedArrests = async () => {
    const ids = selectedArrests.value.map((arrest) => arrest.id);
    loading.value = true;
    try {
        await axios.post("/arrests/bulk-delete", { ids });
        arrests.value = arrests.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Arrests Deleted",
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
        deleteArrestsDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteArrestsDialog.value = true;
};

const updateArrest = (updatedArrest) => {
    const index = arrests.value.findIndex((r) => r.id === updatedArrest.id);
    if (index !== -1) {
        arrests.value[index] = updatedArrest;
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
