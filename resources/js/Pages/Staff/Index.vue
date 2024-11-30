<template>
    <AppLayout title="Staff">
        <div>
            <div class="-mt-6 inline-block bg-transparent">
                <Breadcrumb :home="home" :model="breadcrumbItems">
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

            <div class="card">
                <Toolbar class="mb-6">
                    <template #start>
                        <Button
                            label="New Staff"
                            icon="pi pi-plus"
                            class="mr-2"
                            @click="openNewStaff"
                        />
                        <Button
                            label="Delete"
                            icon="pi pi-trash"
                            severity="danger"
                            outlined
                            @click="confirmDeleteSelected"
                            :disabled="!selectedStaff || !selectedStaff.length"
                        />
                    </template>
                </Toolbar>

                <Toast />

                <DataTable
                    v-if="staffList.length > 0"
                    ref="dt"
                    v-model:selection="selectedStaff"
                    :value="staffList"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} staff"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Staff</h4>
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
                        field="name"
                        header="Staff Name"
                        sortable
                        style="min-width: 12rem"
                    ></Column>
                    <Column
                        field="role.name"
                        header="Role"
                        sortable
                        style="min-width: 12rem"
                    ></Column>
                    <Column
                        field="email"
                        header="Email"
                        sortable
                        style="min-width: 15rem"
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
                                @click="editStaff(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteStaff(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No staff members found</h2>
                </div>
            </div>

            <!-- component -->
            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4"
            >
                <div
                    class="flex flex-col items-center justify-center bg-white p-4 shadow rounded-lg"
                >
                    <div
                        class="inline-flex shadow-lg border border-gray-200 rounded-full overflow-hidden h-40 w-40"
                    >
                        <img
                            src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&w=128&h=128&q=60&facepad=2"
                            alt=""
                            class="h-full w-full"
                        />
                    </div>

                    <h2 class="mt-4 font-bold text-xl">Sebastian Bennett</h2>
                    <h6 class="mt-2 text-sm font-medium">Founder</h6>

                    <p class="text-xs text-gray-500 text-center mt-3">
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit. Ab enim molestiae nulla.
                    </p>

                    <ul class="flex flex-row mt-4 space-x-2">
                        <li>
                            <a
                                href=""
                                class="flex items-center justify-center h-8 w-8 border rounded-full text-gray-800 border-gray-800"
                            >
                                <i class="fab fa-facebook"></i>
                            </a>
                        </li>
                        <li>
                            <a
                                href=""
                                class="flex items-center justify-center h-8 w-8 border rounded-full text-gray-800 border-gray-800"
                            >
                                <i class="fab fa-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a
                                href=""
                                class="flex items-center justify-center h-8 w-8 border rounded-full text-gray-800 border-gray-800"
                            >
                                <i class="fab fa-instagram"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <div
                    class="flex flex-col items-center justify-center bg-white p-4 shadow rounded-lg"
                >
                    <div
                        class="inline-flex shadow-lg border border-gray-200 rounded-full overflow-hidden h-40 w-40"
                    >
                        <img
                            src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&w=128&h=128&q=60&facepad=2"
                            alt=""
                            class="h-full w-full"
                        />
                    </div>

                    <h2 class="mt-4 font-bold text-xl">Sebastian Bennett</h2>
                    <h6 class="mt-2 text-sm font-medium">Founder</h6>

                    <p class="text-xs text-gray-500 text-center mt-3">
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit. Ab enim molestiae nulla.
                    </p>

                    <ul class="flex flex-row mt-4 space-x-2">
                        <li>
                            <a
                                href=""
                                class="flex items-center justify-center h-8 w-8 border rounded-full text-gray-800 border-gray-800"
                            >
                                <i class="fab fa-facebook"></i>
                            </a>
                        </li>
                        <li>
                            <a
                                href=""
                                class="flex items-center justify-center h-8 w-8 border rounded-full text-gray-800 border-gray-800"
                            >
                                <i class="fab fa-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a
                                href=""
                                class="flex items-center justify-center h-8 w-8 border rounded-full text-gray-800 border-gray-800"
                            >
                                <i class="fab fa-instagram"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <div
                    class="flex flex-col items-center justify-center bg-white p-4 shadow rounded-lg"
                >
                    <div
                        class="inline-flex shadow-lg border border-gray-200 rounded-full overflow-hidden h-40 w-40"
                    >
                        <img
                            src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&w=128&h=128&q=60&facepad=2"
                            alt=""
                            class="h-full w-full"
                        />
                    </div>

                    <h2 class="mt-4 font-bold text-xl">Sebastian Bennett</h2>
                    <h6 class="mt-2 text-sm font-medium">Founder</h6>

                    <p class="text-xs text-gray-500 text-center mt-3">
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit. Ab enim molestiae nulla.
                    </p>

                    <ul class="flex flex-row mt-4 space-x-2">
                        <li>
                            <a
                                href=""
                                class="flex items-center justify-center h-8 w-8 border rounded-full text-gray-800 border-gray-800"
                            >
                                <i class="fab fa-facebook"></i>
                            </a>
                        </li>
                        <li>
                            <a
                                href=""
                                class="flex items-center justify-center h-8 w-8 border rounded-full text-gray-800 border-gray-800"
                            >
                                <i class="fab fa-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a
                                href=""
                                class="flex items-center justify-center h-8 w-8 border rounded-full text-gray-800 border-gray-800"
                            >
                                <i class="fab fa-instagram"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <div
                    class="flex flex-col items-center justify-center bg-white p-4 shadow rounded-lg"
                >
                    <div
                        class="inline-flex shadow-lg border border-gray-200 rounded-full overflow-hidden h-40 w-40"
                    >
                        <img
                            src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&w=128&h=128&q=60&facepad=2"
                            alt=""
                            class="h-full w-full"
                        />
                    </div>

                    <h2 class="mt-4 font-bold text-xl">Sebastian Bennett</h2>
                    <h6 class="mt-2 text-sm font-medium">Founder</h6>

                    <p class="text-xs text-gray-500 text-center mt-3">
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit. Ab enim molestiae nulla.
                    </p>

                    <ul class="flex flex-row mt-4 space-x-2">
                        <li>
                            <a
                                href=""
                                class="flex items-center justify-center h-8 w-8 border rounded-full text-gray-800 border-gray-800"
                            >
                                <i class="fab fa-facebook"></i>
                            </a>
                        </li>
                        <li>
                            <a
                                href=""
                                class="flex items-center justify-center h-8 w-8 border rounded-full text-gray-800 border-gray-800"
                            >
                                <i class="fab fa-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a
                                href=""
                                class="flex items-center justify-center h-8 w-8 border rounded-full text-gray-800 border-gray-800"
                            >
                                <i class="fab fa-instagram"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <Dialog
                v-model:visible="staffDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Staff' : 'Add New Staff'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <!-- Level Selection -->
                    <div>
                        <label for="level" class="block font-bold mb-3"
                            >Level</label
                        >
                        <Select
                            id="level"
                            v-model="staff.level_id"
                            :options="levels"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Level"
                            fluid
                            filter
                        />
                        <small
                            v-if="submitted && !staff.level_id"
                            class="text-red-500"
                        >
                            Level is required.
                        </small>
                    </div>

                    <!-- Member Selection -->
                    <div>
                        <label for="member" class="block font-bold mb-3"
                            >Member</label
                        >
                        <Select
                            id="member"
                            v-model="staff.user_id"
                            :options="users"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Member"
                            fluid
                            filter
                        />
                        <small
                            v-if="submitted && !staff.user_id"
                            class="text-red-500"
                        >
                            Member is required.
                        </small>
                    </div>

                    <!-- Station Selection (conditionally rendered) -->
                    <div
                        v-if="
                            selectedLevel && selectedLevel.name === 'District'
                        "
                    >
                        <label for="station" class="block font-bold mb-3"
                            >Station</label
                        >
                        <Select
                            id="station"
                            v-model="staff.station_id"
                            :options="stations"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Station"
                            fluid
                            filter
                        />
                        <small
                            v-if="submitted && !staff.station_id"
                            class="text-red-500"
                        >
                            Station is required.
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
                        />
                        <Button
                            v-else
                            label="Save"
                            icon="pi pi-check"
                            @click="saveStaff"
                        />
                    </div>
                </template>
            </Dialog>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import Checkbox from "primevue/checkbox";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Toolbar from "primevue/toolbar";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import Select from "primevue/select";
