<template>
    <AppLayout title="Species Types">
        <div>
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
                                !selectedTypes ||
                                !selectedTypes.length
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

                <Toast />

                <DataTable
                    v-if="types.length > 0"
                    ref="dt"
                    v-model:selection="selectedTypes"
                    :value="types"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Specie Type"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Species Types</h4>
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
                        header="Specie Type Name"
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
                                @click="editType(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteType(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No Species Types Found</h2>
                </div>
            </div>

            <Dialog
                v-model:visible="typeDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Type' : 'Add New Type'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3"
                            >Specie Type Name</label
                        >
                        <InputText
                            id="name"
                            v-model.trim="type.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !type.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !type.name"
                            class="text-red-500"
                            >Species Type Name is required.</small
                        >
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
                            @click="saveType"
                        />
                    </div>
                </template>
            </Dialog>

            <Dialog
                v-model:visible="deleteTypeDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="type"
                        >Are you sure you want to delete
                        <b>{{ type.name }}</b
                        >?</span
                    >
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteTypeDialog = false"
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
                            @click="deleteType"
                        />
                    </div>
                </template>
            </Dialog>

            <Dialog
                v-model:visible="deleteTypesDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="type"
                        >Are you sure you want to delete the selected
                        types?</span
                    >
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteTypesDialog = false"
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
                            @click="deleteSelectedTypes"
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
import ProgressSpinner from "primevue/progressspinner";

import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const toast = useToast();
const dt = ref();
// const categories = ref([]);
const typeDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteTypeDialog = ref(false);
const deleteTypesDialog = ref(false);
const types = ref({});
const selectedTypes = ref();
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const submitted = ref(false);

const props = defineProps({
    specieTypes: Array,
});

const categories = ref(props.specieTypes);

// onMounted(async () => {
//     try {
//         const response = await axios.get("/role-categories");
//         categories.value = response.data.roleCategories;
//     } catch (error) {
//         toast.add({
//             severity: "error",
//             summary: "Error",
//             detail: "Failed to load role categories.",
//             life: 3000,
//         });
//     }
// });

const openNew = () => {
    editDialog.value = false;

    type.value = {};
    submitted.value = false;
    typeDialog.value = true;
};
const hideDialog = () => {
    typeDialog.value = false;
    submitted.value = false;
};
const saveType = async () => {
    submitted.value = true;

    if (type?.value.name?.trim()) {
        loading.value = true;

        try {
            if (type.value.id) {
                // Update types
                const response = await axios.put(
                    `/specie-types/${type.value.id}`,
                    type.value
                );

                // Find the index of the specie type to update
                const index = types.value.findIndex(
                    (typ) => typ.id === type.value.id
                );
                if (index !== -1) {
                    types.value[index] = response.data; // Update the specie type in the array
                }

                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Specie Type Updated",
                    life: 3000,
                });
            } else {
                // Create new specie type
                const response = await axios.post(
                    "/specie-types",
                    type.value
                );
                types.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Specie Type Created",
                    life: 3000,
                });
            }
            typeDialog.value = false;
            type.value = {}; // Reset form after saving
        } catch (error) {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to save specie type.",
                life: 3000,
            });
        } finally {
            loading.value = false;
        }
    }
};

const editType = (typ) => {
    editDialog.value = true;

    type.value = { ...typ };
    typeDialog.value = true;
};
const confirmDeleteType = (cat) => {
    type.value = cat;
    deleteTypeDialog.value = true;
};
const deleteType = async () => {
    loading.value = true;

    try {
        await axios.delete(`/specie-types/${type.value.id}`);
        types.value = types.value.filter(
            (val) => val.id !== type.value.id
        );
        deleteTypeDialog.value = false;
        type.value = {};
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Specie Type Deleted",
            life: 3000,
        });
    } catch (error) {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: "Failed to delete Specie Type.",
            life: 3000,
        });
    } finally {
        loading.value = false;
    }
};
const confirmDeleteSelected = () => {
    deleteTypesDialog.value = true;
};
const deleteSelectedTypes = async () => {
    loading.value = true;

    try {
        const idsToDelete = selectedTypes.value.map((typ) => typ.id);
        await axios.post(`/specie-types/bulk-delete`, { ids: idsToDelete });
        types.value = types.value.filter(
            (val) => !selectedTypes.value.includes(val)
        );
        deleteTypesDialog.value = false;
        selectedTypes.value = null;
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Specie Types Deleted",
            life: 3000,
        });
    } catch (error) {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: "Failed to delete selected Specie Types.",
            life: 3000,
        });
    } finally {
        loading.value = false;
    }
};
const exportCSV = () => {
    dt.value.exportCSV();
};
</script>
