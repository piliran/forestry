<template>
    <AppLayout title="Route List">
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
                                !selectedRoutes || !selectedRoutes.length
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
                    v-if="routes.length > 0"
                    ref="dt"
                    v-model:selection="selectedRoutes"
                    :value="routes"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Routes"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Routes</h4>
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
                        header="Route Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="area.name"
                        header="Area"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="code"
                        header="code"
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
                        field="route_type.name"
                        header="Route type"
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
                                @click="editRoute(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteRoute(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Routes Found</h2>
                </div>
            </div>

            <!-- Add/Edit Routes Dialog -->
            <Dialog
                v-model:visible="routeDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Route' : 'Add New Route'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            Route Name
                        </label>
                        <InputText
                            id="name"
                            v-model.trim="route.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !route.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !route.name"
                            class="text-red-500"
                        >
                            Route Name is required.
                        </small>
                    </div>

                    <div class="col-12 md:col-6">
                        <label for="route" class="block font-bold mb-2"
                            >Area Name</label
                        >
                        <Select
                            id="id"
                            v-model="route.area_id"
                            :options="areas"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Area"
                            fluid
                        />
                        <small
                            v-if="submitted && !route.area_id"
                            class="text-red-500"
                        >
                            Area Name is required.
                        </small>
                    </div>
                    <div>
                        <label for="code" class="block font-bold mb-3">
                            Code
                        </label>
                        <InputText
                            id="code"
                            v-model.trim="route.code"
                            required="true"
                            autofocus
                            :invalid="submitted && !route.code"
                            fluid
                        />
                        <small
                            v-if="submitted && !route.code"
                            class="text-red-500"
                        >
                            Route Code is required.
                        </small>
                    </div>
                    <div>
                        <label for="location" class="block font-bold mb-3">
                            Location
                        </label>
                        <InputText
                            id="location"
                            v-model.trim="route.location"
                            required="true"
                            autofocus
                            :invalid="submitted && !route.location"
                            fluid
                        />
                        <small
                            v-if="submitted && !route.location"
                            class="text-red-500"
                        >
                            Route Location is required.
                        </small>
                    </div>

                    <div class="col-12 md:col-6">
                        <label for="route" class="block font-bold mb-2"
                            >Route type</label
                        >
                        <Select
                            id="id"
                            v-model="route.route_type_id"
                            :options="routeTypes"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Route Type"
                            fluid
                        />
                        <small
                            v-if="submitted && !route.area_id"
                            class="text-red-500"
                        >
                            Route Type is required.
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
                            @click="saveRoute"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Route Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteRouteDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="route">
                        Are you sure you want to delete
                        <b>{{ route.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteRouteDialog = false"
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
                            @click="deleteRoute"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Route Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteRoutesDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected Routes?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteRouteDialog = false"
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
                            @click="deleteSelectedRoutes"
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

const breadCumbItems = ref([{ label: "Route List" }]);

// Reactive State Variables
const dt = ref();
const routeDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteRouteDialog = ref(false);
const deleteRoutesDialog = ref(false);
const route = ref({});
const selectedRoutes = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    routes: Array,
    areas: Array,
    routeTypes: Array,
});

const routes = ref(props.routes);
const areas = ref(props.areas);
const routeTypes = ref(props.routeTypes);
console.log(routes);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    route.value = {};
    submitted.value = false;
    routeDialog.value = true;
};

const hideDialog = () => {
    routeDialog.value = false;
    submitted.value = false;
};

const saveRoute = async () => {
    submitted.value = true;
    if (route?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (route.value.id) {
                const response = await axios.put(
                    `/route-list/${route.value.id}`,
                    route.value
                );
                updateRoute(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Route Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post("/route-list", route.value);
                routes.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Route Created",
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
            routeDialog.value = false;
        }
    }
};

const editRoute = (routeData) => {
    editDialog.value = true;
    route.value = { ...routeData };
    routeDialog.value = true;
};

const deleteRoute = async () => {
    loading.value = true;
    try {
        await axios.delete(`/route-list/${route.value.id}`);
        routes.value = routes.value.filter((r) => r.id !== route.value.id);
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Route Deleted",
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
        deleteRouteDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteRoute = (routeData) => {
    route.value = routeData;
    deleteRouteDialog.value = true;
};

const deleteSelectedRoutes = async () => {
    const ids = selectedRoutes.value.map((route) => route.id);
    loading.value = true;
    try {
        await axios.post("/route-list/bulk-delete", { ids });
        routes.value = routes.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Routes Deleted",
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
        deleteRoutesDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteRoutesDialog.value = true;
};

const updateRoute = (updatedRoute) => {
    const index = routes.value.findIndex((r) => r.id === updatedRoute.id);
    if (index !== -1) {
        routes.value[index] = updatedRoute;
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
