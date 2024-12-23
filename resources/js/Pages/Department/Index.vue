<template>
    <AppLayout title="Departments">
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
                                !selectedDepartments ||
                                !selectedDepartments.length
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
                    v-if="departments.length > 0"
                    ref="dt"
                    v-model:selection="selectedDepartments"
                    :value="departments"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Departments"
                    @row-click="onRowClick"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Departments</h4>
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
                        field="code"
                        header="Code"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="name"
                        header="Department Name"
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
                        field="website"
                        header="Website"
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
                        field="contact_person.name"
                        header="Contact Person"
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
                                class="mx-2"
                                @click="editDepartment(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteDepartment(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Departments Found</h2>
                </div>
            </div>

            <!-- Add/Edit Department Dialog -->
            <Dialog
                v-model:visible="departmentDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Department' : 'Add New Department'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="code" class="block font-bold mb-3">
                            Code
                        </label>
                        <InputText
                            id="code"
                            v-model.trim="department.code"
                            required="true"
                            autofocus
                            :invalid="submitted && !department.code"
                            fluid
                        />
                        <small
                            v-if="submitted && !department.code"
                            class="text-red-500"
                        >
                            Department Code is required.
                        </small>
                    </div>
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            Department Name
                        </label>
                        <InputText
                            id="name"
                            v-model.trim="department.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !department.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !department.name"
                            class="text-red-500"
                        >
                            Department Name is required.
                        </small>
                    </div>
                    <div>
                        <label for="phone" class="block font-bold mb-3">
                            Phone
                        </label>
                        <InputText
                            id="phone"
                            v-model.trim="department.phone"
                            required="true"
                            autofocus
                            :invalid="submitted && !department.phone"
                            fluid
                        />
                        <small
                            v-if="submitted && !department.phone"
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
                            v-model.trim="department.email"
                            required="true"
                            autofocus
                            :invalid="submitted && !department.email"
                            fluid
                        />
                        <small
                            v-if="submitted && !department.email"
                            class="text-red-500"
                        >
                            Email is required.
                        </small>
                    </div>
                    <div>
                        <label for="website" class="block font-bold mb-3">
                            Website
                        </label>
                        <InputText
                            id="website"
                            v-model.trim="department.website"
                            required="true"
                            autofocus
                            :invalid="submitted && !department.website"
                            fluid
                        />
                        <small
                            v-if="submitted && !department.website"
                            class="text-red-500"
                        >
                            Website is required.
                        </small>
                    </div>
                    <div>
                        <label for="location" class="block font-bold mb-3">
                            Location
                        </label>
                        <InputText
                            id="location"
                            v-model.trim="department.location"
                            required="true"
                            autofocus
                            :invalid="submitted && !department.location"
                            fluid
                        />
                        <small
                            v-if="submitted && !department.location"
                            class="text-red-500"
                        >
                            Location is required.
                        </small>
                    </div>

                    <div class="col-12 md:col-6">
                        <label for="contact_person" class="block font-bold mb-2"
                            >Contact Person</label
                        >
                        <Select
                            id="id"
                            v-model="department.contact_person"
                            :options="staffList"
                            optionLabel="user.name"
                            optionValue="user.id"
                            placeholder="Select Contact Person"
                            fluid
                            filter
                        />
                        <small
                            v-if="submitted && !department.contact_person"
                            class="text-red-500"
                        >
                            Contact person is required.
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
                            @click="saveDepartment"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Department Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteDepartmentDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="department">
                        Are you sure you want to delete
                        <b>{{ department.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteDepartmentDialog = false"
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
                            @click="deleteDepartment"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Department Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteDepartmentsDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected
                        Departments?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteDepartmentsDialog = false"
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
                            @click="deleteSelectedDepartment"
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
import { Link, router } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const toast = useToast();

// Reactive State Variables
const dt = ref();
const departmentDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteDepartmentDialog = ref(false);
const deleteDepartmentsDialog = ref(false);
const department = ref({});
const selectedDepartments = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    departments: Array,
    users: Array,
    staffList: Array,
});

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Departments" }]);
const onRowClick = (rowData) => {
    const departmentId = rowData.data.id;
    router.visit(`${router.page.url}/${departmentId}`);
};
const departments = ref(props.departments);
const users = ref(props.users);
const staffList = ref(props.staffList);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    department.value = {};
    submitted.value = false;
    departmentDialog.value = true;
};

const hideDialog = () => {
    departmentDialog.value = false;
    submitted.value = false;
};

const saveDepartment = async () => {
    submitted.value = true;
    if (department?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (department.value.id) {
                const response = await axios.put(
                    `/departments/${department.value.id}`,
                    department.value
                );
                updateDepartment(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Department Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post(
                    "/departments",
                    department.value
                );
                departments.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Department Created",
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
            departmentDialog.value = false;
        }
    }
};

const editDepartment = (departmentData) => {
    editDialog.value = true;
    department.value = { ...departmentData };
    // console.log(departmentData);
    department.value.contact_person = departmentData.contact_person.id;

    departmentDialog.value = true;
};

const deleteDepartment = async () => {
    loading.value = true;
    try {
        await axios.delete(`/departments/${department.value.id}`);
        departments.value = departments.value.filter(
            (r) => r.id !== department.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Department Deleted",
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
        deleteDepartmentDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteDepartment = (departmentData) => {
    department.value = departmentData;
    deleteDepartmentDialog.value = true;
};

const deleteSelectedDepartment = async () => {
    const ids = selectedDepartments.value.map((department) => department.id);
    loading.value = true;
    try {
        await axios.post("/departments/bulk-delete", { ids });
        departments.value = departments.value.filter(
            (r) => !ids.includes(r.id)
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Departments Deleted",
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
        deleteDepartmentsDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteDepartmentsDialog.value = true;
};

const updateDepartment = (updatedDepartment) => {
    const index = departments.value.findIndex(
        (r) => r.id === updatedDepartment.id
    );
    if (index !== -1) {
        departments.value[index] = updatedDepartment;
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
