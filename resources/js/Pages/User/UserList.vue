<template>
    <AppLayout title="users">
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
                    v-if="users.length > 0"
                    ref="dt"
                    v-model:selection="selectedRoles"
                    :value="users"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} users"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage users</h4>
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
                            <Image
                                :src="`${slotProps.data.profile_photo_url}`"
                                :alt="slotProps.data.profile_photo_url"
                                preview
                            >
                                <template #image>
                                    <img
                                        :src="slotProps.data.profile_photo_url"
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
                        header="Title"
                        field="title"
                        sortable
                        style="min-width: 4rem"
                    ></Column>
                    <Column
                        field="name"
                        header="Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>

                    <Column
                        header="Gender"
                        field="gender"
                        sortable
                        style="min-width: 12rem"
                    ></Column>
                    <Column
                        header="Email"
                        field="email"
                        sortable
                        style="min-width: 12rem"
                    ></Column>
                    <Column
                        header="District"
                        field="district.name"
                        sortable
                        style="min-width: 12rem"
                    ></Column>
                    <Column
                        header="Role"
                        :exportable="false"
                        style="min-width: 8rem"
                    >
                        <template #body="slotProps">
                            <Button
                                @click="assignAndEditRoles(slotProps.data)"
                                icon="pi pi-eye"
                                outlined
                                severity="info"
                            />
                        </template>
                    </Column>
                    <Column
                        header="Permissions"
                        :exportable="false"
                        style="min-width: 8rem"
                    >
                        <template #body="slotProps">
                            <Button
                                @click="
                                    assignAndEditPermissions(slotProps.data)
                                "
                                icon="pi pi-eye"
                                outlined
                                severity="info"
                            />
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
                                @click="viewUser(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-pencil"
                                outlined
                                rounded
                                class="mr-2"
                                @click="editUser(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteUser(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No users found</h2>
                </div>
            </div>

            <Dialog
                v-model:visible="userRolesDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit user' : 'Add New user'"
                :modal="true"
            >
                <div class="grid gap-4">
                    <!-- Title -->
                    <div class="col-12 md:col-6">
                        <label for="title" class="block font-bold mb-2"
                            >Title</label
                        >
                        <Select
                            id="title"
                            v-model="user.title"
                            :options="titleOptions"
                            placeholder="Select title"
                            fluid
                        />
                    </div>

                    <!-- Full Name -->
                    <div class="col-12 md:col-6">
                        <label for="name" class="block font-bold mb-2"
                            >Full Name</label
                        >
                        <InputText
                            id="name"
                            v-model.trim="user.name"
                            required
                            autofocus
                            :invalid="submitted && !user.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !user.name"
                            class="text-red-500"
                        >
                            Name is required.
                        </small>
                    </div>

                    <!-- Date of Birth -->
                    <div class="col-12 md:col-6">
                        <label for="dob" class="block font-bold mb-2"
                            >Date of Birth</label
                        >

                        <DatePicker
                            fluid
                            v-model="user.DOB"
                            dateFormat="dd/mm/yy"
                            placeholder="YYYY-MM-DD"
                        />
                    </div>

                    <!-- Gender -->
                    <div class="col-12 md:col-6">
                        <label for="gender" class="block font-bold mb-2"
                            >Gender</label
                        >
                        <Select
                            id="gender"
                            v-model="user.gender"
                            :options="genderOptions"
                            placeholder="Select gender"
                            fluid
                        />
                    </div>

                    <!-- Email -->
                    <div class="col-12 md:col-6">
                        <label for="email" class="block font-bold mb-2"
                            >Email</label
                        >
                        <InputText
                            id="email"
                            type="email"
                            v-model.trim="user.email"
                            required
                            :invalid="submitted && !user.email"
                            fluid
                        />
                        <small
                            v-if="submitted && !user.email"
                            class="text-red-500"
                        >
                            Email is required.
                        </small>
                    </div>

                    <!-- Phone Number -->
                    <div class="col-12 md:col-6">
                        <label for="phone" class="block font-bold mb-2"
                            >Phone Number</label
                        >
                        <InputText
                            id="phone"
                            type="tel"
                            v-model.trim="user.phone"
                            placeholder="Enter phone number"
                            fluid
                        />
                    </div>

                    <!-- Village -->
                    <div class="col-12 md:col-6">
                        <label for="village" class="block font-bold mb-2"
                            >Village</label
                        >
                        <InputText
                            id="village"
                            v-model.trim="user.village"
                            placeholder="Enter village name"
                            fluid
                        />
                    </div>

                    <!-- National ID -->
                    <div class="col-12 md:col-6">
                        <label for="nid" class="block font-bold mb-2"
                            >National ID</label
                        >
                        <InputText
                            id="nid"
                            v-model.trim="user.national_id"
                            placeholder="Enter National ID"
                            fluid
                        />
                    </div>

                    <!-- Traditional Authority -->
                    <div class="col-12 md:col-6">
                        <label
                            for="traditional_authority"
                            class="block font-bold mb-2"
                        >
                            Traditional Authority
                        </label>
                        <InputText
                            id="traditional_authority"
                            v-model.trim="user.traditional_authority"
                            placeholder="Enter Traditional Authority"
                            fluid
                        />
                    </div>

                    <!-- District -->
                    <div class="col-12 md:col-6">
                        <label for="district" class="block font-bold mb-2"
                            >District</label
                        >
                        <Select
                            id="id"
                            v-model="user.district_id"
                            :options="districts"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select district"
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
                            animationDuration=".5s"
                            aria-label="Custom ProgressSpinner"
                        />
                        <Button
                            v-else
                            label="Save"
                            icon="pi pi-check"
                            @click="saveUser"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single user Confirmation Dialog -->
            <Dialog
                v-model:visible="deletUserDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="user">
                        Are you sure you want to delete <b>{{ user.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deletUserDialog = false"
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
                            @click="deleteUser"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple users Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteUsersDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected users?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteUsersDialog = false"
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
                            @click="deleteSelectedUsers"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- User Role Assignment Dialog -->
            <Dialog
                v-model:visible="roleAssignmentDialog"
                :style="{ width: '450px' }"
                :header="user.name"
                :modal="true"
            >
                <!-- <div class="grid gap-4">
                 
                    <div class="col-12">
                        <label for="role" class="block font-bold mb-2"
                            >Select Role</label
                        >
                        <MultiSelect
                            id="role"
                            v-model="userRole"
                            :options="roles"
                            optionValue="id"
                            optionLabel="name"
                            placeholder="Select role"
                            fluid
                        />
                    </div>
                </div> -->

                <div class="col-12">
                    <label for="roles" class="block font-bold mb-2">
                        Select Role
                    </label>
                    <div class="card flex flex-wrap justify-start gap-4">
                        <div
                            v-for="(role, index) in roles"
                            :key="role.id"
                            class="flex items-center gap-2"
                        >
                            <Checkbox
                                v-model="userRole"
                                :inputId="'role' + index"
                                :value="role.id"
                            />
                            <label :for="'role' + index">{{
                                role.name
                            }}</label>
                        </div>
                    </div>
                </div>

                <template #footer>
                    <Button
                        label="Cancel"
                        icon="pi pi-times"
                        text
                        @click="roleAssignmentDialog = false"
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
                            @click="saveUserRole"
                            :disabled="!userRole"
                        />
                    </div>
                </template>
            </Dialog>

            <Dialog
                v-model:visible="permissionAssignmentDialog"
                :style="{ width: '450px' }"
                :header="user.name"
                :modal="true"
            >
                <!-- <div class="grid gap-4">
                    
                    <div class="col-12">
                        <label for="permission" class="block font-bold mb-2"
                            >Select Permission</label
                        >
                        <MultiSelect
                            id="permission"
                            v-model="userPermission"
                            :options="permissions"
                            optionValue="id"
                            optionLabel="name"
                            placeholder="Select permission"
                            fluid
                        />
                    </div>
                </div> -->

                <div class="col-12">
                    <label for="permissions" class="block font-bold mb-2">
                        Select Permission
                    </label>
                    <div class="card flex flex-wrap justify-start gap-4">
                        <div
                            v-for="(permission, index) in permissions"
                            :key="permission.id"
                            class="flex items-center gap-2"
                        >
                            <Checkbox
                                v-model="userPermission"
                                :inputId="'permission' + index"
                                :value="permission.id"
                            />
                            <label :for="'permission' + index">{{
                                permission.name
                            }}</label>
                        </div>
                    </div>
                </div>

                <template #footer>
                    <Button
                        label="Cancel"
                        icon="pi pi-times"
                        text
                        @click="permissionAssignmentDialog = false"
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
                            @click="saveUserPermission"
                            :disabled="!userPermission"
                        />
                    </div>
                </template>
            </Dialog>

            <Dialog
                v-model:visible="viewUserDialog"
                :style="{ width: '450px' }"
                :header="'User Details'"
                :modal="true"
            >
                <div class="grid gap-4">
                    <div
                        class="w-full h-48 overflow-hidden rounded-lg border shadow-md"
                    >
                        <img
                            v-if="user.profile_photo_url"
                            :src="user.profile_photo_url"
                            alt="user Photo"
                            class="object-cover w-full h-full"
                        />
                        <div v-else class="text-gray-500">
                            No Photo Available
                        </div>
                    </div>
                    <div class="col-span-1 flex flex-col space-y-3">
                        <!-- Title -->
                        <div class="col-12 md:col-6">
                            <label
                                for="title"
                                class="block font-semibold text-lg text-gray-800"
                                >Title</label
                            >
                            <div class="text-base text-gray-600">
                                {{ user.title || "N/A" }}
                            </div>
                        </div>
                        <Divider />

                        <!-- Full Name -->
                        <div class="col-12 md:col-6">
                            <label
                                for="name"
                                class="block font-semibold text-lg text-gray-800"
                                >Full Name</label
                            >
                            <div class="text-base text-gray-600">
                                {{ user.name || "N/A" }}
                            </div>
                        </div>
                        <Divider />

                        <!-- Date of Birth -->
                        <div class="col-12 md:col-6">
                            <label
                                for="dob"
                                class="block font-semibold text-lg text-gray-800"
                                >Date of Birth</label
                            >
                            <div class="text-base text-gray-600">
                                {{ user.DOB || "N/A" }}
                            </div>
                        </div>
                        <Divider />

                        <!-- Gender -->
                        <div class="col-12 md:col-6">
                            <label
                                for="gender"
                                class="block font-semibold text-lg text-gray-800"
                                >Gender</label
                            >
                            <div class="text-base text-gray-600">
                                {{ user.gender || "N/A" }}
                            </div>
                        </div>
                        <Divider />

                        <!-- Email -->
                        <div class="col-12 md:col-6">
                            <label
                                for="email"
                                class="block font-semibold text-lg text-gray-800"
                                >Email</label
                            >
                            <div class="text-base text-gray-600">
                                {{ user.email || "N/A" }}
                            </div>
                        </div>
                        <Divider />

                        <!-- Phone Number -->
                        <div class="col-12 md:col-6">
                            <label
                                for="phone"
                                class="block font-semibold text-lg text-gray-800"
                                >Phone Number</label
                            >
                            <div class="text-base text-gray-600">
                                {{ user.phone || "N/A" }}
                            </div>
                        </div>
                        <Divider />

                        <!-- Village -->
                        <div class="col-12 md:col-6">
                            <label
                                for="village"
                                class="block font-semibold text-lg text-gray-800"
                                >Village</label
                            >
                            <div class="text-base text-gray-600">
                                {{ user.village || "N/A" }}
                            </div>
                        </div>
                        <Divider />

                        <!-- National ID -->
                        <div class="col-12 md:col-6">
                            <label
                                for="nid"
                                class="block font-semibold text-lg text-gray-800"
                                >National ID</label
                            >
                            <div class="text-base text-gray-600">
                                {{ user.national_id || "N/A" }}
                            </div>
                        </div>
                        <Divider />

                        <!-- Traditional Authority -->
                        <div class="col-12 md:col-6">
                            <label
                                for="traditional_authority"
                                class="block font-semibold text-lg text-gray-800"
                                >Traditional Authority</label
                            >
                            <div class="text-base text-gray-600">
                                {{ user.traditional_authority || "N/A" }}
                            </div>
                        </div>
                        <Divider />

                        <!-- District -->
                        <div class="col-12 md:col-6">
                            <label
                                for="district"
                                class="block font-semibold text-lg text-gray-800"
                                >District</label
                            >
                            <div class="text-base text-gray-600">
                                {{ user.district?.name || "N/A" }}
                            </div>
                        </div>
                    </div>
                </div>

                <template #footer>
                    <Button
                        label="Close"
                        icon="pi pi-times"
                        text
                        @click="viewUserDialog = false"
                    />
                </template>
            </Dialog>
        </div>
    </AppLayout>
</template>
<script setup>
import { ref, onMounted, watch, reactive } from "vue";
import { FilterMatchMode } from "@primevue/core/api";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";

import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Checkbox from "primevue/checkbox";
import Toolbar from "primevue/toolbar";
import Dialog from "primevue/dialog";
import InputIcon from "primevue/inputicon";
import InputText from "primevue/inputtext";
import IconField from "primevue/iconfield";
import MultiSelect from "primevue/multiselect";
import Select from "primevue/select";
import ProgressSpinner from "primevue/progressspinner";
import { format } from "date-fns";
import DatePicker from "primevue/datepicker";
import Image from "primevue/image";
import axiosInstance from "@/axiosInstance";
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";
import Divider from "primevue/divider";
import Breadcrumb from "primevue/breadcrumb";
import { Link } from "@inertiajs/vue3";
const toast = useToast();

// Reactive State Variables
const dt = ref();
const userRolesDialog = ref(false);
const viewUserDialog = ref(false);
const roleAssignmentDialog = ref(false);
const permissionAssignmentDialog = ref(false);
const selectedRole = ref(null);
const editDialog = ref(false);
const loading = ref(false);
const deletUserDialog = ref(false);
const deleteUsersDialog = ref(false);
const user = ref({});
const userRole = ref([]);
const userPermission = ref([]);

const selectedRoles = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Users" }]);

const props = defineProps({
    users: Array,
    roles: Array,
    userRoles: Array,
    districts: Array,
    can: Object,
    permissions: Array,
    isAdmin: Boolean,
});

const roleOptions = ref([]);
const users = ref(props.users);
const roles = ref(props.roles);
const districts = ref(props.districts);
const userRoles = ref(props.userRoles);
const can = ref(props.can);
const permissions = ref(props.permissions);

// console.log(can.value.editUser);

const titleOptions = ["Mr.", "Mrs.", "Miss", "Dr.", "Prof."];
const genderOptions = ["Male", "Female", "Other"];
// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    user.value = {};
    submitted.value = false;
    userRolesDialog.value = true;
};

const hideDialog = () => {
    userRolesDialog.value = false;
    roleAssignmentDialog.value = false;
    submitted.value = false;
};

watch(
    () => user.value.DOB,
    (newVal) => {
        if (newVal && typeof newVal === "string" && newVal.includes("T")) {
            user.value.DOB = format(new Date(newVal), "dd/MM/yyyy");
        }
    },
    { immediate: true }
);

async function saveUserRole() {
    loading.value = true;
    try {
        const response = await axios.post("/user-role", {
            userId: user.value.id,
            roleIds: userRole.value,
        });

        if (response.status === 200) {
            const targetUser = users.value.find((u) => u.id === user.value.id);
            if (targetUser) {
                targetUser.roles = response.data.roles; // Update roles in the users array
            }

            userRole.value = response.data.roles.map((role) => role.id);

            toast.add({
                severity: "success",
                summary: "Success",
                detail: "Roles assigned successfully",
                life: 3000,
            });
        }
        roleAssignmentDialog.value = false;
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
        roleAssignmentDialog.value = false;
    }
}

async function saveUserPermission() {
    loading.value = true;
    try {
        const response = await axios.post("/user-permission", {
            userId: user.value.id,
            permissionIds: userPermission.value,
        });

        if (response.status === 200) {
            const targetUser = users.value.find((u) => u.id === user.value.id);
            if (targetUser) {
                targetUser.permissions = response.data.permissions;
            }

            userPermission.value = response.data.permissions.map(
                (role) => role.id
            );

            toast.add({
                severity: "success",
                summary: "Success",
                detail: "Permissions assigned successfully",
                life: 3000,
            });
        }
        permissionAssignmentDialog.value = false;
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
        permissionAssignmentDialog.value = false;
    }
}

const saveUser = async () => {
    submitted.value = true;
    if (user?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (user.value.id) {
                const response = await axios.put(
                    `/users/${user.value.id}`,
                    user.value
                );

                updateUser(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "User Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post("/users", user.value);

                users.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "User Created",
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
            userRolesDialog.value = false;
        }
    }
};

const editUser = (userData) => {
    editDialog.value = true;
    user.value = { ...userData };
    userRolesDialog.value = true;
};

const viewUser = (userData) => {
    editDialog.value = true;
    user.value = { ...userData };
    viewUserDialog.value = true;
};
const assignAndEditRoles = (userData) => {
    user.value = { ...userData };

    userRole.value = userData.roles.map((p) => p.id);

    roleAssignmentDialog.value = true;
};

const assignAndEditPermissions = (userData) => {
    user.value = { ...userData };

    userPermission.value = userData.permissions.map((p) => p.id);

    permissionAssignmentDialog.value = true;
};

const deleteUser = async () => {
    loading.value = true;
    try {
        await axios.delete(`/users/${user.value.id}`);
        // await axiosInstance.delete(`/users/${user.value.id}`);

        users.value = users.value.filter((r) => r.id !== user.value.id);
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "user Deleted",
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
        deletUserDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteUser = (userData) => {
    user.value = userData;
    deletUserDialog.value = true;
};

const deleteSelectedUsers = async () => {
    const ids = selectedRoles.value.map((user) => user.id);
    loading.value = true;
    try {
        await axios.post("/users/bulk-delete", { ids });
        users.value = users.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected users Deleted",
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
        deleteUsersDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteUsersDialog.value = true;
};

const updateUser = (updatedRole) => {
    const index = users.value.findIndex((r) => r.id === updatedRole.id);
    if (index !== -1) {
        users.value[index] = updatedRole;
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