import ProgressSpinner from "primevue/progressspinner";
import Breadcrumb from "primevue/breadcrumb";
import axios from "axios";
import { Link, router } from "@inertiajs/vue3";

const toast = useToast();
const staffDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const submitted = ref(false);
const staff = ref({});
const selectedStaff = ref([]);
const filters = ref({
    global: { value: null, matchMode: "contains" },
});
const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});
const breadcrumbItems = ref([{ label: "Staff" }]);

const props = defineProps({
    staffList: Array,
    levels: Array,
    stations: Array,
    users: Array,
});

const staffList = ref(props.staffList);
const levels = ref(props.levels);
const stations = ref(props.stations);
const users = ref(props.users);

const selectedLevel = computed(() => {
    return levels.value.find((level) => level.id === staff.value.level_id);
});

const openNewStaff = () => {
    editDialog.value = false;
    staff.value = {};
    submitted.value = false;
    staffDialog.value = true;
};

const hideDialog = () => {
    staffDialog.value = false;
    submitted.value = false;
};

const saveStaff = async () => {
    submitted.value = true;

    // Validate required fields
    if (staff?.value?.level_id?.trim() && staff?.value?.user_id?.trim()) {
        loading.value = true;

        try {
            // Prepare staff payload
            const staffPayload = new FormData();
            staffPayload.append("level_id", staff.value.level_id);
            staffPayload.append("user_id", staff.value.user_id);
            staffPayload.append("station_id", staff.value.station_id);

            // If staff already exists, we don't need to re-append staff_photo_path unless it's new
            if (staff.value.id) {
                staffPayload.append("id", staff.value.id);

                const response = await axios.post(
                    `/update-staff`,
                    staffPayload,
                    { headers: { "Content-Type": "multipart/form-data" } }
                );

                updatestaff(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "staff Updated",
                    life: 3000,
                });
            } else {
                // Create a new staff
                const response = await axios.post("/staff", staffPayload, {
                    headers: { "Content-Type": "multipart/form-data" },
                });
                staff.value.push(response.data); // Add the new staff to the list
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "staff Created",
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
            staffDialog.value = false;
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

const viewstaff = (staffData) => {
    staff.value = { ...staffData };
    if (Array.isArray(staffData.district)) {
        staff.value.districts = staffData.district.map((p) => p.id); // Assuming districts are objects with 'id' and 'name'
    } else if (staffData.district && typeof staffData.district === "object") {
        // Handle the case where staffData.district is a single object
        staff.value.districts = [staffData.district.id];
    } else {
        // Handle the case where staffData.district is null or undefined
        staff.value.districts = [];
    }
    viewDialog.value = true;
};

const editStaff = (staffData) => {
    editDialog.value = true;
    staff.value = { ...staffData };

    // Check if staffData.district is an array before mapping
    if (Array.isArray(staffData.district)) {
        staff.value.districts = staffData.district.map((p) => p.id); // Assuming districts are objects with 'id' and 'name'
    } else if (staffData.district && typeof staffData.district === "object") {
        // Handle the case where staffData.district is a single object
        staff.value.districts = [staffData.district.id];
    } else {
        // Handle the case where staffData.district is null or undefined
        staff.value.districts = [];
    }

    staffDialog.value = true;
};

const deleteStaff = async () => {
    loading.value = true;
    try {
        await axios.delete(`/staffsstaffList/${staff.value.id}`);
        staffsstaffList.value = staffsstaffList.value.filter(
            (r) => r.id !== staff.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "staff Deleted",
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
        deletestaffDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteStaff = (roleData) => {
    staff.value = roleData;
    deletestaffDialog.value = true;
};

const deleteSelectedstaffsstaffList = async () => {
    const ids = selectedRoles.value.map((staff) => staff.id);
    loading.value = true;
    try {
        await axios.post("/staffsstaffList/bulk-delete", { ids });
        staffsstaffList.value = staffsstaffList.value.filter(
            (r) => !ids.includes(r.id)
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected staffsstaffList Deleted",
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

const updatestaff = (updatedstaff) => {
    const index = staffsstaffList.value.findIndex(
        (r) => r.id === updatedstaff.id
    );
    if (index !== -1) {
        staffsstaffList.value[index] = updatedstaff;
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
