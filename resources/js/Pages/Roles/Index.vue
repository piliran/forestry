<template>
    <AppLayout title="Roles">
        <div>
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
                            :disabled="!selectedRoles || !selectedRoles.length"
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
                    v-if="roles.length > 0"
                    ref="dt"
                    v-model:selection="selectedRoles"
                    :value="roles"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} roles"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Roles</h4>
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
                        header="Role Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        header="Category Name"
                        field="category.name"
                        sortable
                        style="min-width: 12rem"
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
                                @click="editRole(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteRole(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No roles found</h2>
                </div>
            </div>

            <!-- Add/Edit Role Dialog -->
            <Dialog
                v-model:visible="roleDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Role' : 'Add New Role'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <!-- Role Name -->
                    <div>
                        <label for="name" class="block font-bold mb-3"
                            >Role Name</label
                        >
                        <InputText
                            id="name"
                            v-model.trim="role.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !role.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !role.name"
                            class="text-red-500"
                        >
                            Role Name is required.
                        </small>
                    </div>

                    <!-- Role Category -->
                    <div>
                        <label for="roleCategory" class="block font-bold mb-3">
                            Role Category
                        </label>
                        <Select
                            id="roleCategory"
                            v-model="role.role_category_id"
                            :options="roleCategories"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select a Role Category"
                            fluid
                        />
                    </div>

                    <!-- Permissions -->
                    <div>
                        <label for="permissions" class="block font-bold mb-3">
                            Permissions
                        </label>
                        <MultiSelect
                            id="permissions"
                            v-model="role.permissions"
                            :options="props.permissions"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Permissions"
                            fluid
                        />
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
                            @click="saveRole"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Role Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteRoleDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="role">
                        Are you sure you want to delete <b>{{ role.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteRoleDialog = false"
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
                            @click="deleteRole"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Roles Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteRolesDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected roles?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteRolesDialog = false"
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
                            @click="deleteSelectedRoles"
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
import MultiSelect from "primevue/multiselect";
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const toast = useToast();

// Reactive State Variables
const dt = ref();
const roleDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteRoleDialog = ref(false);
const deleteRolesDialog = ref(false);
const role = ref({});
const selectedRoles = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

role.value = {
    name: "",
    role_category_id: null,
    permissions: [],
};

const props = defineProps({
    roles: Array,
    roleCategories: Array,
    permissions: Array,
});

const roles = ref(props.roles);
const roleCategories = ref(props.roleCategories);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    role.value = {};
    submitted.value = false;
    roleDialog.value = true;
};

const hideDialog = () => {
    roleDialog.value = false;
    submitted.value = false;
};

const saveRole = async () => {
    submitted.value = true;
    if (role?.value?.name?.trim()) {
        loading.value = true;
        try {
            const rolePayload = {
                name: role.value.name,
                role_category_id: role.value.role_category_id,
                permissions: role.value.permissions, // Send permissions to the backend
            };

            if (role.value.id) {
                // Update existing role
                const response = await axios.put(
                    `/roles/${role.value.id}`,
                    rolePayload
                );
                updateRole(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Role Updated",
                    life: 3000,
                });
            } else {
                // Create new role
                const response = await axios.post("/roles", rolePayload);
                roles.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Role Created",
                    life: 3000,
                });
            }
        } catch (err) {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to save role.",
                life: 3000,
            });
        } finally {
            loading.value = false;
            roleDialog.value = false;
        }
    }
};

const editRole = (roleData) => {
    editDialog.value = true;
    role.value = { ...roleData };
    // Set the selected permissions for this role
    role.value.permissions = roleData.permissions.map((p) => p.id); // Assuming permissions are objects with 'id' and 'name'
    roleDialog.value = true;
};

const deleteRole = async () => {
    loading.value = true;
    try {
        await axios.delete(`/roles/${role.value.id}`);
        roles.value = roles.value.filter((r) => r.id !== role.value.id);
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Role Deleted",
            life: 3000,
        });
    } catch (err) {
    } finally {
        deleteRoleDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteRole = (roleData) => {
    role.value = roleData;
    deleteRoleDialog.value = true;
};

const deleteSelectedRoles = async () => {
    const ids = selectedRoles.value.map((role) => role.id);
    loading.value = true;
    try {
        await axios.post("/roles/bulk-delete", { ids });
        roles.value = roles.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Roles Deleted",
            life: 3000,
        });
    } catch (err) {
    } finally {
        deleteRolesDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteRolesDialog.value = true;
};

const updateRole = (updatedRole) => {
    const index = roles.value.findIndex((r) => r.id === updatedRole.id);
    if (index !== -1) {
        roles.value[index] = updatedRole;
    }
};

const exportCSV = () => {
    dt.value?.exportCSV();
};
</script>
