<template>
    <AppLayout title="Arrests">
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
                                !selectedArrests || !selectedArrests.length
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
                    v-if="arrests.length > 0"
                    ref="dt"
                    v-model:selection="selectedArrests"
                    :value="arrests"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} arrests"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Arrests</h4>
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
                        field="suspect.name"
                        header="Suspect Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="crime.name"
                        header="Crime"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="description"
                        header="Description"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column header="Date" sortable style="min-width: 10rem">
                        <template #body="slotProps">
                            {{ formatDate(slotProps.data.date) }}
                        </template>
                    </Column>
                    <!-- <Column
                        field="location"
                        header="Location"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="proof"
                        header="Proof"
                        sortable
                        style="min-width: 10rem"
                    ></Column> -->

                    <!-- <Column
                        field="crime.name"
                        header="Crime"
                        sortable
                        style="min-width: 10rem"
                    ></Column> -->
                    <!-- <Column
                        field="confiscate.item"
                        header="Confiscates"
                        sortable
                        style="min-width: 10rem"
                    ></Column> -->

                    <Column
                        header="Action"
                        :exportable="false"
                        style="min-width: 12rem"
                    >
                        <template #body="slotProps">
                            <Button
                                icon="pi pi-eye"
                                outlined
                                rounded
                                class="mr-2"
                                severity="info"
                                @click="viewArrest(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-pencil"
                                outlined
                                rounded
                                class="mr-2"
                                @click="editArrest(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteArrest(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Arrests Found</h2>
                </div>
            </div>

            <!-- Add/Edit Arrests Dialog -->
            <Dialog
                v-model:visible="arrestDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Arrest' : 'Add New Arrest'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div class="col-span-1 flex justify-center items-center">
                        <div
                            v-if="showSelectedSuspect"
                            class="h-48 w-48 overflow-hidden rounded-full border shadow-md"
                        >
                            <img
                                v-if="selectedSuspect.suspect_photo_path"
                                :src="selectedSuspect.suspect_photo_path"
                                alt="Suspect Photo"
                                class="object-contain w-full h-full"
                            />
                            <div v-else class="text-gray-500">
                                No Photo Available
                            </div>
                        </div>
                    </div>

                    <div class="col-12 md:col-6">
                        <label for="suspects" class="block font-bold mb-2"
                            >Suspect</label
                        >
                        <Select
                            id="id"
                            v-model="arrest.suspect_id"
                            :options="suspects"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Suspect"
                            fluid
                            filter
                        />
                        <small
                            v-if="submitted && !arrest.suspect_id"
                            class="text-red-500"
                        >
                            Suspect is required.
                        </small>
                    </div>

                    <div class="col-12 md:col-6">
                        <label for="crimes" class="block font-bold mb-2"
                            >Crime</label
                        >
                        <Select
                            id="id"
                            v-model="arrest.crime_id"
                            :options="crimes"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Crime"
                            fluid
                            filter
                        />
                        <small
                            v-if="submitted && !arrest.crime_id"
                            class="text-red-500"
                        >
                            Crime is required.
                        </small>
                    </div>
                    <div>
                        <label for="date" class="block font-bold mb-3">
                            Date Arrested
                        </label>

                        <DatePicker
                            fluid
                            v-model="arrest.date"
                            dateFormat="dd/mm/yy"
                            placeholder="YYYY-MM-DD"
                        />
                        <small
                            v-if="submitted && !arrest.date"
                            class="text-red-500"
                        >
                            Arrest Date is required.
                        </small>
                    </div>
                    <div class="col-12">
                        <label for="description" class="block font-bold mb-3"
                            >Description</label
                        >
                        <Textarea
                            id="description"
                            v-model="arrest.description"
                            required="true"
                            rows="3"
                            cols="20"
                            autofocus
                            :invalid="submitted && !arrest.description"
                            fluid
                        />
                        <small
                            v-if="submitted && !arrest.description"
                            class="text-red-500"
                        >
                            Arrest Description is required.
                        </small>
                    </div>

                    <!-- <div>
                        <label for="location" class="block font-bold mb-3">
                            Location
                        </label>
                        <InputText
                            id="location"
                            v-model.trim="arrest.location"
                            required="true"
                            autofocus
                            :invalid="submitted && !arrest.location"
                            fluid
                        />
                        <small
                            v-if="submitted && !arrest.location"
                            class="text-red-500"
                        >
                            Location is required.
                        </small>
                    </div>
                    <div>
                        <label for="proof" class="block font-bold mb-3">
                            Proof
                        </label>
                        <InputText
                            id="proof"
                            v-model.trim="arrest.proof"
                            required="true"
                            autofocus
                            :invalid="submitted && !arrest.proof"
                            fluid
                        />
                        <small
                            v-if="submitted && !arrest.proof"
                            class="text-red-500"
                        >
                            Proof is required.
                        </small>
                    </div> -->

                    <!-- <div class="col-12 md:col-6">
                        <label for="confiscate" class="block font-bold mb-2"
                            >Confiscate</label
                        >
                        <Select
                            id="id"
                            v-model="arrest.confiscate_id"
                            :options="confiscates"
                            optionLabel="item"
                            optionValue="id"
                            placeholder="Select Confiscates"
                            fluid
                        />
                        <small
                            v-if="submitted && !arrest.confiscate_id"
                            class="text-red-500"
                        >
                            Confiscate is required.
                        </small>
                    </div> -->
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
                            @click="saveArrest"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Arrest Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteArrestDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="arrest">
                        Are you sure you want to delete
                        <b>{{ arrest.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteArrestDialog = false"
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
                            @click="deleteArrest"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple DistrAArrestict Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteArrestsDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected Arrests?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteArrestDialog = false"
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
                            @click="deleteSelectedArrests"
                        />
                    </div>
                </template>
            </Dialog>

            <Dialog
                v-model:visible="viewDialog"
                modal
                header="Confiscate Details"
                :style="{ width: '450px' }"
            >
                <div class="p-6 space-y-6">
                    <!-- Grid Container for Image and Details -->
                    <div class="grid grid-cols-1 gap-6">
                        <div
                            class="col-span-1 flex justify-center items-center"
                        >
                            <div
                                class="w-full h-48 overflow-hidden rounded-lg border shadow-md"
                            >
                                <img
                                    v-if="arrest.suspect.suspect_photo_path"
                                    :src="arrest.suspect.suspect_photo_path"
                                    alt="Suspect Photo"
                                    class="object-contain w-full h-full"
                                />
                                <div v-else class="text-gray-500">
                                    No Photo Available
                                </div>
                            </div>
                        </div>

                        <div class="col-span-1 flex flex-col space-y-3">
                            <div class="space-y-1">
                                <span
                                    class="font-semibold text-lg text-gray-800"
                                    >Suspect</span
                                >
                                <div class="text-base text-gray-600">
                                    {{ arrest.suspect.name || "N/A" }}
                                </div>
                            </div>
                            <Divider />
                            <div class="space-y-1">
                                <span
                                    class="font-semibold text-lg text-gray-800"
                                    >Crime</span
                                >
                                <div class="text-base text-gray-600">
                                    {{ arrest.crime.name || "N/A" }}
                                </div>
                            </div>
                            <Divider />
                            <div class="space-y-1">
                                <span
                                    class="font-semibold text-lg text-gray-800"
                                    >Penalty</span
                                >
                                <div class="text-base text-gray-600">
                                    {{ arrest.crime.penalty || "N/A" }}
                                </div>
                            </div>
                            <Divider />
                            <div class="space-y-1">
                                <span
                                    class="font-semibold text-lg text-gray-800"
                                    >Date Arrested</span
                                >
                                <div class="text-base text-gray-600">
                                    {{ arrest.date || "N/A" }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <template #footer>
                    <Button
                        label="Close"
                        icon="pi pi-times"
                        text
                        @click="viewDialog = false"
                    />
                </template>
            </Dialog>
        </div>
    </AppLayout>
</template>
<script setup>
import { ref, onMounted, watch } from "vue";
import { FilterMatchMode } from "@primevue/core/api";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import Divider from "primevue/divider";

import Textarea from "primevue/textarea";
import { format } from "date-fns";
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
import DatePicker from "primevue/datepicker";

const toast = useToast();

// Reactive State Variables
const dt = ref();
const arrestDialog = ref(false);
const editDialog = ref(false);
const viewDialog = ref(false);
const showSelectedSuspect = ref(false);

const loading = ref(false);
const deleteArrestDialog = ref(false);
const deleteArrestsDialog = ref(false);
const arrest = ref({});
const selectedSuspect = ref({});
const selectedArrests = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    arrests: Array,
    suspects: Array,
    confiscates: Array,
    crimes: Array,
});

const arrests = ref(props.arrests);
const suspects = ref(props.suspects);
const confiscates = ref(props.confiscates);
const crimes = ref(props.crimes);

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Arrests" }]);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    arrest.value = {};
    submitted.value = false;
    arrestDialog.value = true;
};

