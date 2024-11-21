<template>
    <AppLayout title="suspects">
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
                    v-if="suspects.length > 0"
                    ref="dt"
                    v-model:selection="selectedRoles"
                    :value="suspects"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} suspects"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage suspects</h4>
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
                    <Column header="Image">
                        <template #body="slotProps">
                            <!-- <img
                                :src="`${slotProps.data.suspect_photo_path}`"
                                :alt="slotProps.data.suspect_photo_path"
                                class="w-10 h-10 rounded-full me-3 object-cover"
                            /> -->

                            <Image
                                :src="`${slotProps.data.suspect_photo_path}`"
                                :alt="slotProps.data.suspect_photo_path"
                                preview
                            >
                                <template #image>
                                    <img
                                        :src="slotProps.data.suspect_photo_path"
                                        alt="Profile"
                                        style="
                                            border-radius: 50%;
                                            width: 50px;
                                            height: 50px;
                                            object-fit: cover;
                                        "
                                    />
                                </template>
                            </Image>
                        </template>
                    </Column>
                    <Column
                        field="name"
                        header="suspect Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="national_id"
                        header="National ID"
                        sortable
                        style="min-width: 10rem"
                    ></Column>

                    <Column
                        header="District"
                        field="district.name"
                        sortable
                        style="min-width: 12rem"
                    ></Column>
                    <Column
                        header="Village"
                        field="village"
                        sortable
                        style="min-width: 12rem"
                    ></Column>
                    <Column
                        header="T/A"
                        field="TA"
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
                                @click="editSuspect(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteSuspect(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No suspects found</h2>
                </div>
            </div>

            <!-- Add/Edit suspect Dialog -->
            <Dialog
                v-model:visible="suspectDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit suspect' : 'Add New suspect'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <!-- suspect Name -->
                    <div>
                        <label for="name" class="block font-bold mb-3"
                            >Suspect Name</label
                        >
                        <InputText
                            id="name"
                            v-model.trim="suspect.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !suspect.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !suspect.name"
                            class="text-red-500"
                        >
                            suspect Name is required.
                        </small>
                    </div>

                    <div>
                        <label for="name" class="block font-bold mb-3"
                            >Suspect Photo</label
                        >

                        <FileUpload
                            ref="fileupload"
                            mode="basic"
                            name="suspect[]"
                            accept="image/*"
                            :maxFileSize="1000000"
                            @select="onFileSelect"
                            @upload="onUpload"
                        />

                        <!-- Image Preview -->
                        <div v-if="previewUrl" class="mt-3 flex justify-center">
                            <img
                                :src="previewUrl"
                                alt="Selected Suspect Photo"
                                width="250"
                                class="border rounded-md"
                            />
                        </div>
                        <div
                            v-if="suspect.suspect_photo_path && !previewUrl"
                            class="mt-3 flex justify-center"
                        >
                            <img
                                :src="suspect.suspect_photo_path"
                                alt="Suspect Photo"
                                class="border rounded-md"
                                width="250"
                            />
                        </div>

                        <!-- Error Message -->
                        <small
                            v-if="submitted && !fileupload"
                            class="text-red-500"
                        >
                            Suspect image is required.
                        </small>
                    </div>

                    <div>
                        <label for="national_id" class="block font-bold mb-3"
                            >Suspect National Id</label
                        >
                        <InputText
                            id="national_id"
                            v-model.trim="suspect.national_id"
                            required="true"
                            autofocus
                            :invalid="submitted && !suspect.national_id"
                            fluid
                        />
                        <small
                            v-if="submitted && !suspect.national_id"
                            class="text-red-500"
                        >
                            Suspect National Id is required.
                        </small>
                    </div>

                    <!-- districts -->
                    <div>
                        <label for="districts" class="block font-bold mb-3">
                            District
                        </label>
                        <Select
                            id="districts"
                            v-model="suspect.district_id"
                            :options="props.districts"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select district"
                            fluid
                        />
                    </div>

                    <div>
                        <label for="village" class="block font-bold mb-3"
                            >Suspect Village</label
                        >
                        <InputText
                            id="village"
                            v-model.trim="suspect.village"
                            required="true"
                            autofocus
                            :invalid="submitted && !suspect.village"
                            fluid
                        />
                        <small
                            v-if="submitted && !suspect.village"
                            class="text-red-500"
                        >
                            Suspect Village is required.
                        </small>
                    </div>
                    <div>
                        <label for="TA" class="block font-bold mb-3"
                            >Suspect T/A</label
                        >
                        <InputText
                            id="TA"
                            v-model.trim="suspect.TA"
                            required="true"
                            autofocus
                            :invalid="submitted && !suspect.TA"
                            fluid
                        />
                        <small
                            v-if="submitted && !suspect.TA"
                            class="text-red-500"
                        >
                            Suspect T/A is required.
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
                            @click="saveSuspect"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single suspect Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteSuspectDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="suspect">
                        Are you sure you want to delete <b>{{ suspect.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteSuspectDialog = false"
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
                            @click="deleteSuspect"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple suspects Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteRolesDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected suspects?
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
                            @click="deleteSelectedSuspects"
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

import FileUpload from "primevue/fileupload";

import Image from "primevue/image";

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
const suspectDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteSuspectDialog = ref(false);
const deleteRolesDialog = ref(false);
const suspect = ref({});
const selectedRoles = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const fileupload = ref();

const upload = () => {
    fileupload.value.upload();
};

const onUpload = () => {
    toast.add({
        severity: "info",
        summary: "Success",
        detail: "File Uploaded",
        life: 3000,
    });
};

suspect.value = {
    name: "",
    role_category_id: null,
    districts: [],
};

const props = defineProps({
    suspects: Array,

    districts: Array,
});

const suspects = ref(props.suspects);
const countries = ref(props.countries);

const previewUrl = ref(null);
const onFileSelect = (event) => {
    const file = event.files[0]; // Get the first selected file
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrl.value = e.target.result; // Set the preview URL
        };
        reader.readAsDataURL(file); // Read the file as a data URL
    }
};

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    suspect.value = {};
    submitted.value = false;
    suspectDialog.value = true;
};

