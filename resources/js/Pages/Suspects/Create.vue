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
                            <Step value="2">Offenses</Step>
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
                                    <div class="flex flex-col flex-1">
                                        <label
                                            for="suspect"
                                            class="block font-bold mb-3"
                                        >
                                            Suspect Photo
                                        </label>

                                        <FileUpload
                                            ref="suspectfileupload"
                                            mode="basic"
                                            name="suspect[]"
                                            @select="onSuspectFileSelect"
                                            accept="image/*,video/*"
                                            :maxFileSize="9000000"
                                            multiple
                                        />

                                        <!-- Image Preview -->
                                        <div
                                            v-if="
                                                previewSuspectType === 'image'
                                            "
                                            class="mt-3 flex justify-center"
                                        >
                                            <img
                                                :src="previewSuspectUrl"
                                                alt="Selected proof"
                                                width="250"
                                                class="border rounded-md"
                                            />
                                        </div>

                                        <!-- Video Preview -->
                                        <div
                                            v-if="
                                                previewSuspectType === 'video'
                                            "
                                            class="mt-3 flex justify-center"
                                        >
                                            <video
                                                controls
                                                width="250"
                                                class="border rounded-md"
                                            >
                                                <source
                                                    :src="previewSuspectUrl"
                                                    type="video/mp4"
                                                />
                                                Your browser does not support
                                                the video tag.
                                            </video>
                                        </div>

                                        <!-- Validation Error -->
                                        <small
                                            v-if="
                                                submitted && !suspectfileupload
                                            "
                                            class="text-red-500"
                                        >
                                            Suspect photo is required.
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
                                        Choose suspect offenses
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
                                <div class="flex flex-col gap-2 p-4 mx-auto">
                                    <div
                                        class="text-center mt-4 mb-4 text-xl font-semibold"
                                    >
                                        Select confiscates
                                    </div>

                                    <div class="flex gap-4 items-center">
                                        <div
                                            v-for="item in confiscates"
                                            :key="item.id"
                                            class="flex items-center space-x-2"
                                        >
                                            <input
                                                type="checkbox"
                                                :value="item.id"
                                                :id="`checkbox-${item.id}`"
                                                @change="
                                                    handleCheckboxChange(item)
                                                "
                                                class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring focus:ring-blue-300 focus:ring-opacity-50"
                                            />
                                            <label
                                                :for="`checkbox-${item.id}`"
                                                class="text-sm font-medium"
                                            >
                                                {{ item.item }}
                                            </label>
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
                                            label="Submit"
                                            @click="saveSuspect"
                                        />
                                    </div>
                                </div>
                            </StepPanel>
                        </StepPanels>
                    </Stepper>
                </div>
            </div>

            <Dialog
                v-model:visible="addConfiscateDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="grid gap-4">
                    <!-- Quantity Input Section -->
                    <div class="flex flex-col flex-1">
                        <label for="quantity" class="block font-semibold mb-1">
                            Enter quantity for {{ checkedItem.item }}:
                        </label>
                        <input
                            type="number"
                            v-model="selectedItems[checkedItem.id].quantity"
                            v-if="
                                checkedItem &&
                                checkedItem.id &&
                                selectedItems[checkedItem.id]
                            "
                            min="1"
                            placeholder="Enter quantity"
                            class="border rounded p-2 w-full"
                        />
                    </div>

                    <!-- Proof Section -->
                    <div class="flex flex-col flex-1">
                        <label for="proof" class="block font-bold mb-3">
                            Proof
                        </label>
                        <FileUpload
                            name="demo[]"
                            url="/api/upload"
                            @upload="onTemplatedUpload($event)"
                            :multiple="true"
                            accept="image/*"
                            :maxFileSize="1000000"
                            @select="onSelectedFiles"
                        >
                            <template
                                #header="{
                                    chooseCallback,
                                    uploadCallback,
                                    clearCallback,
                                    files,
                                }"
                            >
                                <div
                                    class="flex flex-wrap justify-between items-center flex-1 gap-4"
                                >
                                    <div class="flex gap-2">
                                        <Button
                                            @click="chooseCallback()"
                                            icon="pi pi-images"
                                            rounded
                                            outlined
                                            severity="secondary"
                                        ></Button>
                                        <!-- <Button
                                            @click="uploadEvent(uploadCallback)"
                                            icon="pi pi-cloud-upload"
                                            rounded
                                            outlined
                                            severity="success"
                                            :disabled="
                                                !files || files.length === 0
                                            "
                                        ></Button> -->
                                        <Button
                                            @click="clearCallback()"
                                            icon="pi pi-times"
                                            rounded
                                            outlined
                                            severity="danger"
                                            :disabled="
                                                !files || files.length === 0
                                            "
                                        ></Button>
                                    </div>
                                    <!-- <ProgressBar
                                        :value="totalSizePercent"
                                        :showValue="false"
                                        class="md:w-20rem h-1 w-full md:ml-auto"
                                    >
                                        <span class="whitespace-nowrap"
                                            >{{ totalSize }}B / 1Mb</span
                                        >
                                    </ProgressBar> -->
                                </div>
                            </template>
                            <template
                                #content="{
                                    files,
                                    uploadedFiles,
                                    removeUploadedFileCallback,
                                    removeFileCallback,
                                }"
                            >
                                <div class="flex flex-col gap-8 pt-4">
                                    <div v-if="files.length > 0">
                                        <!-- <h5>Pending</h5> -->
                                        <div class="flex flex-wrap gap-4">
                                            <div
                                                v-for="(file, index) of files"
                                                :key="
                                                    file.name +
                                                    file.type +
                                                    file.size
                                                "
                                                class="rounded-border flex flex-col items-center gap-4"
                                            >
                                                <div>
                                                    <img
                                                        role="presentation"
                                                        :alt="file.name"
                                                        :src="file.objectURL"
                                                        width="100"
                                                        height="50"
                                                    />
                                                </div>
                                                <span
                                                    class="font-semibold text-ellipsis max-w-60 whitespace-nowrap overflow-hidden"
                                                    >{{ file.name }}</span
                                                >
                                                <div>
                                                    {{ formatSize(file.size) }}
                                                </div>
                                                <!-- <Badge
                                                    value="Pending"
                                                    severity="warn"
                                                /> -->
                                                <Button
                                                    icon="pi pi-times"
                                                    @click="
                                                        onRemoveTemplatingFile(
                                                            file,
                                                            removeFileCallback,
                                                            index
                                                        )
                                                    "
                                                    outlined
                                                    rounded
                                                    severity="danger"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="uploadedFiles.length > 0">
                                        <h5>Completed</h5>
                                        <div class="flex flex-wrap gap-4">
                                            <div
                                                v-for="(
                                                    file, index
                                                ) of uploadedFiles"
                                                :key="
                                                    file.name +
                                                    file.type +
                                                    file.size
                                                "
                                                class="p-8 rounded-border flex flex-col border border-surface items-center gap-4"
                                            >
                                                <div>
                                                    <img
                                                        role="presentation"
                                                        :alt="file.name"
                                                        :src="file.objectURL"
                                                        width="100"
                                                        height="50"
                                                    />
                                                </div>
                                                <span
                                                    class="font-semibold text-ellipsis max-w-60 whitespace-nowrap overflow-hidden"
                                                    >{{ file.name }}</span
                                                >
                                                <div>
                                                    {{ formatSize(file.size) }}
                                                </div>
                                                <Badge
                                                    value="Completed"
                                                    class="mt-4"
                                                    severity="success"
                                                />
                                                <Button
                                                    icon="pi pi-times"
                                                    @click="
                                                        removeUploadedFileCallback(
                                                            index
                                                        )
                                                    "
                                                    outlined
                                                    rounded
                                                    severity="danger"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template #empty>
                                <div
                                    class="flex items-center justify-center flex-col"
                                >
                                    <i
                                        class="pi pi-cloud-upload !border-2 !rounded-full !p-8 !text-4xl !text-muted-color"
                                    />
                                    <p class="mt-6 mb-0">
                                        Drag and drop files to here to upload.
                                    </p>
                                </div>
                            </template>
                        </FileUpload>

                        <!-- <FileUpload
                            ref="fileupload"
                            name="proof[]"
                            @select="onFileSelect"
                            accept="image/*,video/*"
                            :maxFileSize="9000000"
                            @upload="onAdvancedUpload($event)"
                            customUpload
                        /> -->

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

                        <!-- Validation Error -->
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
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="addConfiscateDialog = false"
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
                            @click="uploadEvent(uploadCallback)"
                        />
                    </div>
                </template>
            </Dialog>
        </div>
    </AppLayout>
