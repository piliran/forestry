<template>
    <AppLayout title="Team Operations">
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
                            @click="openNewOperationToTeam"
                        />
                        <Button
                            label="Delete"
                            icon="pi pi-trash"
                            severity="danger"
                            outlined
                            @click="confirmDeleteSelected"
                            :disabled="
                                !selectedOperationToTeam ||
                                !selectedOperationToTeam.length
                            "
                        />
                    </template>
                </Toolbar>

                <Toast />

                <DataTable
                    v-if="operationToTeams.length > 0"
                    ref="dt"
                    v-model:selection="selectedOperationToTeam"
                    :value="operationToTeams"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Team Operations"
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
                    <Column header="#" headerStyle="width:3rem">
                        <template #body="slotProps">
                            {{ slotProps.index + 1 }}
                        </template>
                    </Column>
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
                                @click="editOperationToTeam(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="
                                    confirmDeleteOperationToTeam(slotProps.data)
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
                v-model:visible="operationToTeamDialog"
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
                            v-model="operationToTeam.team_id"
                            :options="teams"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select Team"
                            fluid
                            filter
                        />
                        <small
                            v-if="submitted && !operationToTeam.team_id"
                            class="text-red-500"
                        >
                            Team is required.
                        </small>
                    </div>

                    <!-- team operation Selection -->
                    <div>
                        <label for="operation" class="block font-bold mb-3"
                            >Operation</label
                        >
                        <Select
                            id="operation"
                            v-model="operationToTeam.operation_id"
                            :options="operations"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select operation"
                            fluid
                            filter
                        />
                        <small
                            v-if="submitted && !operationToTeam.operation_id"
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
                        @click="operationToTeamDialog = false"
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
                            @click="saveOperationToTeam"
                        />
                    </div>
                </template>
            </Dialog>

            <Dialog
                v-model:visible="deleteOperationToTeamDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="operationToTeam.team.name">
                        Are you sure you want to remove
                        <b>{{ operationToTeam.operation.name }}</b
                        >?
                    </span>
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteOperationToTeamDialog = false"
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
                            @click="deleteOperationToTeam"
                        />
                    </div>
                </template>
            </Dialog>

            <!-- Delete Multiple suspects Confirmation Dialog -->
            <Dialog
                v-model:visible="deleteOperationToTeamsDialog"
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
                        @click="deleteOperationToTeamsDialog = false"
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
                            @click="deleteSelectedOperationToTeams"
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
const operationToTeamDialog = ref(false);
const editDialog = ref(false);
const deleteOperationToTeamDialog = ref(false);
const deleteOperationToTeamsDialog = ref(false);
const loading = ref(false);
const submitted = ref(false);
const operationToTeam = ref({});
const selectedOperationToTeam = ref([]);
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

// console.log(operationToTeams);

const openNewOperationToTeam = () => {
    editDialog.value = false;
    operationToTeam.value = {};
    submitted.value = false;
    operationToTeamDialog.value = true;
};

const hideDialog = () => {
    OperationToTeamDialog.value = false;
    submitted.value = false;
};

const saveOperationToTeam = async () => {
    submitted.value = true;

    // Validate required fields
    if (operationToTeam.value?.team_id && operationToTeam.value?.operation_id) {
        loading.value = true;

        try {
            const operationToTeamPayload = new FormData();
            operationToTeamPayload.append(
                "team_id",
                operationToTeam.value.team_id
            );
            operationToTeamPayload.append(
                "operation_id",
                operationToTeam.value.operation_id
            );

            if (operationToTeam.value.id) {
                operationToTeamPayload.append("id", operationToTeam.value.id);
                const response = await axios.post(
                    `/update-team-operation`,
                    operationToTeamPayload,
                    {
                        headers: { "Content-Type": "multipart/form-data" },
                    }
                );
                updateOperationToTeam(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Team operation updated.",
                    life: 3000,
                });
            } else {
                const response = await axios.post(
                    `/team-operations`,
                    operationToTeamPayload,
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
            operationToTeamDialog.value = false;
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

const editOperationToTeam = (operationToTeamData) => {
    editDialog.value = true;
    operationToTeam.value = { ...operationToTeamData };

    operationToTeamDialog.value = true;
};

const deleteOperationToTeam = async () => {
    loading.value = true;
    try {
        await axios.delete(`/team-operations/${operationToTeam.value.id}`);
        operationToTeams.value = operationToTeams.value.filter(
            (r) => r.id !== operationToTeam.value.id
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Team Operation Deleted",
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
        deleteOperationToTeamDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteOperationToTeam = (operationToTeamData) => {
    operationToTeam.value = operationToTeamData;
    deleteOperationToTeamDialog.value = true;
};

const deleteSelectedOperationToTeams = async () => {
    const ids = selectedOperationToTeam.value.map(
        (operationToTeam) => operationToTeam.id
    );
    loading.value = true;
    try {
        await axios.post("/team-operations/bulk-delete", { ids });
        operationToTeams.value = operationToTeams.value.filter(
            (r) => !ids.includes(r.id)
        );
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Team Operations Deleted",
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
        deleteOperationToTeamsDialog.value = false;
        loading.value = false;
    }
};

const confirmDeleteSelected = () => {
    deleteOperationToTeamsDialog.value = true;
};

const updateOperationToTeam = (updatedOperationToTeam) => {
    const index = operationToTeams.value.findIndex(
        (r) => r.id === updatedOperationToTeam.id
    );
    if (index !== -1) {
        operationToTeams.value[index] = updatedOperationToTeam;
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
