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
                            <span class="text-primary font-semibold">{{ item.label }}</span>
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

            <!-- Accordion -->
            <div class="card flex flex-col">
                <!-- Set the width to one third of the page -->
                <div class="w-1/3">
                    <Accordion value="0">
                        <AccordionPanel v-for="tab in tabs" :key="tab.title" :value="tab.value">
                            <AccordionHeader>{{ tab.title }}</AccordionHeader>
                            <AccordionContent>
                                <div class="card flex justify-center">
                                    <PickList
                                        v-model="selectedPermissions[tab.value]"
                                        dataKey="key"
                                        breakpoint="1000px"
                                    >
                                        <template #option="{ option }">
                                            {{ option.name }}
                                        </template>
                                    </PickList>
                                </div>
                            </AccordionContent>
                        </AccordionPanel>
                    </Accordion>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Toast from "primevue/toast";
import Button from "primevue/button";
import Toolbar from "primevue/toolbar";
import Accordion from 'primevue/accordion';
import AccordionPanel from 'primevue/accordionpanel';
import AccordionHeader from 'primevue/accordionheader';
import AccordionContent from 'primevue/accordioncontent';
import PickList from 'primevue/picklist';
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

// Define tabs
const tabs = ref([
    { title: 'Department 1', value: '0' },
    { title: 'Department 2', value: '1' },
    { title: 'Department 3', value: '2' }
]);

// Permissions for each department
const allPermissions = ref([
    { name: 'Add', key: 'add' },
    { name: 'Edit', key: 'edit' },
    { name: 'Delete', key: 'delete' },
    { name: 'Archive', key: 'archive' }
]);

// Selected permissions for each tab
const selectedPermissions = ref({
    '0': [[], []], // Source and target lists for Department 1
    '1': [[], []], // Source and target lists for Department 2
    '2': [[], []]  // Source and target lists for Department 3
});

// Initialize source lists for each department
onMounted(() => {
    tabs.value.forEach((tab) => {
        selectedPermissions.value[tab.value][0] = [...allPermissions.value]; // Source list
    });
});
</script>

<style scoped>
::v-deep(.p-breadcrumb) {
    background: transparent !important;
    box-shadow: none !important;
}
</style>
