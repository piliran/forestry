<template>
    <AppLayout title="Operations">
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
                                !selectedOperations ||
                                !selectedOperations.length
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
                    v-if="operations.length > 0"
                    ref="dt"
                    v-model:selection="selectedOperations"
                    :value="operations"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} operations"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Operations</h4>
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
                        header="Operation Name"
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
                        field="operation_type.name"
                        header="Operation Type"
                        sortable
                        style="min-width: 10rem"
                    ></Column>

                    <Column
                        field="station.name"
                        header="Station"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="route.name"
                        header="Route"
                        sortable
                        style="min-width: 10rem"
                    ></Column>

                    <Column
                        header="Add Suspect"
                        :exportable="false"
                        style="min-width: 12rem"
                    >
                        <template #body="slotProps">
                            <Link
                                :href="'/add-suspect/' + slotProps.data.id"
                                preserve-scroll
                            >
                                <Button
                                    label="Manage Suspect"
                                    size="small"
                                    style="
                                        padding-left: 7px;
                                        padding-right: 7px;
                                        font-size: 12px;
                                    "
                                />
                            </Link>
                        </template>
                    </Column>

                    <Column
                        field="funder.name"
                        header="Funded By"
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
                                @click="editOperation(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteOperation(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No operations found</h2>
                </div>
            </div>

            <!-- Add/Edit Operation Dialog -->
            <Dialog
                v-model:visible="operationDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Operation' : 'Add New Operation'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <!-- Operation Name -->
                    <div>
                        <label for="name" class="block font-bold mb-3"
                            >Operation Name</label
                        >
                        <InputText
                            id="name"
                            v-model.trim="operation.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !operation.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !operation.name"
                            class="text-red-500"
                        >
                            Operation Name is required.
                        </small>
                    </div>

                    <div class="col-12">
                        <label for="description" class="block font-bold mb-3"
                            >Operation Description</label
                        >
                        <Textarea
                            id="description"
                            v-model="operation.description"
                            required="true"
                            rows="3"
                            cols="20"
                            autofocus
                            :invalid="submitted && !operation.description"
                            fluid
                        />
                        <small
                            v-if="submitted && !operation.description"
                            class="text-red-500"
                        >
                            Operation Description is required.
                        </small>
                    </div>

                    <!-- Operation Category -->
                    <div>
                        <label for="operationType" class="block font-bold mb-3">
                            Type
                        </label>
                        <Select
                            id="operationType"
                            v-model="operation.operation_type_id"
                            :options="operationTypes"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select a Operation Type"
                            fluid
                            filter
                        />
                    </div>
                    <!-- Operation Station -->
                    <div>
                        <label for="station" class="block font-bold mb-3">
                            Station
                        </label>
                        <Select
                            id="station"
                            v-model="operation.station_id"
                            :options="stations"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select a Station"
                            fluid
                            filter
                        />
                    </div>

                    <!-- Operation Route -->
                    <div>
                        <label for="route" class="block font-bold mb-3">
                            Route
                        </label>
                        <Select
                            id="route"
                            v-model="operation.route_id"
                            :options="routes"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select a Route"
                            fluid
                            filter
                        />
                    </div>

                    <div>
                        <label for="funder" class="block font-bold mb-3"
                            >Funded By</label
                        >
                        <Select
                            id="funded_by"
                            v-model="operation.funded_by"
                            :options="funders"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select a Funder"
                            fluid
                            filter
                        />
                        <small
                            v-if="submitted && !operation.funded_by"
                            class="text-red-500"
                        >
                            Funder is required.
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
                            @click="saveOperation"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Operation Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteOperationDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="operation">
                        Are you sure you want to delete
                        <b>{{ operation.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteOperationDialog = false"
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
                            @click="deleteOperation"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Operations Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteOperationsDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected operations?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteOperationsDialog = false"
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
                            @click="deleteSelectedOperations"
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
import DatePicker from "primevue/datepicker";
import IconField from "primevue/iconfield";
import Select from "primevue/select";
import ProgressSpinner from "primevue/progressspinner";
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";
import Breadcrumb from "primevue/breadcrumb";
import Textarea from "primevue/textarea";

import { Link } from "@inertiajs/vue3";

const toast = useToast();
const dt = ref();
const date = ref();
const operationDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteOperationDialog = ref(false);
const deleteOperationsDialog = ref(false);
const operation = ref({});
const selectedOperations = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Operations" }]);

const props = defineProps({
    operations: Array,
    operationTypes: Array,
    stations: Array,
    routes: Array,
    funders: Array,
});

const operations = ref(props.operations);
const funders = ref(props.funders);
const operationTypes = ref(props.operationTypes);
const stations = ref(props.stations);
const routes = ref(props.routes);
// console.log(operations);
// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    operation.value = {};
    submitted.value = false;
    operationDialog.value = true;
};

const hideDialog = () => {
    operationDialog.value = false;
    submitted.value = false;
};

const saveOperation = async () => {
    submitted.value = true;
    if (operation?.value?.name?.trim()) {
        loading.value = true;
        try {
            const operationPayload = {
                name: operation.value.name,
                description: operation.value.description,
                operation_type_id: operation.value.operation_type_id,
                station_id: operation.value.station_id,
                route_id: operation.value.route_id,
                funded_by: operation.value.funded_by,
            };
            // console.log(operationPayload);
            if (operation.value.id) {
                const response = await axios.put(
                    `/operations-list/${operation.value.id}`,
                    {
                        id: operation.value.id,
                        name: operation.value.name,
                        description: operation.value.description,
                        operation_type_id: operation.value.operation_type_id,
                        station_id: operation.value.station_id,
                        route_id: operation.value.route_id,
                        funded_by: operation.value.funded_by,
                    }
                );
                updateOperation(response.data);
                toast.add({
                    severity: "success",
                    summary: "Success",
                    detail: "Operation updated successfully.",
                    life: 5000,
                });
            } else {
                const response = await axios.post(
                    "/operations-list",
                    operationPayload
                );
                operations.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Success",
                    detail: "Operation added successfully.",
                    life: 5000,
                });
            }

            hideDialog();
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
        }
    }
};

