<template>
    <AppLayout title="Schedules">
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
                                !selectedSchedules || !selectedSchedules.length
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
                    v-if="schedules.length > 0"
                    ref="dt"
                    v-model:selection="selectedSchedules"
                    :value="schedules"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Schedules"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Schedules</h4>
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
                        header="Operation"
                        field="operation.name"
                        sortable
                        style="min-width: 12rem"
                    ></Column>
                    <Column
                        field="date_of_operation"
                        header="Operation Date"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="deployment_time"
                        header="Deployment Time"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="withdrawal_time"
                        header="Withdraw Time"
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
                                @click="editSchedule(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteSchedule(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Schedules Found</h2>
                </div>
            </div>

            <!-- Add/Edit Schedule Dialog -->
            <Dialog
                v-model:visible="scheduleDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Schedule' : 'Add New Schedule'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="operation" class="block font-bold mb-3">
                            Operation
                        </label>
                        <Select
                            id="operation"
                            v-model="schedule.operation_id"
                            :options="operations"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Operation"
                            fluid
                            filter
                        />
                    </div>
                    <div>
                        <label for="code" class="block font-bold mb-3">
                            Operation Date
                        </label>
                        <DatePicker
                            id="date_of_operation"
                            v-model="schedule.date_of_operation"
                            dateFormat="dd/mm/yy"
                            required="true"
                            autofocus
                            :invalid="submitted && !schedule.date_of_operation"
                            fluid
                            placeholder="Enter Time of Deployment"
                        />
                        <small
                            v-if="submitted && !schedule.date_of_operation"
                            class="text-red-500"
                        >
                            Operation Date is required.
                        </small>
                    </div>
                    <div>
                        <label
                            for="deployment_time"
                            class="block font-bold mb-3"
                        >
                            Deployment Time
                        </label>
                        <DatePicker
                            id="deployment_time"
                            v-model="schedule.deployment_time"
                            dateFormat="dd/mm/yy"
                            required="true"
                            autofocus
                            :invalid="submitted && !schedule.deployment_time"
                            fluid
                            placeholder="Enter Time of Deployment"
                            showTime
                            hourFormat="24"
                        />
                        <small
                            v-if="submitted && !schedule.deployment_time"
                            class="text-red-500"
                        >
                            Deployment Time is required.
                        </small>
                    </div>
                    <div>
                        <label
                            for="withdrawal_time"
                            class="block font-bold mb-3"
                        >
                            Withdrawal Time
                        </label>
                        <DatePicker
                            id="withdrawal_time"
                            v-model="schedule.withdrawal_time"
                            dateFormat="dd/mm/yy"
                            required="true"
                            autofocus
                            :invalid="submitted && !schedule.withdrawal_time"
                            fluid
                            placeholder="Enter Time of Withdraw"
                            showTime
                            hourFormat="24"
                        />
                        <small
                            v-if="submitted && !schedule.withdrawal_time"
                            class="text-red-500"
                        >
                            Withdrawal Time is required.
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
                            @click="saveSchedule"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Schedule Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteScheduleDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="schedule">
                        Are you sure you want to delete
                        <b>{{ schedule.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteScheduleDialog = false"
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
                            @click="deleteSchedule"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Schedule Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteSchedulesDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected Schedules?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteSchedulesDialog = false"
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
                            @click="deleteSelectedSchedules"
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
import DatePicker from "primevue/datepicker";
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

// Reactive State Variables
const dt = ref();
const scheduleDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteScheduleDialog = ref(false);
const deleteSchedulesDialog = ref(false);
const schedule = ref({});
const selectedSchedules = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    schedules: Array,
    operations: Array,
});

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Schedules" }]);

const schedules = ref(props.schedules);
const operations = ref(props.operations);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    schedule.value = {};
    submitted.value = false;
    scheduleDialog.value = true;
};

const hideDialog = () => {
    scheduleDialog.value = false;
    submitted.value = false;
};

const saveSchedule = async () => {
    submitted.value = true;
    if (schedule?.value?.date_of_operation) {
        loading.value = true;
        try {
            if (schedule.value.id) {
                const response = await axios.put(
                    `/schedules/${schedule.value.id}`,
                    schedule.value
                );

                updateSchedule(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Schedule Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post("/schedules", schedule.value);

                schedules.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Schedule Created",
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
            scheduleDialog.value = false;
        }
    }
};

const editSchedule = (scheduleData) => {
    editDialog.value = true;
    schedule.value = { ...scheduleData };
    scheduleDialog.value = true;
};

const deleteSchedule = async () => {
    loading.value = true;
    try {
        await axios.delete(`/schedules/${schedule.value.id}`);
        schedules.value = schedules.value.filter(
            (r) => r.id !== schedule.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Schedule Deleted",
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
        deleteScheduleDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSchedule = (scheduleData) => {
    schedule.value = scheduleData;
    deleteScheduleDialog.value = true;
};

const deleteSelectedSchedules = async () => {
    const ids = selectedSchedules.value.map((schedule) => schedule.id);
    loading.value = true;
    try {
        await axios.post("/schedules/bulk-delete", { ids });
        schedules.value = schedules.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Schedule Deleted",
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
        deleteSchedulesDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteSchedulesDialog.value = true;
};

const updateSchedule = (updatedSchedule) => {
    const index = schedules.value.findIndex((r) => r.id === updatedSchedule.id);
    if (index !== -1) {
        schedules.value[index] = updatedSchedule;
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
