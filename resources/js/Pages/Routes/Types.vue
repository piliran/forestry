<template>
    <AppLayout title="Route Types">
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
                                !selectedRouteTypes ||
                                !selectedRouteTypes.length
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
                    v-if="routeTypes.length > 0"
                    ref="dt"
                    v-model:selection="selectedRouteTypes"
                    :value="routeTypes"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Route Types"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Route Types</h4>
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
                        header="Route Type"
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
                                @click="editRouteType(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteRouteType(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Route Types Found</h2>
                </div>
            </div>

            <!-- Add/Edit Route Types Dialog -->
            <Dialog
                v-model:visible="routeTypeDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Department' : 'Add New Department'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            Route Type Name
                        </label>
                        <InputText
                            id="name"
                            v-model.trim="routeType.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !routeType.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !routeType.name"
                            class="text-red-500"
                        >
                            Route Type Name is required.
                        </small>
                    </div>

                    <div class="col-12">
                        <label for="description" class="block font-bold mb-3"
                            >Description</label
                        >
                        <Textarea
                            id="description"
                            v-model="routeType.description"
                            required="true"
                            rows="3"
                            cols="20"
                            autofocus
                            :invalid="submitted && !routeType.description"
                            fluid
                        />
                        <small
                            v-if="submitted && !routeType.description"
                            class="text-red-500"
                        >
                            Route Type Description is required.
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
                            @click="saveRouteType"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single RouteType Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteRouteTypeDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="routeType">
                        Are you sure you want to delete
                        <b>{{ routeType.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteRouteTypeDialog = false"
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
                            @click="deleteRouteType"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Department Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteRouteTypesDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected Route
                        Types?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteRouteTypesDialog = false"
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
                            @click="deleteSelectedRouteType"
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
const routeTypeDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteRouteTypeDialog = ref(false);
const deleteRouteTypesDialog = ref(false);
const routeType = ref({});
const selectedRouteTypes = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    routeTypes: Array,
});

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "RouteType" }]);

const routeTypes = ref(props.routeTypes);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    routeType.value = {};
    submitted.value = false;
    routeTypeDialog.value = true;
};

const hideDialog = () => {
    routeTypeDialog.value = false;
    submitted.value = false;
};

const saveRouteType = async () => {
    submitted.value = true;
    if (routeType?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (routeType.value.id) {
                const response = await axios.put(
                    `/route-types/${routeType.value.id}`,
                    routeType.value
                );
                updateRouteType(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Route Type Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post(
                    "/route-types",
                    routeType.value
                );
                routeTypes.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Route Type Created",
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
            routeTypeDialog.value = false;
        }
    }
};

const editRouteType = (routeTypeData) => {
    editDialog.value = true;
    routeType.value = { ...routeTypeData };
    routeTypeDialog.value = true;
};

const deleteRouteType = async () => {
    loading.value = true;
    try {
        await axios.delete(`/route-types/${routeType.value.id}`);
        routeTypes.value = routeTypes.value.filter(
            (r) => r.id !== routeType.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Route Type Deleted",
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
        deleteRouteTypeDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteRouteType = (routeTypeData) => {
    routeType.value = routeTypeData;
    deleteRouteTypeDialog.value = true;
};

const deleteSelectedRouteType = async () => {
    const ids = selectedRouteTypes.value.map((routeType) => routeType.id);
    loading.value = true;
    try {
        await axios.post("/route-types/bulk-delete", { ids });
        routeTypes.value = routeTypes.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Route Type Deleted",
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
        deleteRouteTypesDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteRouteTypesDialog.value = true;
};

const updateRouteType = (updatedRouteType) => {
    const index = routeTypes.value.findIndex(
        (r) => r.id === updatedRouteType.id
    );
    if (index !== -1) {
        routeTypes.value[index] = updatedRouteType;
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
