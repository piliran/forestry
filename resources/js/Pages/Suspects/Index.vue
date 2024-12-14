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
            <div class="card">
                <Toolbar class="mb-6">
                    <template #start>
                        <!-- <Button
                            label="New"
                            icon="pi pi-plus"
                            class="mr-2"
                            @click="router.visit('add-suspect')"
                        /> -->

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
                                <InputIcon id="icon">
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText
                                    id="search"
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
                            <Image
                                v-if="
                                    slotProps.data.suspect_photo_path &&
                                    !slotProps.data.suspect_photo_path.endsWith(
                                        '/null'
                                    )
                                "
                                :src="`${slotProps.data.suspect_photo_path}`"
                                :alt="slotProps.data.suspect_photo_path"
                                preview
                            >
                                <template #image>
                                    <img
                                        v-if="
                                            slotProps.data.suspect_photo_path &&
                                            !slotProps.data.suspect_photo_path.endsWith(
                                                '/null'
                                            )
                                        "
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
                        header="Status"
                        field="status"
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
                                icon="pi pi-eye"
                                outlined
                                rounded
                                class="mr-2"
                                severity="info"
                                @click="viewSuspect(slotProps.data)"
                            />
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
                :style="{ width: '500px' }"
                :header="editDialog ? 'Edit suspect' : 'Add New suspect'"
                :modal="true"
            >
                <!-- <div class="flex flex-col gap-6">
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
                </div> -->

                <div class="card flex justify-center">
                    <Stepper value="1" linear class="basis-[40rem]">
                        <StepList>
                            <Step value="1">Suspect</Step>
                            <Step value="2">Crimes</Step>
                            <Step value="3">Confiscated</Step>
                        </StepList>
                        <StepPanels>
                            <StepPanel v-slot="{ activateCallback }" value="1">
                                <div
                                    class="flex flex-col w-full gap-6 p-4 border-2 border-dashed border-surface-200 dark:border-surface-700 rounded bg-surface-50 dark:bg-surface-950"
                                >
                                    <!-- Suspect Name -->
                                    <div class="flex flex-col w-full">
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
                                    <div class="flex flex-col w-full">
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

                                    <div class="flex flex-col w-full">
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

                                    <div class="col-12 md:col-6">
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

                                    <div class="flex flex-col w-full">
                                        <label
                                            for="districts"
                                            class="block font-bold mb-3"
                                        >
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

                                    <div class="flex flex-col w-full">
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
                                    <div class="flex flex-col w-full">
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
                                <div class="flex pt-6 justify-end">
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
                                <div
                                    class="flex flex-col gap-2 mx-auto"
                                    style="min-height: 16rem; max-width: 24rem"
                                >
                                    <div
                                        class="text-center mt-4 mb-4 text-xl font-semibold"
                                    >
                                        Choose suspect crimes
                                    </div>
                                    <div
                                        class="flex flex-wrap justify-center gap-4"
                                    >
                                        <div
                                            v-for="(
                                                crime, index
                                            ) in forestryCrimes"
                                            :key="index"
                                        >
                                            <ToggleButton
                                                v-model="crime.value"
                                                :onLabel="crime.label"
                                                :offLabel="crime.label"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="flex pt-6 justify-between">
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
                                <div class="flex flex-col gap-4">
                                    <p class="font-bold text-lg">
                                        Select Confiscated Items
                                    </p>

                                    <!-- List of Items with Checkboxes -->
                                    <div
                                        v-for="item in confiscatedItems"
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
                                                >{{ item.name }}</label
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
                                                {{ item.name }}:
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
                                                <!-- Safely access item name by checking for existence -->
                                                {{ getItemNameById(id) }}:
                                                {{ quantity }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="pt-6">
                                    <Button
                                        label="Back"
                                        severity="secondary"
                                        icon="pi pi-arrow-left"
                                        @click="activateCallback('2')"
                                    />
                                </div>
                            </StepPanel>
                        </StepPanels>
                    </Stepper>
                </div>
                <!-- <template #footer>
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
                </template> -->
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

            <Dialog
                v-model:visible="viewDialog"
                modal
                header="Suspect Details"
                :style="{ width: '450px' }"
            >
                <div class="p-6 space-y-6">
                    <!-- Grid Container for Image and Details -->
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Suspect Photo on the Left -->
                        <div
                            class="col-span-1 flex justify-center items-center"
                        >
                            <div
                                class="w-full h-48 overflow-hidden rounded-lg border shadow-md"
                            >
                                <img
                                    v-if="suspect.suspect_photo_path"
                                    :src="suspect.suspect_photo_path"
                                    alt="Suspect Photo"
                                    class="object-contain w-full h-full"
                                />
                                <div v-else class="text-gray-500">
                                    No Photo Available
                                </div>
                            </div>
                        </div>
                        <!-- Suspect Data on the Right -->
                        <div class="col-span-1 flex flex-col space-y-3">
                            <div class="space-y-1">
                                <span
                                    class="font-semibold text-lg text-gray-800"
                                    >Name</span
                                >
                                <div class="text-base text-gray-600">
                                    {{ suspect.name || "N/A" }}
                                </div>
                            </div>
                            <Divider />
                            <div class="space-y-1">
                                <span
                                    class="font-semibold text-lg text-gray-800"
                                    >National ID</span
                                >
                                <div class="text-base text-gray-600">
                                    {{ suspect.national_id || "N/A" }}
                                </div>
                            </div>
                            <Divider />

                            <div class="space-y-1">
                                <span
                                    class="font-semibold text-lg text-gray-800"
                                    >District</span
                                >
                                <div class="text-base text-gray-600">
                                    {{ suspect.district?.name || "N/A" }}
                                </div>
                            </div>
                            <Divider />

                            <div class="space-y-1">
                                <span
                                    class="font-semibold text-lg text-gray-800"
                                    >Village</span
                                >
                                <div class="text-base text-gray-600">
                                    {{ suspect.village || "N/A" }}
                                </div>
                            </div>
                            <Divider />

                            <div class="space-y-1">
                                <span
                                    class="font-semibold text-lg text-gray-800"
                                    >T/A</span
                                >
                                <div class="text-base text-gray-600">
                                    {{ suspect.TA || "N/A" }}
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
import { Link, router } from "@inertiajs/vue3";
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

const breadCumbItems = ref([{ label: "Suspects" }]);
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
});

const suspects = ref(props.suspects);
const countries = ref(props.countries);

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

const forestryCrimes = ref([
    { label: "Illegal Logging", value: false },
    { label: "Wildlife Poaching", value: false },
    { label: "Forest Encroachment", value: false },
    { label: "Timber Smuggling", value: false },
    { label: "Unlawful Burning", value: false },
]);

const confiscatedItems = ref([
    { id: 1, name: "Chainsaw" },
    { id: 2, name: "Truckloads of Timber" },
    { id: 3, name: "Animal Skins" },
    { id: 4, name: "Hunting Rifles" },
    { id: 5, name: "Unlicensed Vehicles" },
]);

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
    const item = confiscatedItems.value.find(
        (item) => item.id === parseInt(id)
    );
    return item ? item.name : "Item not found";
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
        return false;
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
