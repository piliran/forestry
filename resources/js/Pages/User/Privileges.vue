<template>
    <AppLayout title="Privileges">
        <div ref="formContainer">
            <!-- Breadcrumb -->
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
                            <span class="text-surface-700 dark:text-surface-0">
                                {{ item.label }}
                            </span>
                        </a>
                    </template>
                </Breadcrumb>
            </div>

            <!-- Toolbar -->
            <div class="card">
                <!-- <Toolbar class="mb-6">
                    <template #start>
                        <Button
                            label="New"
                            icon="pi pi-plus"
                            class="mr-2"
                            @click="openNew"
                        />
                        <Button
                            v-if="selectedPrivileges"
                            label="Delete"
                            icon="pi pi-trash"
                            severity="danger"
                            outlined
                            @click="confirmDeleteSelected"
                            :disabled="
                                !selectedPrivileges ||
                                !selectedPrivileges.length
                            "
                        />
                    </template>
                    <template #end>
                        <ToggleButton
                            v-model="checked"
                            onLabel="Locked"
                            offLabel="Unlocked"
                            onIcon="pi pi-lock"
                            offIcon="pi pi-lock-open"
                            class="w-30 mr-2"
                            aria-label="Do you confirm"
                        />
                        <Button
                            label="Export"
                            icon="pi pi-upload"
                            severity="secondary"
                            @click="exportCSV($event)"
                        />
                    </template>
                </Toolbar> -->

                <!-- Toast Notifications -->
                <Toast />
            </div>

            <!-- Responsive Panels -->
            <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="table in tables" :key="table.id" class="card">
                    <Panel :header="table.name" toggleable>
                        <div class="card flex flex-wrap gap-4">
                            <div
                                v-for="(permission, index) in permissions"
                                :key="permission.id"
                                class="flex items-center gap-2"
                            >
                                <Checkbox
                                    :id="
                                        'permission' +
                                        table.id +
                                        '-' +
                                        permission.id
                                    "
                                    v-model="selectedPermissions[table.id]"
                                    :inputId="
                                        'permission' +
                                        table.id +
                                        '-' +
                                        permission.id
                                    "
                                    name="permission"
                                    :value="permission.id"
                                    :checked="
                                        table.table_to_permissions.some(
                                            (tp) =>
                                                tp.permission_id ===
                                                permission.id
                                        )
                                    "
                                    @change="
                                        handleCheckboxChange(
                                            table.id,
                                            permission.id,
                                            $event.target.checked
                                        )
                                    "
                                />
                                <label
                                    :for="
                                        'permission' +
                                        table.id +
                                        '-' +
                                        permission.id
                                    "
                                >
                                    {{ permission.name }}
                                </label>
                            </div>
                        </div>
                    </Panel>
                </div>
            </div>
        </div>
        <div class="absolute bottom-4 right-8">
            <div>
                <!-- <ProgressSpinner
                    v-if="loading"
                    style="width: 40px; height: 40px"
                    strokeWidth="4"
                    fill="transparent"
                    animationDuration=".5s"
                    aria-label="Custom ProgressSpinner"
                /> -->
                <Button
                    label="Save"
                    icon="pi pi-check"
                    :disabled="loading"
                    @click="saveTablePermission"
                />
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import Toast from "primevue/toast";
import Button from "primevue/button";
import Toolbar from "primevue/toolbar";
import Checkbox from "primevue/checkbox";

import { useToast } from "primevue/usetoast";
import ToggleButton from "primevue/togglebutton";
import ProgressSpinner from "primevue/progressspinner";

import Panel from "primevue/panel";
import Breadcrumb from "primevue/breadcrumb";
import { Link } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import { useLoading } from "vue-loading-overlay";

const fullPage = ref(false);
const formContainer = ref(null);
const $loading = useLoading({
    container: fullPage.value ? null : formContainer.value,
    loader: "spinner",
    width: 64,
    height: 64,
    backgroundColor: "#ffffff",
    opacity: 0.5,
    zIndex: 999,
    color: "#4caf50",
});
const toast = useToast();
const loading = ref(false);
const submitted = ref(false);

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});
const breadCumbItems = ref([{ label: "Privileges" }]);

const props = defineProps({
    tablePermissions: Array,
    tablePrivileges: Array,
    tables: Array,
    permissions: Array,
});

const selectedPermissions = ref({});

const tables = ref(props.tables);
const permissions = ref(props.permissions);

// console.log(tables.value);

const initializePermissions = () => {
    props.tables.forEach((table) => {
        selectedPermissions.value[table.id] = table.table_to_permissions.map(
            (tp) => tp.permission_id
        );
    });
};

onMounted(() => {
    initializePermissions();
});

/**
 * Handle the change in checkbox state.
 */
// const handleCheckboxChange = (table, permission) => {
//     const tablePermissions = selectedPermissions.value[table.id] || [];
//     if (isPermissionSelected(table, permission)) {

//         selectedPermissions.value[table.id] = tablePermissions.filter(
//             (id) => id !== permission.id
//         );
//     } else {

//         selectedPermissions.value[table.id] = [
//             ...tablePermissions,
//             permission.id,
//         ];
//     }
// };

const handleCheckboxChange = (tableId, permissionId, isChecked) => {
    if (!selectedPermissions[tableId]) {
        // Initialize the array for the table if it doesn't exist
        selectedPermissions[tableId] = [];
    }

    if (isChecked) {
        // Add the permission if it's checked
        if (!selectedPermissions[tableId].includes(permissionId)) {
            selectedPermissions[tableId].push(permissionId);
        }
    } else {
        // Remove the permission if it's unchecked
        selectedPermissions[tableId] = selectedPermissions[tableId].filter(
            (id) => id !== permissionId
        );

        // Remove the table entry if no permissions remain
        if (selectedPermissions[tableId].length === 0) {
            delete selectedPermissions[tableId];
        }
    }
};

const saveTablePermission = async () => {
    submitted.value = true;
    const loader = $loading.show({
        // canCancel: true,
        // onCancel,
    });

    // Validate selectedPermissions
    if (Object.keys(selectedPermissions.value).length > 0) {
        loading.value = true;

        try {
            // Prepare payload for synchronization
            const permissionPayload = new FormData();

            // Loop through the selectedPermissions object
            Object.entries(selectedPermissions.value).forEach(
                ([tableId, permissions]) => {
                    permissions.forEach((permissionId, index) => {
                        permissionPayload.append(
                            `permissions[${tableId}][${index}]`,
                            permissionId
                        );
                    });
                }
            );

            // Send the request to the Laravel backend
            const response = await axios.post(
                "/privileges",
                permissionPayload,
                {
                    headers: { "Content-Type": "multipart/form-data" },
                }
            );
            tables.value = response.data;

            // Handle success
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Permissions synchronized successfully",
                life: 3000,
            });

            // Reset form state
            // selectedPermissions.value = {};
        } catch (err) {
            // Handle validation errors
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
                // Handle authorization errors
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: "You are not allowed to perform this action",
                    life: 5000,
                });
            } else {
                // Handle unexpected errors
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: "An unexpected error occurred.",
                    life: 5000,
                });
            }
        } finally {
            loader.hide();
            loading.value = false;
        }
    } else {
        // Handle missing data
        toast.add({
            severity: "error",
            summary: "Error",
            detail: "Please select at least one permission for a table.",
            life: 3000,
        });
    }
};
</script>

<style scoped>
::v-deep(.p-breadcrumb) {
    background: transparent !important;
    box-shadow: none !important;
}
</style>