const hideDialog = () => {
    arrestDialog.value = false;
    submitted.value = false;
};

watch(
    () => arrest.value.date,
    (newVal) => {
        if (newVal && typeof newVal === "string" && newVal.includes("T")) {
            arrest.value.date = format(new Date(newVal), "dd/MM/yyyy");
        }
    },
    { immediate: true }
);

watch(
    () => arrest.value.suspect_id,
    (newVal) => {
        if (newVal) {
            showSelectedSuspect.value = true;

            // const suspect = arrests.value.find(
            //     (arrest) => arrest.suspect.id === newVal
            // );
            const suspect = suspects.value.find((r) => r.id === newVal);

            selectedSuspect.value = suspect;
        }
    },
    { immediate: true }
);

const formatDate = (dateString) => {
    if (!dateString) return "N/A";
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
    }); // Example: "Nov 24, 2024"
};

const saveArrest = async () => {
    submitted.value = true;
    if (arrest?.value?.description?.trim()) {
        loading.value = true;
        try {
            if (arrest.value.id) {
                const response = await axios.put(
                    `/arrests/${arrest.value.id}`,
                    arrest.value
                );
                updateArrest(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Arrest Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post("/arrests", arrest.value);
                arrests.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Arrest Created",
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
            arrestDialog.value = false;
        }
    }
};

const editArrest = (arrestData) => {
    editDialog.value = true;
    arrest.value = { ...arrestData };
    arrestDialog.value = true;
};

const viewArrest = (arrestData) => {
    arrest.value = { ...arrestData };
    viewDialog.value = true;
};

const deleteArrest = async () => {
    loading.value = true;
    try {
        await axios.delete(`/arrests/${arrest.value.id}`);
        arrests.value = arrests.value.filter((r) => r.id !== arrest.value.id);
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Arrest Deleted",
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
        deleteArrestDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteArrest = (arrestData) => {
    arrest.value = arrestData;
    deleteArrestDialog.value = true;
};

const deleteSelectedArrests = async () => {
    const ids = selectedArrests.value.map((arrest) => arrest.id);
    loading.value = true;
    try {
        await axios.post("/arrests/bulk-delete", { ids });
        arrests.value = arrests.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Arrests Deleted",
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
        deleteArrestsDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteArrestsDialog.value = true;
};

const updateArrest = (updatedArrest) => {
    const index = arrests.value.findIndex((r) => r.id === updatedArrest.id);
    if (index !== -1) {
        arrests.value[index] = updatedArrest;
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
</style>
