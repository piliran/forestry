<template>
    <AppLayout title="Confiscates">
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
                                !selectedConfiscates ||
                                !selectedConfiscates.length
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
                    v-if="confiscates.length > 0"
                    ref="dt"
                    v-model:selection="selectedConfiscates"
                    :value="confiscates"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Confiscates"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Confiscates</h4>
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
                        field="item"
                        header="Item Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="quantity"
                        header="Quantity"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="suspect.name"
                        header="Suspect"
                        sortable
                        style="min-width: 10rem"
                    ></Column>

                    <Column header="Proof">
                        <template #body="slotProps">
                            <!-- Check if the proof exists and is not null -->
                            <div
                                v-if="
                                    slotProps.data.proof &&
                                    !slotProps.data.proof.endsWith('/null')
                                "
                            >
                                <!-- Render an Image Preview if the file is an image -->
                                <Image
                                    v-if="isImage(slotProps.data.proof)"
                                    :src="`${slotProps.data.proof}`"
                                    :alt="slotProps.data.proof"
                                    preview
                                >
                                    <template #image>
                                        <img
                                            :src="slotProps.data.proof"
                                            alt="proof"
                                            style="
                                                width: 60px;
                                                height: 40px;
                                                object-fit: cover;
                                            "
                                        />
                                    </template>
                                </Image>

                                <!-- Render a Video Player if the file is a video -->
                                <video
                                    v-else
                                    controls
                                    style="
                                        width: 60px;
                                        height: 40px;
                                        object-fit: cover;
                                    "
                                >
                                    <source
                                        :src="slotProps.data.proof"
                                        type="video/mp4"
                                    />
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </template>
                    </Column>

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
                                @click="viewConfiscate(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-pencil"
                                outlined
                                rounded
                                class="mr-2"
                                @click="editConfiscate(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteConfiscate(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Confiscates Found</h2>
                </div>
            </div>

            <!-- Add/Edit Confiscates Dialog -->
            <Dialog
                v-model:visible="confiscateDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Confiscate' : 'Add New Confiscate'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            Item Name
                        </label>
                        <InputText
                            id="name"
                            v-model.trim="confiscate.item"
                            required="true"
                            autofocus
                            :invalid="submitted && !confiscate.itemm"
                            fluid
                        />
                        <small
                            v-if="submitted && !confiscate.item"
                            class="text-red-500"
                        >
                            Confiscate Item is required.
                        </small>
                    </div>

                    <div>
                        <label for="quantity" class="block font-bold mb-3">
                            Quantity
                        </label>
                        <InputText
                            id="name"
                            v-model.trim="confiscate.quantity"
                            required="true"
                            autofocus
                            :invalid="submitted && !confiscate.quantity"
                            fluid
                        />
                        <small
                            v-if="submitted && !confiscate.quantity"
                            class="text-red-500"
                        >
                            Quantity is required.
                        </small>
                    </div>

                    <div class="col-12 md:col-6">
                        <label for="confiscate" class="block font-bold mb-2"
                            >Suspect</label
                        >
                        <Select
                            id="id"
                            v-model="confiscate.suspect_id"
                            :options="suspects"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Suspect"
                            fluid
                        />
                        <small
                            v-if="submitted && !confiscate.suspect_id"
                            class="text-red-500"
                        >
                            Suspect Name is required.
                        </small>
                    </div>
                    <div>
                        <label for="proof" class="block font-bold mb-3"
                            >Proof</label
                        >

                        <!-- File Upload -->
                        <FileUpload
                            ref="fileupload"
                            mode="basic"
                            name="proof[]"
                            @select="onFileSelect"
                            accept="image/*,video/*"
                            :maxFileSize="9000000"
                        />

                        <!-- Image Preview -->
                        <div
                            v-if="previewType === 'image'"
                            class="mt-3 flex justify-center"
                        >
                            <img
                                :src="previewUrl"
                                alt="Selected proof"
                                width="250"
                                class="border rounded-md"
                            />
                        </div>

                        <!-- Video Preview -->
                        <div
                            v-if="previewType === 'video'"
                            class="mt-3 flex justify-center"
                        >
                            <video
                                controls
                                width="250"
                                class="border rounded-md"
                            >
                                <source :src="previewUrl" type="video/mp4" />
                                Your browser does not support the video tag.
                            </video>
                        </div>

                        <!-- Existing Proof Preview -->
                        <div
                            v-if="confiscate.proof"
                            class="mt-3 flex justify-center items-center space-x-3"
                        >
                            <!-- Display the image if the proof is an image -->
                            <img
                                v-if="isImage(confiscate.proof)"
                                :src="confiscate.proof"
                                alt="proof"
                                class="border rounded-md"
                                width="250"
                            />

                            <!-- Display the video if the proof is a video -->
                            <video
                                v-else
                                controls
                                width="250"
                                class="border rounded-md"
                            >
                                <source
                                    :src="confiscate.proof"
                                    type="video/mp4"
                                />
                                Your browser does not support the video tag.
                            </video>
                        </div>

                        <!-- Error Message -->
                        <small
                            v-if="submitted && !fileupload"
                            class="text-red-500"
                        >
                            Proof is required.
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
                            @click="saveConfiscate"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Confiscate Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteConfiscateDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="confiscate">
                        Are you sure you want to delete
                        <b>{{ confiscate.item }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteConfiscateDialog = false"
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
                            @click="deleteConfiscate"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Confiscate Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteConfiscatesDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected
                        Confiscates?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteConfiscateDialog = false"
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
                            @click="deleteSelectedConfiscates"
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
                            v-if="confiscate.proof"
                            class="col-span-1 flex justify-center items-center"
                        >
                            <div
                                class="w-full h-48 overflow-hidden rounded-lg border shadow-md"
                            >
                                <img
                                    v-if="isImage(confiscate.proof)"
                                    :src="confiscate.proof"
                                    alt="proof"
                                    class="object-contain w-full h-full border rounded-md"
                                />

                                <!-- Display the video if the proof is a video -->
                                <video
                                    v-else
                                    controls
                                    class="object-cover w-full h-full border rounded-md"
                                >
                                    <source
                                        :src="confiscate.proof"
                                        type="video/mp4"
                                    />
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                        <!-- Suspect Data on the Right -->
                        <div class="col-span-1 flex flex-col space-y-3">
                            <div class="space-y-1">
                                <span
                                    class="font-semibold text-lg text-gray-800"
                                    >Item</span
                                >
                                <div class="text-base text-gray-600">
                                    {{ confiscate.item || "N/A" }}
                                </div>
                            </div>
                            <Divider />
                            <div class="space-y-1">
                                <span
                                    class="font-semibold text-lg text-gray-800"
                                    >Quantity</span
                                >
                                <div class="text-base text-gray-600">
                                    {{ confiscate.quantity || "N/A" }}
                                </div>
                            </div>
                            <Divider />
                            <div class="space-y-1">
                                <span
                                    class="font-semibold text-lg text-gray-800"
                                    >Suspect</span
                                >
                                <div class="text-base text-gray-600">
                                    {{ confiscate.suspect.name || "N/A" }}
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
import ProgressSpinner from "primevue/progressspinner";
import Breadcrumb from "primevue/breadcrumb";
import { Link } from "@inertiajs/vue3";
import Image from "primevue/image";
import FileUpload from "primevue/fileupload";
import Divider from "primevue/divider";

import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const toast = useToast();

// Reactive State Variables
const dt = ref();
const confiscateDialog = ref(false);
const editDialog = ref(false);
const viewDialog = ref(false);

const loading = ref(false);
const deleteConfiscateDialog = ref(false);
const deleteConfiscatesDialog = ref(false);
const confiscate = ref({});
const selectedConfiscates = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    confiscates: Array,
    suspects: Array,
    encroached_areas: Array,
});