const hideDialog = () => {
    suspectDialog.value = false;
    submitted.value = false;
};

const saveSuspect = async () => {
    submitted.value = true;

    // Validate required fields
    if (
        suspect?.value?.name?.trim() &&
        suspect?.value?.national_id?.trim() &&
        suspect?.value?.district_id &&
        suspect?.value?.village?.trim() &&
        suspect?.value?.TA?.trim()
    ) {
        loading.value = true;

        try {
            // Prepare suspect payload
            const suspectPayload = new FormData();
            suspectPayload.append("name", suspect.value.name);
            suspectPayload.append("national_id", suspect.value.national_id);
            suspectPayload.append("district_id", suspect.value.district_id);
            suspectPayload.append("village", suspect.value.village);
            suspectPayload.append("TA", suspect.value.TA);

            // Append the image file if selected
            const file = fileupload.value.files[0]; // Get the first selected file
            if (file) {
                suspectPayload.append("suspect_photo_path", file);
            }

            // If suspect already exists, we don't need to re-append suspect_photo_path unless it's new
            if (suspect.value.id) {
                suspectPayload.append("id", suspect.value.id);

                const response = await axios.post(
                    `/update-suspect`,
                    suspectPayload,
                    { headers: { "Content-Type": "multipart/form-data" } }
                );

                updateSuspect(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Suspect Updated",
                    life: 3000,
                });
            } else {
                // Create a new suspect
                const response = await axios.post("/suspects", suspectPayload, {
                    headers: { "Content-Type": "multipart/form-data" },
                });
                suspects.value.push(response.data); // Add the new suspect to the list
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Suspect Created",
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
            suspectDialog.value = false;
        }
    } else {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: "Please fill out all required fields.",
            life: 3000,
        });
    }
};

// const editSuspect = (suspectData) => {
//     editDialog.value = true;
//     suspect.value = { ...suspectData };

//     suspect.value.districts = suspectData.district.map((p) => p.id);
//     suspectDialog.value = true;
// };

const editSuspect = (suspectData) => {
    editDialog.value = true;
    suspect.value = { ...suspectData };

    // Check if suspectData.district is an array before mapping
    if (Array.isArray(suspectData.district)) {
        suspect.value.districts = suspectData.district.map((p) => p.id); // Assuming districts are objects with 'id' and 'name'
    } else if (
        suspectData.district &&
        typeof suspectData.district === "object"
    ) {
        // Handle the case where suspectData.district is a single object
        suspect.value.districts = [suspectData.district.id];
    } else {
        // Handle the case where suspectData.district is null or undefined
        suspect.value.districts = [];
    }

    suspectDialog.value = true;
};

const deleteSuspect = async () => {
    loading.value = true;
    try {
        await axios.delete(`/suspects/${suspect.value.id}`);
        suspects.value = suspects.value.filter(
            (r) => r.id !== suspect.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "suspect Deleted",
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
        } else {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "An unexpected error occurred.",
                life: 5000,
            });
        }
    } finally {
        deleteSuspectDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSuspect = (roleData) => {
    suspect.value = roleData;
    deleteSuspectDialog.value = true;
};

const deleteSelectedSuspects = async () => {
    const ids = selectedRoles.value.map((suspect) => suspect.id);
    loading.value = true;
    try {
        await axios.post("/suspects/bulk-delete", { ids });
        suspects.value = suspects.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected suspects Deleted",
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
        } else {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "An unexpected error occurred.",
                life: 5000,
            });
        }
    } finally {
        deleteRolesDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteRolesDialog.value = true;
};

const updateSuspect = (updatedSuspect) => {
    const index = suspects.value.findIndex((r) => r.id === updatedSuspect.id);
    if (index !== -1) {
        suspects.value[index] = updatedSuspect;
    }
};

const exportCSV = () => {
    dt.value?.exportCSV();
};
</script>

<style scoped>
.profile-image img {
    border-radius: 15px;
    width: 50px;
    height: 100px;
    object-fit: cover;
}
</style>