</template>
<script setup>
import { ref, onMounted, reactive } from "vue";
import { FilterMatchMode } from "@primevue/core/api";
import { usePrimeVue } from "primevue/config";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import Checkbox from "primevue/checkbox";
import CustomUpload from "./CustomUpload.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Toolbar from "primevue/toolbar";
import Dialog from "primevue/dialog";
import InputIcon from "primevue/inputicon";

import FileUpload from "primevue/fileupload";
import ProgressBar from "primevue/progressbar";
import Badge from "primevue/badge";
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

const $primevue = usePrimeVue();
const toast = useToast();

const totalSize = ref(0);
const totalSizePercent = ref(0);
const files = ref([]);

const onRemoveTemplatingFile = (file, removeFileCallback, index) => {
    removeFileCallback(index);
    totalSize.value -= parseInt(formatSize(file.size));
    totalSizePercent.value = totalSize.value / 10;
};

const onClearTemplatingUpload = (clear) => {
    clear();
    totalSize.value = 0;
    totalSizePercent.value = 0;
};

const onSelectedFiles = (event) => {
    files.value = event.files;
    selectedItems[checkedItem.value.id].files = event.files;
    files.value.forEach((file) => {
        totalSize.value += parseInt(formatSize(file.size));
    });
};

const uploadEvent = (callback) => {
    // totalSizePercent.value = totalSize.value / 10;
    // callback();
    console.log(files.value);
};