const confiscates = ref(props.confiscates);
const suspects = ref(props.suspects);
const encroached_areas = ref(props.encroached_areas);

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Confiscates" }]);
const fileupload = ref(); // Reference to FileUpload component
const previewUrl = ref(null); // URL for preview
const previewType = ref(null); // Type of preview (image or video)

const isImage = (proof) => {
    const imageExtensions = [".png", ".jpg", ".jpeg", ".gif", ".bmp", ".webp"];
    return imageExtensions.some((ext) => proof.toLowerCase().endsWith(ext));
};
// Handle file selection
const onFileSelect = (event) => {
    const file = event.files[0]; // Get the first selected file
    if (file) {
        const fileType = file.type;

        if (fileType.startsWith("image/")) {
            previewType.value = "image"; // Set type to image
        } else if (fileType.startsWith("video/")) {
            previewType.value = "video"; // Set type to video
        } else {
            previewType.value = null;
            previewUrl.value = null;
            console.error("Unsupported file type");
            return;
        }

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
    confiscate.value = {};
    submitted.value = false;
    confiscateDialog.value = true;
};

const hideDialog = () => {
    confiscateDialog.value = false;
    submitted.value = false;
};

const saveConfiscate = async () => {
    submitted.value = true;

    // Validation: Ensure the item name and quantity are provided
    if (!confiscate.value.item?.trim() || !confiscate.value.quantity) {
        toast.add({
            severity: "error",
            summary: "Validation Error",
            detail: "Item Name and Quantity are required.",
            life: 3000,
        });
        return;
    }

    // Show loading spinner
    loading.value = true;

    try {
        const formData = new FormData();

        // Add confiscate details to formData
        formData.append("item", confiscate.value.item);
        formData.append("quantity", confiscate.value.quantity);
        formData.append("suspect_id", confiscate.value.suspect_id || "");

        // Add proof file if selected
        const file = fileupload.value?.files?.[0]; // Get the first file
        if (file) {
            formData.append("proof", file);
        }

        let response;
        if (confiscate.value.id) {
            formData.append("id", confiscate.value.id);

            // Update existing confiscate
            response = await axios.post(`/update-confiscate`, formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            updateConfiscate(response.data); // Update the confiscate list
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Confiscate Updated",
                life: 3000,
            });
        } else {
            // Create new confiscate
            response = await axios.post("/confiscates", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            confiscates.value.push(response.data); // Add to confiscate list
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Confiscate Created",
                life: 3000,
            });
        }
    } catch (err) {
        // Handle validation or other errors
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
                detail: "You are not allowed to perform this action.",
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
        confiscateDialog.value = false; // Close the dialog
    }
};

