<template>
    <AppLayout title="Suspects">
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
            <div class="">
                <!-- Toast Notifications -->
                <Toast />

                <div class="flex justify-center">
                    <Stepper value="1" linear class="basis-full">
                        <StepList>
                            <Step value="1">Suspect</Step>
                            <Step value="2">Crimes</Step>
                            <Step value="3">Confiscated</Step>
                        </StepList>
                        <StepPanels>
                            <StepPanel v-slot="{ activateCallback }" value="1">
                                <div
                                    class="text-center mt-4 mb-4 pt-6 text-xl font-semibold"
                                >
                                    Suspect information
                                </div>
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border-surface-200 dark:border-surface-700 rounded bg-surface-50 dark:bg-surface-950"
                                >
                                    <div class="flex flex-col">
                                        <label
                                            for="name"
                                            class="block font-bold mb-2"
                                            >Suspect Name</label
                                        >
                                        <InputText
                                            id="name"
                                            v-model.trim="suspect.name"
                                            required="true"
                                            autofocus
                                            :invalid="
                                                submitFirst && !suspect.name
                                            "
                                            class="w-full"
                                        />
                                        <small
                                            v-if="submitFirst && !suspect.name"
                                            class="text-red-500"
                                        >
                                            Suspect Name is required.
                                        </small>
                                    </div>

                                    <!-- Suspect National ID -->
                                    <div class="flex flex-col">
                                        <label
                                            for="national_id"
                                            class="block font-bold mb-2"
                                            >Suspect National ID</label
                                        >
                                        <InputText
                                            id="national_id"
                                            v-model.trim="suspect.national_id"
                                            required="true"
                                            autofocus
                                            :invalid="
                                                submitFirst &&
                                                !suspect.national_id
                                            "
                                            class="w-full"
                                        />
                                        <small
                                            v-if="
                                                submitFirst &&
                                                !suspect.national_id
                                            "
                                            class="text-red-500"
                                        >
                                            Suspect National ID is required.
                                        </small>
                                    </div>

                                    <!-- Date of Birth -->
                                    <div class="flex flex-col">
                                        <label
                                            for="dob"
                                            class="block font-bold mb-2"
                                            >Date of Birth</label
                                        >
                                        <DatePicker
                                            fluid
                                            v-model="suspect.DOB"
                                            dateFormat="dd/mm/yy"
                                            placeholder="YYYY-MM-DD"
                                            :invalid="
                                                submitFirst && !suspect.DOB
                                            "
                                        />
                                        <small
                                            v-if="submitFirst && !suspect.DOB"
                                            class="text-red-500"
                                        >
                                            Date of birth is required.
                                        </small>
                                    </div>

                                    <!-- Gender -->
                                    <div class="flex flex-col">
                                        <label
                                            for="gender"
                                            class="block font-bold mb-2"
                                            >Gender</label
                                        >
                                        <Select
                                            id="gender"
                                            v-model="suspect.gender"
                                            :options="genderOptions"
                                            placeholder="Select gender"
                                            fluid
                                            :invalid="
                                                submitFirst && !suspect.gender
                                            "
                                        />
                                        <small
                                            v-if="
                                                submitFirst && !suspect.gender
                                            "
                                            class="text-red-500"
                                        >
                                            Gender is required.
                                        </small>
                                    </div>

                                    <!-- District -->
                                    <div class="flex flex-col">
                                        <label
                                            for="districts"
                                            class="block font-bold mb-3"
                                            >District</label
                                        >
                                        <Select
                                            id="districts"
                                            v-model="suspect.district_id"
                                            :options="props.districts"
                                            optionLabel="name"
                                            optionValue="id"
                                            placeholder="Select district"
                                            fluid
                                            :invalid="
                                                submitFirst &&
                                                !suspect.district_id
                                            "
                                        />
                                        <small
                                            v-if="
                                                submitFirst &&
                                                !suspect.district_id
                                            "
                                            class="text-red-500"
                                        >
                                            District is required.
                                        </small>
                                    </div>

                                    <!-- Suspect Village -->
                                    <div class="flex flex-col">
                                        <label
                                            for="village"
                                            class="block font-bold mb-3"
                                            >Suspect Village</label
                                        >
                                        <InputText
                                            id="village"
                                            v-model.trim="suspect.village"
                                            required="true"
                                            autofocus
                                            :invalid="
                                                submitFirst && !suspect.village
                                            "
                                            fluid
                                        />
                                        <small
                                            v-if="
                                                submitFirst && !suspect.village
                                            "
                                            class="text-red-500"
                                        >
                                            Suspect Village is required.
                                        </small>
                                    </div>

                                    <!-- Suspect T/A -->
                                    <div class="flex flex-col">
                                        <label
                                            for="TA"
                                            class="block font-bold mb-3"
                                            >Suspect T/A</label
                                        >
                                        <InputText
                                            id="TA"
                                            v-model.trim="suspect.TA"
                                            required="true"
                                            autofocus
                                            :invalid="
                                                submitFirst && !suspect.TA
                                            "
                                            fluid
                                        />
                                        <small
                                            v-if="submitFirst && !suspect.TA"
                                            class="text-red-500"
                                        >
                                            Suspect T/A is required.
                                        </small>
                                    </div>
                                </div>

                                <!-- Next Button -->
                                <div class="flex pt-6 p-4 justify-end">
                                    <Button
                                        label="Next"
                                        icon="pi pi-arrow-right"
                                        @click="
                                            () => {
                                                if (validateStep1())
                                                    activateCallback('2');
                                            }
                                        "
                                    />
                                </div>
                            </StepPanel>

                            <StepPanel v-slot="{ activateCallback }" value="2">
                                <div class="flex flex-col gap-2 p-4 mx-auto">
                                    <div
                                        class="text-center mt-4 mb-4 text-xl font-semibold"
                                    >
                                        Choose suspect crimes
                                    </div>

                                    <div class="flex flex-wrap gap-4">
                                        <div
                                            v-for="(
                                                crime, index
                                            ) in props.crimes"
                                            :key="index"
                                            class="flex items-center space-x-2"
                                        >
                                            <input
                                                type="checkbox"
                                                v-model="crime.value"
                                                id="id"
                                                class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring focus:ring-blue-300 focus:ring-opacity-50"
                                            />
                                            <label
                                                :for="`crime-${index}`"
                                                class="text-sm font-medium"
                                            >
                                                {{ crime.name }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex pt-6 p-4 justify-between">
                                    <Button
                                        label="Back"
                                        severity="secondary"
                                        icon="pi pi-arrow-left"
                                        @click="activateCallback('1')"
                                    />
                                    <Button
                                        label="Next"
                                        icon="pi pi-arrow-right"
                                        iconPos="right"
                                        @click="activateCallback('3')"
                                    />
                                </div>
                            </StepPanel>
                            <StepPanel v-slot="{ activateCallback }" value="3">
                                <div class="flex flex-col gap-4 p-4">
                                    <p class="font-bold text-lg">
                                        Select Confiscated Items
                                    </p>

                                    <!-- List of Items with Checkboxes -->
                                    <div
                                        v-for="item in confiscates"
                                        :key="item.id"
                                        class="flex flex-col gap-2 border border-surface-200 dark:border-surface-700 rounded p-2 bg-surface-50 dark:bg-surface-950"
                                    >
                                        <div class="flex items-center gap-3">
                                            <input
                                                type="checkbox"
                                                :value="item.id"
                                                :id="`checkbox-${item.id}`"
                                                @change="
                                                    handleCheckboxChange(item)
                                                "
                                                class="h-5 w-5"
                                            />
                                            <label
                                                :for="`checkbox-${item.id}`"
                                                class="text-base"
                                                >{{ item.item }}</label
                                            >
                                        </div>

                                        <!-- Quantity Input -->
                                        <div
                                            v-if="selectedItems[item.id]"
                                            class="mt-2"
                                        >
                                            <label
                                                for="quantity"
                                                class="block font-semibold mb-1"
                                            >
                                                Enter quantity for
                                                {{ item.item }}:
                                            </label>
                                            <input
                                                type="number"
                                                v-model.number="
                                                    selectedItems[item.id]
                                                "
                                                min="1"
                                                placeholder="Enter quantity"
                                                class="border rounded p-2 w-full"
                                            />
                                        </div>
                                    </div>

                                    <!-- Display Selected Items and Quantities -->
                                    <div
                                        v-if="Object.keys(selectedItems).length"
                                        class="mt-4"
                                    >
                                        <p class="font-bold text-lg">
                                            Selected Items and Quantities:
                                        </p>
                                        <ul class="list-disc pl-6">
                                            <li
                                                v-for="(
                                                    quantity, id
                                                ) in selectedItems"
                                                :key="id"
                                                class="text-base"
                                            >
                                                {{ getItemNameById(id) }}:
                                                {{ quantity }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="flex pt-6 p-4 justify-between">
                                    <Button
                                        label="Back"
                                        severity="secondary"
                                        icon="pi pi-arrow-left"
                                        @click="activateCallback('2')"
                                    />
                                    <Button label="Submit" />
                                </div>
                            </StepPanel>
                        </StepPanels>
                    </Stepper>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script setup>
import { ref, onMounted } from "vue";
import { FilterMatchMode } from "@primevue/core/api";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import Checkbox from "primevue/checkbox";

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
import Breadcrumb from "primevue/breadcrumb";
import { Link } from "@inertiajs/vue3";
import Divider from "primevue/divider";
import DatePicker from "primevue/datepicker";
import ToggleButton from "primevue/togglebutton";

import Stepper from "primevue/stepper";
import StepList from "primevue/steplist";
import StepPanels from "primevue/steppanels";
import StepItem from "primevue/stepitem";
import Step from "primevue/step";
import StepPanel from "primevue/steppanel";

const toast = useToast();

// Reactive State Variables
const dt = ref();
const suspectDialog = ref(false);
const editDialog = ref(false);
const viewDialog = ref(false);
const loading = ref(false);
const deleteSuspectDialog = ref(false);
const deleteRolesDialog = ref(false);
const suspect = ref({});
const selectedRoles = ref([]);
const submitted = ref(false);
const submitFirst = ref(false);
const submitSecond = ref(false);
const submitThird = ref(false);

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([
    { label: "Suspects", route: "/suspects" },
    { label: "Add Suspect" },
]);
const genderOptions = ["Male", "Female", "Other"];

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
    crimes: Array,
    confiscates: Array,
});

const suspects = ref(props.suspects);
const countries = ref(props.countries);
const confiscates = ref(props.confiscates);

const previewUrl = ref(null);
const onFileSelect = (event) => {
    const file = event.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrl.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const selectedItems = ref([]);

const handleCheckboxChange = (item) => {
    if (selectedItems.value[item.id]) {
        delete selectedItems.value[item.id];
    } else {
        selectedItems.value[item.id] = 1;
    }
};

// Method to safely get item name by id
const getItemNameById = (id) => {
    const item = confiscates.value.find((item) => item.id === parseInt(id));
    return item ? item.item : "Item not found";
};

const validateStep1 = () => {
    submitFirst.value = true;
    if (
        !suspect.value.name ||
        !suspect.value.national_id ||
        !suspect.value.district_id ||
        !suspect.value.village ||
        !suspect.value.TA
    ) {
        return true;
    }
    return true;
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

const viewSuspect = (suspectData) => {
    suspect.value = { ...suspectData };
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
    viewDialog.value = true;
};

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
::v-deep(.p-breadcrumb) {
    background: transparent !important;
    box-shadow: none !important;
}
</style>
