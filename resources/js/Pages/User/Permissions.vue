<template>
    <AppLayout title="Permissions">
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
                            :disabled="
                                !selectedPermissions ||
                                !selectedPermissions.length
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
                    v-if="permissions.length > 0"
                    ref="dt"
                    v-model:selection="selectedPermissions"
                    :value="permissions"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} permissions"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Permissions</h4>
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
                        header="Permission Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="description"
                        header="Description"
                        sortable
                        style="min-width: 15rem"
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
                                @click="editPermission(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeletePermission(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Permissions Found</h2>
                </div>
            </div>

            <!-- Add/Edit Permission Dialog -->
            <Dialog
                v-model:visible="permissionDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Permission' : 'Add New Permission'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            Permission Name
                        </label>
                        <InputText
                            id="name"
                            v-model.trim="permission.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !permission.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !permission.name"
                            class="text-red-500"
                        >
                            Permission Name is required.
                        </small>
                    </div>
                    <div>
                        <label for="description" class="block font-bold mb-3">
                            Description
                        </label>
                        <InputText
                            id="description"
                            v-model.trim="permission.description"
                            required="true"
                            fluid
                        />
                        <small
                            v-if="submitted && !permission.description"
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
                            @click="savePermission"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Permission Confirmation Dialog -->
            <Dialog
                v-model:visible="deletePermissionDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="permission">
                        Are you sure you want to delete
                        <b>{{ permission.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deletePermissionDialog = false"
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
                            @click="deletePermission"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Permissions Confirmation Dialog -->
            <Dialog
                v-model:visible="deletePermissionsDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected
                        Permissions?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deletePermissionsDialog = false"
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
                            @click="deleteSelectedPermissions"
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
import ProgressSpinner from "primevue/progressspinner";

import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const toast = useToast();

const dt = ref();
const permissionDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deletePermissionDialog = ref(false);
const deletePermissionsDialog = ref(false);
const permission = ref({});
const selectedPermissions = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    permissions: Array,
});

const permissions = ref(props.permissions);

const openNew = () => {
    editDialog.value = false;
    permission.value = {};
    submitted.value = false;
    permissionDialog.value = true;
};

const hideDialog = () => {
    permissionDialog.value = false;
    submitted.value = false;
};

const savePermission = async () => {
    submitted.value = true;
    if (permission?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (permission.value.id) {
                const response = await axios.put(
                    `/permissions/${permission.value.id}`,
                    permission.value
                );
                updatePermission(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Permission Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post(
                    "/permissions",
                    permission.value
                );
                permissions.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Permission Added",
                    life: 3000,
                });
            }
            permissionDialog.value = false;
        } catch (error) {
            console.error("Error saving permission", error);
        } finally {
            loading.value = false;
        }
    }
};

const editPermission = (perm) => {
    editDialog.value = true;
    permission.value = { ...perm };
    permissionDialog.value = true;
};

const confirmDeletePermission = (perm) => {
    permission.value = perm;
    deletePermissionDialog.value = true;
};

const deletePermission = async () => {
    loading.value = true;
    try {
        const response = await axios.delete(
            `/permissions/${permission.value.id}`
        );
        permissions.value = permissions.value.filter(
            (perm) => perm.id !== permission.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: response.data.message,
            life: 3000,
        });
        deletePermissionDialog.value = false;
    } catch (error) {
        console.error("Error deleting permission", error);
    } finally {
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deletePermissionsDialog.value = true;
};

const deleteSelectedPermissions = async () => {
    const ids = selectedPermissions.value.map((perm) => perm.id);
    loading.value = true;
    try {
        const response = await axios.post(`/permissions/bulk-delete`, {
            ids,
        });
        permissions.value = permissions.value.filter(
            (perm) => !selectedPermissions.value.includes(perm)
        );
        selectedPermissions.value = [];
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: response.data.message,
            life: 3000,
        });
        deletePermissionsDialog.value = false;
    } catch (error) {
        console.error("Error deleting permissions", error);
    } finally {
        loading.value = false;
    }
};

const updatePermission = (updatePermission) => {
    const index = permissions.value.findIndex(
        (r) => r.id === updatePermission.id
    );
    if (index !== -1) {
        permissions.value[index] = updatePermission;
    }
};
</script>
