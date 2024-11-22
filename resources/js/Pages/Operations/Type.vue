<template>
    <AppLayout title="Operations Type">
        <div>
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
                            @click="exportCSV"
                        />
                    </template>
                </Toolbar>

                <Toast />

                <DataTable
                    v-if="operations.length > 0"
                    ref="dt"
                    v-model:selection="selectedOperations"
                    :value="operations"
                    dataKey="id"
                    paginator
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} operations types"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Operations Types</h4>
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
                        field="description"
                        header="Description"
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
                    <h2>No operations types found</h2>
                </div>
            </div>

            <!-- Add/Edit Dialog -->
            <Dialog
                v-model:visible="operationDialog"
                :style="{ width: '450px' }"
                :header="
                    editDialog
                        ? 'Edit Operation Type'
                        : 'Add New Operation Type'
                "
                modal
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3"
                            >Name</label
                        >
                        <InputText
                            id="name"
                            v-model.trim="operation.name"
                            required
                            autofocus
                            :invalid="submitted && !operation.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !operation.name"
                            class="text-red-500"
                        >
                            Name is required.
                        </small>
                    </div>
                    <div>
                        <label for="description" class="block font-bold mb-3"
                            >Description</label
                        >
                        <InputText
                            id="description"
                            v-model.trim="operation.description"
                            required
                            autofocus
                            :invalid="submitted && !operation.description"
                            fluid
                        />
                        <small
                            v-if="submitted && !operation.description"
                            class="text-red-500"
                        >
                            Description is required.
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

            <!-- Delete Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteOperationDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                modal
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="operation"
                        >Are you sure you want to delete
                        <b>{{ operation.name }}</b
                        >?</span
                    >
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

            <!-- Bulk Delete Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteOperationsDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                modal
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="selectedOperations"
                        >Are you sure you want to delete the selected operations
                        types?</span
                    >
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

import { useToast } from "primevue/usetoast";
import axios from "axios";
import AppLayout from "@/Layouts/AppLayout.vue";

import {
    Toast,
    DataTable,
    Column,
    Button,
    Toolbar,
    Dialog,
    InputIcon,
    InputText,
    IconField,
    ProgressSpinner,
} from "primevue";

const toast = useToast();
const dt = ref();
const operationDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteOperationDialog = ref(false);
const deleteOperationsDialog = ref(false);
const operation = ref({});
const selectedOperations = ref([]);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const submitted = ref(false);

const props = defineProps({
    operationTypes: Array,
});

const operations = ref(props.operationTypes);

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

    if (operation.value.name?.trim()) {
        loading.value = true;
        try {
            if (operation.value.id) {
                // Update
                const response = await axios.put(
                    `/operations-types/${operation.value.id}`,
                    operation.value
                );
                const index = operations.value.findIndex(
                    (op) => op.id === operation.value.id
                );
                if (index !== -1) operations.value[index] = response.data;

                toast.add({
                    severity: "success",
                    summary: "Updated",
                    detail: "Operation type updated",
                    life: 3000,
                });
            } else {
                // Create
                const response = await axios.post(
                    "/operations-types",
                    operation.value
                );
                operations.value.push(response.data);

                toast.add({
                    severity: "success",
                    summary: "Created",
                    detail: "Operation type added",
                    life: 3000,
                });
            }
            operationDialog.value = false;
            operation.value = {};
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

const editOperation = (op) => {
    editDialog.value = true;
    operation.value = { ...op };

    operationDialog.value = true;
};

const confirmDeleteOperation = (op) => {
    operation.value = op;
    deleteOperationDialog.value = true;
};

const deleteOperation = async () => {
    loading.value = true;
    try {
        await axios.delete(`/operations-types/${operation.value.id}`);
        operations.value = operations.value.filter(
            (op) => op.id !== operation.value.id
        );

        toast.add({
            severity: "success",
            summary: "Deleted",
            detail: "Operation type deleted",
            life: 3000,
        });
        deleteOperationDialog.value = false;
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
        await axios.post("/operations-types/delete-multiple", {
            ids: selectedOperations.value.map((op) => op.id),
        });
        operations.value = operations.value.filter(
            (op) => !selectedOperations.value.includes(op)
        );

        toast.add({
            severity: "success",
            summary: "Deleted",
            detail: "Selected operations deleted",
            life: 3000,
        });
        deleteOperationsDialog.value = false;
        selectedOperations.value = [];
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

const exportCSV = () => {
    dt.value?.exportCSV();
};
</script>

<style>
/* Add your custom styling here if needed */
</style>