const editConfiscate = (confiscateData) => {
    editDialog.value = true;
    confiscate.value = { ...confiscateData };
    confiscateDialog.value = true;
};

const viewConfiscate = (confiscateData) => {
    confiscate.value = { ...confiscateData };
    viewDialog.value = true;
};

const deleteConfiscate = async () => {
    loading.value = true;
    try {
        await axios.delete(`/confiscates/${confiscate.value.id}`);
        confiscates.value = confiscates.value.filter(
            (r) => r.id !== confiscate.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Confiscate Deleted",
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
        deleteConfiscateDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteConfiscate = (confiscateData) => {
    confiscate.value = confiscateData;
    deleteConfiscateDialog.value = true;
};

const deleteSelectedConfiscates = async () => {
    const ids = selectedConfiscates.value.map((confiscate) => confiscate.id);
    loading.value = true;
    try {
        await axios.post("/confiscates/bulk-delete", { ids });
        confiscates.value = confiscates.value.filter(
            (r) => !ids.includes(r.id)
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Confiscate Deleted",
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
        deleteConfiscatesDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteConfiscatesDialog.value = true;
};

const updateConfiscate = (updatedConfiscate) => {
    const index = confiscates.value.findIndex(
        (r) => r.id === updatedConfiscate.id
    );
    if (index !== -1) {
        confiscates.value[index] = updatedConfiscate;
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
