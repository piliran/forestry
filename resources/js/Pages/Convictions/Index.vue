<template>
    <AppLayout title="Convictions">
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
                                !selectedConvictions ||
                                !selectedConvictions.length
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
                    v-if="convictions.length > 0"
                    ref="dt"
                    v-model:selection="selectedConvictions"
                    :value="convictions"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} convictions"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Convictions</h4>
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
                        field="offender_name"
                        header="Offender Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="conviction_date"
                        header="Conviction Date"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="offense"
                        header="Offense"
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
                                @click="editConviction(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteConviction(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Convictions Found</h2>
                </div>
            </div>

            <!-- Add/Edit Conviction Dialog -->
            <Dialog
                v-model:visible="convictionDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Conviction' : 'Add New Conviction'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="offender_name" class="block font-bold mb-3">
                            Offender Name
                        </label>
                        <InputText
                            id="offender_name"
                            v-model.trim="conviction.offender_name"
                            required="true"
                            autofocus
                            :invalid="submitted && !conviction.offender_name"
                            fluid
                            placeholder="Enter Offender Name"
                        />
                        <small
                            v-if="submitted && !conviction.offender_name"
                            class="text-red-500"
                        >
                            Offender Name is required.
                        </small>
                    </div>
                    <div>
                        <label
                            for="conviction_date"
                            class="block font-bold mb-3"
                        >
                            Conviction Date
                        </label>
                        <DatePicker
                            id="conviction_date"
                            v-model.trim="conviction.conviction_date"
                            dateFormat="dd/mm/yy"
                            required="true"
                            :invalid="submitted && !conviction.conviction_date"
                            fluid
                            placeholder="Select Conviction Date"
                        />
                        <small
                            v-if="submitted && !conviction.conviction_date"
                            class="text-red-500"
                        >
                            Conviction Date is required.
                        </small>
                    </div>
                    <div>
                        <label for="offense" class="block font-bold mb-3">
                            Offense
                        </label>
                        <InputText
                            id="offense"
                            v-model.trim="conviction.offense"
                            required="true"
                            :invalid="submitted && !conviction.offense"
                            fluid
                            placeholder="Enter Offense"
                        />
                        <small
                            v-if="submitted && !conviction.offense"
                            class="text-red-500"
                        >
                            Offense is required.
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
                            @click="saveConviction"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Conviction Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteConvictionDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="conviction">
                        Are you sure you want to delete
                        <b>{{ conviction.offender_name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteConvictionDialog = false"
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
                            @click="deleteConviction"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Conviction Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteConvictionsDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected
                        Convictions?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteConvictionsDialog = false"
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
                            @click="deleteSelectedConviction"
                        />
                    </div>
                </template>
            </Dialog>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, defineProps } from "vue";
import { FilterMatchMode } from "@primevue/core/api";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";

import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Toolbar from "primevue/toolbar";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import DatePicker from "primevue/datepicker";
import IconField from "primevue/iconfield";
import { Link } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const toast = useToast();

// Reactive State Variables
const convictionDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteConvictionDialog = ref(false);
const deleteConvictionsDialog = ref(false);
const conviction = ref({});
const selectedConvictions = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    convictions: Array,
});

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Convictions" }]);

const convictions = ref(props.convictions);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    conviction.value = {};
    submitted.value = false;
    convictionDialog.value = true;
};

const hideDialog = () => {
    convictionDialog.value = false;
    submitted.value = false;
};

const saveConviction = async () => {
    submitted.value = true;
    if (conviction?.value?.offender_name?.trim()) {
        loading.value = true;
        try {
            if (conviction.value.id) {
                const response = await axios.put(
                    `/convictions/${conviction.value.id}`,
                    conviction.value
                );
                updateConviction(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Conviction Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post(
                    "/convictions",
                    conviction.value
                );
                convictions.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Conviction Created",
                    life: 3000,
                });
            }
        } catch (err) {
            handleError(err);
        } finally {
            loading.value = false;
            convictionDialog.value = false;
        }
    }
};

const editConviction = (convictionData) => {
    editDialog.value = true;
    conviction.value = { ...convictionData };
    convictionDialog.value = true;
};

const deleteConviction = async () => {
    loading.value = true;
    try {
        await axios.delete(`/convictions/${conviction.value.id}`);
        convictions.value = convictions.value.filter(
            (r) => r.id !== conviction.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Conviction Deleted",
            life: 3000,
        });
    } catch (err) {
        handleError(err);
    } finally {
        deleteConvictionDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteConviction = (convictionData) => {
    conviction.value = convictionData;
    deleteConvictionDialog.value = true;
};

const deleteSelectedConviction = async () => {
    const ids = selectedConvictions.value.map((conviction) => conviction.id);
    loading.value = true;
    try {
        await axios.post("/convictions/bulk-delete", { ids });
        convictions.value = convictions.value.filter(
            (r) => !ids.includes(r.id)
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Convictions Deleted",
            life: 3000,
        });
    } catch (err) {
        handleError(err);
    } finally {
        deleteConvictionsDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteConvictionsDialog.value = true;
};

const updateConviction = (updatedConviction) => {
    const index = convictions.value.findIndex(
        (r) => r.id === updatedConviction.id
    );
    if (index !== -1) {
        convictions.value[index] = updatedConviction;
    }
};

const handleError = (err) => {
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