const onTemplatedUpload = () => {
    toast.add({
        severity: "info",
        summary: "Success",
        detail: "File Uploaded",
        life: 3000,
    });
};

const formatSize = (bytes) => {
    const k = 1024;
    const dm = 3;
    const sizes = $primevue.config.locale.fileSizeTypes;

    if (bytes === 0) {
        return `0 ${sizes[0]}`;
    }

    const i = Math.floor(Math.log(bytes) / Math.log(k));
    const formattedSize = parseFloat((bytes / Math.pow(k, i)).toFixed(dm));

    return `${formattedSize} ${sizes[i]}`;
};

const suspectDialog = ref(false);

const addConfiscateDialog = ref(false);

const loading = ref(false);

const suspect = ref({});
// const checkedItem = ref({});
const selectedRoles = ref([]);
const submitted = ref(false);
const submitFirst = ref(false);
const submitSecond = ref(false);
const submitThird = ref(false);

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
const suspectfileupload = ref();

const onAdvancedUpload = () => {
    toast.add({
        severity: "info",
        summary: "Success",
        detail: "File Uploaded",
        life: 3000,
    });
};

const previewUrl = ref(null);
const previewType = ref(null);
const previewSuspectUrl = ref(null);
const previewSuspectType = ref(null);

const isImage = (proof) => {
    const imageExtensions = [".png", ".jpg", ".jpeg", ".gif", ".bmp", ".webp"];
    return imageExtensions.some((ext) => proof.toLowerCase().endsWith(ext));
};

const addConfiscate = () => {
    toast.add({
        severity: "info",
        summary: "Success",
        detail: "Add successfully",
        life: 3000,
    });
};

const submitSuspect = () => {
    toast.add({
        severity: "info",
        summary: "Success",
        detail: "Submitted successfully",
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

const confiscates = ref(props.confiscates);

const onFileSelect = (event) => {
    const file = event.files[0];
    if (file) {
        const fileType = file.type;

        if (fileType.startsWith("image/")) {
            previewType.value = "image";
        } else if (fileType.startsWith("video/")) {
            previewType.value = "video";
        } else {
            previewType.value = null;
            previewUrl.value = null;
            console.error("Unsupported file type");
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrl.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const onSuspectFileSelect = (event) => {
    const file = event.files[0];
    if (file) {
        const fileType = file.type;

        if (fileType.startsWith("image/")) {
            previewSuspectType.value = "image";
        } else if (fileType.startsWith("video/")) {
            previewSuspectType.value = "video";
        } else {
            previewSuspectType.value = null;
            previewSuspectUrl.value = null;
            console.error("Unsupported file type");
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            previewSuspectUrl.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const selectedItems = reactive({});
const checkedItem = ref(null);

const handleCheckboxChange = (item) => {
    if (selectedItems[item.id]) {
        delete selectedItems[item.id];
    } else {
        selectedItems[item.id] = { id: item.id, quantity: 1, files: [] };

        checkedItem.value = item;
        console.log(selectedItems);

        addConfiscateDialog.value = true;

        previewUrl.value = null;
        previewType.value = null;
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

            // Append confiscates data
            Object.entries(selectedItems).forEach(([id, confiscate]) => {
                suspectPayload.append(`confiscates[${id}][id]`, confiscate.id);
                suspectPayload.append(
                    `confiscates[${id}][quantity]`,
                    confiscate.quantity
                );

                // Append confiscate images
                confiscate.files.forEach((file, index) => {
                    suspectPayload.append(
                        `confiscates[${id}][files][${index}]`,
                        file
                    );
                });
            });

            if (suspect.value.id) {
                // If suspect already exists, update them
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
