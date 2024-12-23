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
                            label="New Team Member"
                            icon="pi pi-plus"
                            class="mr-2"
                            @click="openNewTeamMember"
                        />
                        <Button
                            label="Delete"
                            icon="pi pi-trash"
                            severity="danger"
                            outlined
                            @click="confirmDeleteSelected"
                            :disabled="
                                !selectedTeamMember ||
                                !selectedTeamMember.length
                            "
                        />
                    </template>
                </Toolbar>

                <Toast />

                <DataTable
                    v-if="teamMembers.length > 0"
                    ref="dt"
                    v-model:selection="selectedTeamMember"
                    :value="teamMembers"
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
                            <h4 class="m-0">Manage Team Members</h4>
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
                        field="staff.user.name"
                        header="Member"
                        sortable
                        style="min-width: 12rem"
                    ></Column>

                    <Column
                        field="team.name"
                        header="Team"
                        sortable
                        style="min-width: 15rem"
                    ></Column>

                    <Column header="Team Leader" style="min-width: 10rem">
                        <template #body="slotProps">
                            <span
                                v-if="slotProps.data.is_team_lead"
                                class="text-green-500 font-bold"
                            >
                                Yes
                            </span>
                            <span v-else class="text-gray-500"> No </span>
                        </template>
                    </Column>
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
                                @click="confirmDeleteTeamMember(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>

                <div v-else class="flex items-center justify-center">
                    <h2>No team members found</h2>
                </div>
            </div>

            <Dialog
                v-model:visible="teamMemberDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Team Member' : 'Add Team Member'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <!-- Team Selection -->
                    <div>
                        <label for="team" class="block font-bold mb-3"
                            >Team Name</label
                        >
                        <Select
                            id="team"
                            v-model="staff.team_id"
                            :options="teams"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Team"
                            fluid
                            filter
                        />
                        <small
                            v-if="submitted && !staff.team_id"
                            class="text-red-500"
                        >
                            Team is required.
                        </small>
                    </div>

                    <!-- Staff Member Selection -->
                    <div>
                        <label for="member" class="block font-bold mb-3"
                            >Staff Member</label
                        >
                        <Select
                            id="member"
                            v-model="staff.staff_id"
                            :options="staffList"
                            optionLabel="user.name"
                            optionValue="id"
                            placeholder="Select Staff Member"
                            fluid
                            filter
                        />
                        <small
                            v-if="submitted && !staff.staff_id"
                            class="text-red-500"
                        >
                            Member is required.
                        </small>
                    </div>

                    <!-- Is Team Lead -->
                    <div class="flex items-center gap-4">
                        <label for="is_team_lead" class="font-bold"
                            >Team Lead</label
                        >
                        <ToggleSwitch v-model="staff.is_team_lead" />
                    </div>
                </div>

                <template #footer>
                    <Button
                        label="Cancel"
                        icon="pi pi-times"
                        text
                        @click="teamMemberDialog = false"
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
                            @click="saveTeamMember"
                        />
                    </div>
                </template>
            </Dialog>

            <Dialog
                v-model:visible="deleteteamMemberDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="staff.user.name">
                        Are you sure you want to remove
                        <b>{{ staff.user.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteteamMemberDialog = false"
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
                            @click="deleteTeamMember"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple suspects Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteTeamMembersDialog"
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
                        @click="deleteTeamMembersDialog = false"
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
                            @click="deleteSelectedTeamMembers"
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
import Image from "primevue/image";

import ToggleSwitch from "primevue/toggleswitch";

const toast = useToast();
const teamMemberDialog = ref(false);
const editDialog = ref(false);
const deleteteamMemberDialog = ref(false);
const deleteTeamMembersDialog = ref(false);
const loading = ref(false);
const submitted = ref(false);
const staff = ref({});
const selectedTeamMember = ref([]);
const filters = ref({
    global: { value: null, matchMode: "contains" },
});
const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});
const breadcrumbItems = ref([{ label: "Team Members" }]);

const props = defineProps({
    teamMembers: Array,
    staffList: Array,
    teams: Array,
});

const teamMembers = ref(props.teamMembers);
const staffList = ref(props.staffList);
const teams = ref(props.teams);

const selectedLevel = computed(() => {
    return levels.value.find((level) => level.id === staff.value.level_id);
});

const openNewTeamMember = () => {
    editDialog.value = false;
    staff.value = {};
    submitted.value = false;
    teamMemberDialog.value = true;
};

const hideDialog = () => {
    teamMemberDialog.value = false;
    submitted.value = false;
};

const saveTeamMember = async () => {
    submitted.value = true;

    // Validate required fields
    if (staff.value?.team_id && staff.value?.staff_id) {
        loading.value = true;

        try {
            const teamMemberPayload = new FormData();
            teamMemberPayload.append("team_id", staff.value.team_id);
            teamMemberPayload.append("staff_id", staff.value.staff_id);
            teamMemberPayload.append(
                "is_team_lead",
                staff.value.is_team_lead ? 1 : 0
            );

            if (staff.value.id) {
                teamMemberPayload.append("id", staff.value.id);
                const response = await axios.post(
                    `/update-team-member`,
                    teamMemberPayload,
                    {
                        headers: { "Content-Type": "multipart/form-data" },
                    }
                );
                updateTeamMember(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Team member updated.",
                    life: 3000,
                });
            } else {
                const response = await axios.post(
                    `/team-members`,
                    teamMemberPayload,
                    {
                        headers: { "Content-Type": "multipart/form-data" },
                    }
                );
                teamMembers.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Team member created.",
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
            teamMemberDialog.value = false;
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
    staff.value = {
        ...staffData,
        is_team_lead: !!staffData.is_team_lead, // Convert 1/0 to true/false
    };

    teamMemberDialog.value = true;
};

const deleteTeamMember = async () => {
    loading.value = true;
    try {
        await axios.delete(`/team-members/${staff.value.id}`);
        teamMembers.value = teamMembers.value.filter(
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
        deleteteamMemberDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteTeamMember = (roleData) => {
    staff.value = roleData;
    deleteteamMemberDialog.value = true;
};

const deleteSelectedTeamMembers = async () => {
    const ids = selectedTeamMember.value.map((staff) => staff.id);
    loading.value = true;
    try {
        await axios.post("/team-members/bulk-delete", { ids });
        teamMembers.value = teamMembers.value.filter(
            (r) => !ids.includes(r.id)
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected staffsteamMembers Deleted",
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
        deleteTeamMembersDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteTeamMembersDialog.value = true;
};

const updateTeamMember = (updatedstaff) => {
    const index = teamMembers.value.findIndex((r) => r.id === updatedstaff.id);
    if (index !== -1) {
        teamMembers.value[index] = updatedstaff;
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
::v-deep(.p-datatable-tbody > tr:hover) {
    background-color: #f0f0f0 !important;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
</style>