const editOperation = (operationData) => {
    editDialog.value = true;

    operation.value = { ...operationData };
    if (operationData.route != null) {
        operation.value.route_id = operationData.route.id;
    }
    if (operationData.funder != null) {
        operation.value.funded_by = operationData.funder.id;
    }
    // operation.value.route_id = operationData.route.id;
    // operation.value.operation_type_id = operationData.operation_type.id;
    // operation.value.funded_by = operationData.funder.id;
    // console.log(operationData);

    if (Array.isArray(operationData.type)) {
        operation.value.type = operationData.operation_type.map((p) => p.id);
    } else if (operationData.type && typeof operationData.type === "object") {
        operation.value.type = [operationData.type.id];
    } else {
        operation.value.type = [];
    }

    operationDialog.value = true;
};

const confirmDeleteOperation = (op) => {
    operation.value = op;
    deleteOperationDialog.value = true;
};

const deleteOperation = async () => {
    loading.value = true;

    try {
        await axios.delete(`/operations-list/${operation.value.id}`);
        operations.value = operations.value.filter(
            (val) => val.id !== operation.value.id
        );
        deleteOperationDialog.value = false;
        operation.value = {};
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Operation Deleted",
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
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteOperationsDialog.value = true;
};

const deleteSelectedOperations = async () => {
    loading.value = true;

    try {
        const idsToDelete = selectedOperations.value.map((cat) => cat.id);
        await axios.post(`/operations-list/bulk-delete`, { ids: idsToDelete });
        operations.value = operations.value.filter(
            (val) => !selectedOperations.value.includes(val)
        );
        deleteOperationsDialog.value = false;
        selectedOperations.value = null;
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Operations Deleted",
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
        loading.value = false;
    }
};

// const updateOperation = (updatedOperations) => {
//     const index = operations.value.findIndex(
//         (r) => r.id === updatedOperations.id
//     );
//     if (index !== -1) {
//         operations.value[index] = updatedOperations;
//     }
// };

const updateOperation = (updatedOperation) => {
    const index = operations.value.findIndex(
        (r) => r.id === updatedOperation.id
    );
    if (index !== -1) {
        operations.value[index] = updatedOperation;
        operations.value = [...operations.value]; // Ensure reactivity
    }
};

// const updateRole = (updatedRole) => {
//     const index = roles.value.findIndex((r) => r.id === updatedRole.id);
//     if (index !== -1) {
//         roles.value[index] = updatedRole;
//     }
// };

const exportCSV = () => {
    dt.value.exportCSV();
};
</script>
<style scoped>
::v-deep(.p-breadcrumb) {
    background: transparent !important;
    box-shadow: none !important;
}
</style>
