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
                            label="New Team operation"
                            icon="pi pi-plus"
                            class="mr-2"
                            @click="openNewTeamoperation"
                        />
                        <Button
                            label="Delete"
                            icon="pi pi-trash"
                            severity="danger"
                            outlined
                            @click="confirmDeleteSelected"
                            :disabled="
                                !selectedTeamoperation ||
                                !selectedTeamoperation.length
                            "
                        />
                    </template>
                </Toolbar>

                <Toast />

                <DataTable
                    v-if="operationToTeams.length > 0"
                    ref="dt"
                    v-model:selection="selectedTeamoperation"
                    :value="operationToTeams"
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
                            <h4 class="m-0">Manage Team Operations</h4>
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
                        field="operation.name"
                        header="Operation"
                        sortable
                        style="min-width: 12rem"
                    ></Column>

                    <Column
                        field="team.name"
                        header="Team"
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
                                @click="editTeamOperation(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="
                                    confirmDeleteTeamOperation(slotProps.data)
                                "
                            />
                        </template>
                    </Column>
                </DataTable>

                <div v-else class="flex items-center justify-center">
                    <h2>No team operations found</h2>
                </div>
            </div>

            <Dialog
                v-model:visible="teamOperationDialog"
                :style="{ width: '450px' }"
                :header="
                    editDialog ? 'Edit Team Operation' : 'Add Team Operation'
                "
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

                    <!-- Staff operation Selection -->
                    <div>
                        <label for="operation" class="block font-bold mb-3"
                            >Operation</label
                        >
                        <Select
                            id="operation"
                            v-model="staff.operation_id"
                            :options="operations"
                            optionLabel="operation.name"
                            optionValue="id"
                            placeholder="Select operation"
                            fluid
                            filter
                        />
                        <small
                            v-if="submitted && !staff.operation_id"
                            class="text-red-500"
                        >
                            Operation is required.
                        </small>
                    </div>
                </div>

                <template #footer>
                    <Button
                        label="Cancel"
                        icon="pi pi-times"
                        text
                        @click="teamOperationDialog = false"
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
                            @click="saveTeamoperation"
                        />
                    </div>
                </template>
            </Dialog>

            <Dialog
                v-model:visible="deleteteamOperationDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="staff.user.name">
                        Are you sure you want to remove
                        <b>{{ staff.operation.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteteamOperationDialog = false"
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
                            @click="deleteTeamoperation"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple suspects Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteoperationToTeamsDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected team
                        operations?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteoperationToTeamsDialog = false"
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
                            @click="deleteSelectedoperationToTeams"
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
const teamOperationDialog = ref(false);
const editDialog = ref(false);
const deleteteamOperationDialog = ref(false);
const deleteoperationToTeamsDialog = ref(false);
const loading = ref(false);
const submitted = ref(false);
const staff = ref({});
const selectedTeamoperation = ref([]);
const filters = ref({
    global: { value: null, matchMode: "contains" },
});
const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});
const breadcrumbItems = ref([{ label: "Team Operations" }]);

const props = defineProps({
    operationToTeams: Array,
    operations: Array,
    teams: Array,
});

const operationToTeams = ref(props.operationToTeams);
const operations = ref(props.operations);
const teams = ref(props.teams);

const selectedLevel = computed(() => {
    return levels.value.find((level) => level.id === staff.value.level_id);
});

const openNewTeamoperation = () => {
    editDialog.value = false;
    staff.value = {};
    submitted.value = false;
    teamOperationDialog.value = true;
};

const hideDialog = () => {
    teamOperationDialog.value = false;
    submitted.value = false;
};

const saveTeamoperation = async () => {
    submitted.value = true;

    // Validate required fields
    if (staff.value?.team_id && staff.value?.staff_id) {
        loading.value = true;

        try {
            const teamoperationPayload = new FormData();
            teamoperationPayload.append("team_id", staff.value.team_id);
            teamoperationPayload.append("staff_id", staff.value.staff_id);
            teamoperationPayload.append(
                "is_team_lead",
                staff.value.is_team_lead ? 1 : 0
            );

            if (staff.value.id) {
                teamoperationPayload.append("id", staff.value.id);
                const response = await axios.post(
                    `/update-team-operation`,
                    teamoperationPayload,
                    {
                        headers: { "Content-Type": "multipart/form-data" },
                    }
                );
                updateTeamoperation(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Team operation updated.",
                    life: 3000,
                });
            } else {
                const response = await axios.post(
                    `/team-operations`,
                    teamoperationPayload,
                    {
                        headers: { "Content-Type": "multipart/form-data" },
                    }
                );
                operationToTeams.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Team operation created.",
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
            teamOperationDialog.value = false;
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

const editTeamOperation = (staffData) => {
    editDialog.value = true;
    staff.value = {
        ...staffData,
        is_team_lead: !!staffData.is_team_lead, // Convert 1/0 to true/false
    };

    teamOperationDialog.value = true;
};

const deleteTeamoperation = async () => {
    loading.value = true;
    try {
        await axios.delete(`/team-operations/${staff.value.id}`);
        operationToTeams.value = operationToTeams.value.filter(
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
        deleteteamOperationDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteTeamOperation = (roleData) => {
    staff.value = roleData;
    deleteteamOperationDialog.value = true;
};

const deleteSelectedoperationToTeams = async () => {
    const ids = selectedTeamoperation.value.map((staff) => staff.id);
    loading.value = true;
    try {
        await axios.post("/team-operations/bulk-delete", { ids });
        operationToTeams.value = operationToTeams.value.filter(
            (r) => !ids.includes(r.id)
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected staffsoperationToTeams Deleted",
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
        deleteoperationToTeamsDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteoperationToTeamsDialog.value = true;
};

const updateTeamoperation = (updatedstaff) => {
    const index = operationToTeams.value.findIndex(
        (r) => r.id === updatedstaff.id
    );
    if (index !== -1) {
        operationToTeams.value[index] = updatedstaff;
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
