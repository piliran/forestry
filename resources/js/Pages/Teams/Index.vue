<template>
    <AppLayout title="Teams">
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
                            :disabled="!selectedTeams || !selectedTeams.length"
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
                    v-if="teams.length > 0"
                    ref="dt"
                    v-model:selection="selectedTeams"
                    :value="teams"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Teams"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Teams</h4>
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
                        header="Team Name"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="description"
                        header="Description"
                        sortable
                        style="min-width: 10rem"
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
                                @click="editTeam(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteTeam(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Teams Found</h2>
                </div>
            </div>

            <!-- Add/Edit Team Dialog -->
            <Dialog
                v-model:visible="teamDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Team' : 'Add New Team'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3">
                            Team Name
                        </label>
                        <InputText
                            id="name"
                            v-model.trim="team.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !team.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !team.name"
                            class="text-red-500"
                        >
                            Team Name is required.
                        </small>
                    </div>

                    <div class="col-12">
                        <label for="description" class="block font-bold mb-3"
                            >Description</label
                        >
                        <Textarea
                            id="description"
                            v-model="team.description"
                            required="true"
                            rows="3"
                            cols="20"
                            autofocus
                            :invalid="submitted && !team.description"
                            fluid
                        />
                        <small
                            v-if="submitted && !team.description"
                            class="text-red-500"
                        >
                            Team Description is required.
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
                            @click="saveTeam"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Single Team Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteTeamDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="team">
                        Are you sure you want to delete
                        <b>{{ team.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteTeamDialog = false"
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
                            @click="deleteTeam"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple Team Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteTeamsDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span>
                        Are you sure you want to delete the selected Team?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteTeamsDialog = false"
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
                            @click="deleteSelectedTeams"
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
import InputText from "primevue/inputtext";
import IconField from "primevue/iconfield";
import Select from "primevue/select";
import Textarea from "primevue/textarea";
import ProgressSpinner from "primevue/progressspinner";
import Breadcrumb from "primevue/breadcrumb";
import { Link } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const toast = useToast();

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Teams" }]);

// Reactive State Variables
const dt = ref();
const teamDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteTeamDialog = ref(false);
const deleteTeamsDialog = ref(false);
const team = ref({});
const selectedTeams = ref([]);
const submitted = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const props = defineProps({
    teams: Array,
});

const teams = ref(props.teams);

// CRUD Methods
const openNew = () => {
    editDialog.value = false;
    team.value = {};
    submitted.value = false;
    teamDialog.value = true;
};

const hideDialog = () => {
    teamDialog.value = false;
    submitted.value = false;
};

const saveTeam = async () => {
    submitted.value = true;
    if (team?.value?.name?.trim()) {
        loading.value = true;
        try {
            if (team.value.id) {
                const response = await axios.put(
                    `/teams/${team.value.id}`,
                    team.value
                );
                updateTeams(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Team Updated",
                    life: 3000,
                });
            } else {
                const response = await axios.post("/teams", team.value);
                teams.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Team Created",
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
            teamDialog.value = false;
        }
    }
};

const editTeam = (teamData) => {
    editDialog.value = true;
    team.value = { ...teamData };
    teamDialog.value = true;
};

const deleteTeam = async () => {
    loading.value = true;
    try {
        await axios.delete(`/teams/${team.value.id}`);
        teams.value = teams.value.filter((r) => r.id !== team.value.id);
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Team Deleted",
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
        deleteTeamDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteTeam = (teamData) => {
    team.value = teamData;
    deleteTeamDialog.value = true;
};

const deleteSelectedTeams = async () => {
    const ids = selectedTeams.value.map((team) => team.id);
    loading.value = true;
    try {
        await axios.post("/teams/bulk-delete", { ids });
        teams.value = teams.value.filter((r) => !ids.includes(r.id));
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Teams Deleted",
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
        deleteTeamsDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteTeamsDialog.value = true;
};

const updateTeam = (updatedTeam) => {
    const index = teams.value.findIndex((r) => r.id === updatedTeam.id);
    if (index !== -1) {
        teams.value[index] = updatedTeam;
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
::v-deep(.p-datatable-tbody > tr:hover) {
    background-color: #f0f0f0 !important;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
</style>
