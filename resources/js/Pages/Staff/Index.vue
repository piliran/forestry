<template>
    <AppLayout title="Staff">
        <div>
            <div class="-mt-6 inline-block bg-transparent">
                <Breadcrumb :home="home" :model="breadcrumbItems">
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

            <div class="card">
                <Toolbar class="mb-6">
                    <template #start>
                        <Button
                            label="New Staff"
                            icon="pi pi-plus"
                            class="mr-2"
                            @click="openNewStaff"
                        />
                        <Button
                            label="Delete"
                            icon="pi pi-trash"
                            severity="danger"
                            outlined
                            @click="confirmDeleteSelected"
                            :disabled="!selectedStaff || !selectedStaff.length"
                        />
                    </template>
                </Toolbar>

                <Toast />

                <DataTable
                    v-if="staffList.length > 0"
                    ref="dt"
                    v-model:selection="selectedStaff"
                    :value="staffList"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} staff"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Staff</h4>
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
                        header="Staff Name"
                        sortable
                        style="min-width: 12rem"
                    ></Column>
                    <Column
                        field="role.name"
                        header="Role"
                        sortable
                        style="min-width: 12rem"
                    ></Column>
                    <Column
                        field="email"
                        header="Email"
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
                                @click="editStaff(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteStaff(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No staff members found</h2>
                </div>
            </div>

            <Dialog
                v-model:visible="staffDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Staff' : 'Add New Staff'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3"
                            >Name</label
                        >
                        <InputText
                            id="name"
                            v-model.trim="staff.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !staff.name"
                        />
                        <small
                            v-if="submitted && !staff.name"
                            class="text-red-500"
                        >
                            Name is required.
                        </small>
                    </div>
                    <div>
                        <label for="email" class="block font-bold mb-3"
                            >Email</label
                        >
                        <InputText
                            id="email"
                            v-model.trim="staff.email"
                            required="true"
                            :invalid="submitted && !staff.email"
                        />
                        <small
                            v-if="submitted && !staff.email"
                            class="text-red-500"
                        >
                            Email is required.
                        </small>
                    </div>
                    <div>
                        <label for="role" class="block font-bold mb-3"
                            >Role</label
                        >
                        <Select
                            id="role"
                            v-model="staff.role_id"
                            :options="roles"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select a Role"
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
                        />
                        <Button
                            v-else
                            label="Save"
                            icon="pi pi-check"
                            @click="saveStaff"
                        />
                    </div>
                </template>
            </Dialog>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import Checkbox from "primevue/checkbox";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Toolbar from "primevue/toolbar";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import Select from "primevue/select";
import ProgressSpinner from "primevue/progressspinner";
import Breadcrumb from "primevue/breadcrumb";
import axios from "axios";

const toast = useToast();
const staffDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const submitted = ref(false);
const staff = ref({});
const selectedStaff = ref([]);
const filters = ref({
    global: { value: null, matchMode: "contains" },
});
const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});
const breadcrumbItems = ref([{ label: "Staff List" }]);

const props = defineProps({
    staffList: Array,
    roles: Array,
});

const openNewStaff = () => {
    editDialog.value = false;
    staff.value = {};
    submitted.value = false;
    staffDialog.value = true;
};

const hideDialog = () => {
    staffDialog.value = false;
    submitted.value = false;
};

const saveStaff = async () => {
    // Save staff logic
};
</script>
