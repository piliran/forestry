<template>
    <AppLayout title="Permissions">
        <div>
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
                            <span
                                class="text-surface-700 dark:text-surface-0"
                            >
                                {{ item.label }}
                            </span>
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
                            :disabled="!selectedPermissions || !selectedPermissions.length"
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
            </div>

            <!-- Responsive Panels -->
            <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="tab in tabs"
                    :key="tab.title"
                    class="card"
                >
                    <Panel :header="tab.title" toggleable>
                        <div class="card flex flex-wrap justify-center gap-4">
                            <div
                                v-for="permission in tab.permissions"
                                :key="permission.key"
                                class="flex items-center gap-2"
                            >
                                <Checkbox
                                    v-model="selectedPermissions[tab.value]"
                                    :inputId="permission.key"
                                    name="permission"
                                    :value="permission.name"
                                />
                                <label :for="permission.key">
                                    {{ permission.name }}
                                </label>
                            </div>
                        </div>
                    </Panel>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import Toast from "primevue/toast";
import Button from "primevue/button";
import Toolbar from "primevue/toolbar";
import Checkbox from "primevue/checkbox";

import Panel from "primevue/panel";
import Breadcrumb from "primevue/breadcrumb";
import { Link } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";

// Data references
const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Permissions" }]);

// Define tabs and their respective permissions dynamically
const tabs = ref([
    {
        title: "Department 1",
        value: "0",
        permissions: [
            { name: "Add", key: "add1" },
            { name: "Edit", key: "edit1" },
            { name: "Delete", key: "delete1" },
            { name: "Archive", key: "archive1" },
        ],
    },
    {
        title: "Department 2",
        value: "1",
        permissions: [
            { name: "Add", key: "add2" },
            { name: "Edit", key: "edit2" },
            { name: "Delete", key: "delete2" },
            { name: "Archive", key: "archive2" },
        ],
    },
    {
        title: "Department 3",
        value: "2",
        permissions: [
            { name: "Add", key: "add3" },
            { name: "Edit", key: "edit3" },
            { name: "Delete", key: "delete3" },
            { name: "Archive", key: "archive3" },
        ],
    },
    {
        title: "Department 4",
        value: "3",
        permissions: [
            { name: "Add", key: "add3" },
            { name: "Edit", key: "edit3" },
            { name: "Delete", key: "delete3" },
            { name: "Archive", key: "archive3" },
        ],
    },
]);

// Track selected permissions for each tab
const selectedPermissions = ref({
    "0": [], // Selected permissions for Department 1
    "1": [], // Selected permissions for Department 2
    "2": [], // Selected permissions for Department 3
    "3": [], // Selected permissions for Department 3
});
</script>

<style scoped>
::v-deep(.p-breadcrumb) {
    background: transparent !important;
    box-shadow: none !important;
}
</style>
