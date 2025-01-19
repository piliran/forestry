<template>
    <AppLayout title="offenses">
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
                                !selectedOffenses || !selectedOffenses.length
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
                    v-if="offenses.length > 0"
                    ref="dt"
                    v-model:selection="selectedOffenses"
                    :value="offenses"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} offenses"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Offenses</h4>
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
                    <Column header="#" headerStyle="width:3rem">
                        <template #body="slotProps">
                            {{ slotProps.index + 1 }}
                        </template>
                    </Column>
                    <Column
                        selectionMode="multiple"
                        style="width: 3rem"
                        :exportable="false"
                    ></Column>
                    <Column
                        field="name"
                        header="offense Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="description"
                        header="Description"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="penalty"
                        header="Penalty"
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
                                @click="editoffense(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteoffense(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Offenses Found</h2>
                </div>
            </div>

            <!-- Add/Edit offense Dialog -->
            <Dialog
                v-model:visible="offenseDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit offense' : 'Add New offense'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            offense Name
                        </label>
                        <InputText
                            id="name"
                            v-model.trim="offense.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !offense.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !offense.name"
                            class="text-red-500"
                        >
                            offense Name is required.
                        </small>
                    </div>

                    <div class="col-12">
                        <label for="description" class="block font-bold mb-3"
                            >Description</label
                        >
                        <Textarea
                            id="description"
                            v-model="offense.description"
                            required="true"
                            rows="3"
                            cols="20"
                            autofocus
                            :invalid="submitted && !offense.description"
                            fluid
                        />
                        <small
                            v-if="submitted && !offense.description"
                            class="text-red-500"
                        >
                            offense Description is required.
                        </small>
                    </div>
                    <div>
                        <label for="penalty" class="block font-bold mb-3">
                            Penalty
                        </label>
                        <InputText
                            id="penalty"
                            v-model.trim="offense.penalty"
                            required="true"
                            autofocus
                            :invalid="submitted && !offense.penalty"
                            fluid
                        />
                        <small
                            v-if="submitted && !offense.penalty"
                            class="text-red-500"
                        >
                            Penalty is required.
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
                            @click="saveoffense"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single offense Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteOffenseDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="offense">
                        Are you sure you want to delete <b>{{ offense.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteOffenseDialog = false"
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
                            @click="deleteoffense"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple offense Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteOffensesDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected offenses?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteOffensesDialog = false"
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
                            @click="deleteSelectedOffenses"
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
import Textarea from "primevue/textarea";
import ProgressSpinner from "primevue/progressspinner";
import Breadcrumb from "primevue/breadcrumb";
import { Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const toast = useToast();

// Reactive State Variables
const dt = ref();
const offenseDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteOffenseDialog = ref(false);
const deleteOffensesDialog = ref(false);
const offense = ref({});
const selectedOffenses = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Offenses" }]);

const props = defineProps({
    offenses: Array,
});

const offenses = ref(props.offenses);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    offense.value = {};
    submitted.value = false;
    offenseDialog.value = true;
};

const hideDialog = () => {
    offenseDialog.value = false;
    submitted.value = false;
};

const saveoffense = async () => {
    submitted.value = true;
    if (offense?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (offense.value.id) {
                const response = await axios.put(
                    `/offenses/${offense.value.id}`,
                    offense.value
                );
                updateoffense(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "offense Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post("/offenses", offense.value);
                offenses.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "offense Created",
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
            offenseDialog.value = false;
        }
    }
};

const editoffense = (offenseData) => {
    editDialog.value = true;
    offense.value = { ...offenseData };
    offenseDialog.value = true;
};

const deleteoffense = async () => {
    loading.value = true;
    try {
        await axios.delete(`/offenses/${offense.value.id}`);
        offenses.value = offenses.value.filter(
            (r) => r.id !== offense.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "offense Deleted",
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
        deleteOffenseDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteoffense = (offenseData) => {
    offense.value = offenseData;
    deleteOffenseDialog.value = true;
};

const deleteSelectedOffenses = async () => {
    const ids = selectedOffenses.value.map((offense) => offense.id);
    loading.value = true;
    try {
        await axios.post("/offenses/bulk-delete", { ids });
        offenses.value = offenses.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected offenses Deleted",
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
        deleteOffensesDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteOffensesDialog.value = true;
};

const updateoffense = (updatedoffense) => {
    const index = offenses.value.findIndex((r) => r.id === updatedoffense.id);
    if (index !== -1) {
        offenses.value[index] = updatedoffense;
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
