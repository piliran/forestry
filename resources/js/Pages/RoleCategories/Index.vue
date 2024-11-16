<template>
    <AppLayout title="Role Categories">
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
                                !selectedCategories ||
                                !selectedCategories.length
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
                    v-if="categories.length > 0"
                    ref="dt"
                    v-model:selection="selectedCategories"
                    :value="categories"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} role categories"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Role Categories</h4>
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
                        header="Category Name"
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
                                @click="editCategory(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteCategory(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else class="flex items-center justify-center">
                    <h2>No role categories found</h2>
                </div>
            </div>

            <Dialog
                v-model:visible="categoryDialog"
                :style="{ width: '450px' }"
                :header="editDialog ? 'Edit Category' : 'Add New Category'"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <div>
                        <label for="name" class="block font-bold mb-3"
                            >Category Name</label
                        >
                        <InputText
                            id="name"
                            v-model.trim="category.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !category.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !category.name"
                            class="text-red-500"
                            >Category Name is required.</small
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
                            @click="saveCategory"
                        />
                    </div>
                </template>
            </Dialog>

            <Dialog
                v-model:visible="deleteCategoryDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="category"
                        >Are you sure you want to delete
                        <b>{{ category.name }}</b
                        >?</span
                    >
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteCategoryDialog = false"
                    />
                    <Button
                        label="Yes"
                        icon="pi pi-check"
                        @click="deleteCategory"
                    />
                </template>
            </Dialog>

            <Dialog
                v-model:visible="deleteCategoriesDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="category"
                        >Are you sure you want to delete the selected
                        categories?</span
                    >
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteCategoriesDialog = false"
                    />
                    <Button
                        label="Yes"
                        icon="pi pi-check"
                        text
                        @click="deleteSelectedCategories"
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
import InputText from "primevue/inputtext";
import IconField from "primevue/iconfield";
import ProgressSpinner from "primevue/progressspinner";

import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

const toast = useToast();
const dt = ref();
// const categories = ref([]);
const categoryDialog = ref(false);
const editDialog = ref(false);
const loading = ref(false);
const deleteCategoryDialog = ref(false);
const deleteCategoriesDialog = ref(false);
const category = ref({});
const selectedCategories = ref();
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const submitted = ref(false);

const props = defineProps({
    roleCategories: Array,
});

const categories = ref(props.roleCategories);

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

    category.value = {};
    submitted.value = false;
    categoryDialog.value = true;
};
const hideDialog = () => {
    categoryDialog.value = false;
    submitted.value = false;
};
const saveCategory = async () => {
    submitted.value = true;

    if (category?.value.name?.trim()) {
        loading.value = true;

        try {
            if (category.value.id) {
                // Update category
                const response = await axios.put(
                    `/role-categories/${category.value.id}`,
                    category.value
                );

                // Find the index of the category to update
                const index = categories.value.findIndex(
                    (cat) => cat.id === category.value.id
                );
                if (index !== -1) {
                    categories.value[index] = response.data; // Update the category in the array
                }

                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Category Updated",
                    life: 3000,
                });
            } else {
                // Create new category
                const response = await axios.post(
                    "/role-categories",
                    category.value
                );
                categories.value.push(response.data);
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Category Created",
                    life: 3000,
                });
            }
            categoryDialog.value = false;
            category.value = {}; // Reset form after saving
        } catch (error) {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Failed to save category.",
                life: 3000,
            });
        } finally {
            loading.value = false;
        }
    }
};

const editCategory = (cat) => {
    editDialog.value = true;

    category.value = { ...cat };
    categoryDialog.value = true;
};
const confirmDeleteCategory = (cat) => {
    category.value = cat;
    deleteCategoryDialog.value = true;
};
const deleteCategory = async () => {
    try {
        await axios.delete(`/role-categories/${category.value.id}`);
        categories.value = categories.value.filter(
            (val) => val.id !== category.value.id
        );
        deleteCategoryDialog.value = false;
        category.value = {};
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Category Deleted",
            life: 3000,
        });
    } catch (error) {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: "Failed to delete category.",
            life: 3000,
        });
    }
};
const confirmDeleteSelected = () => {
    deleteCategoriesDialog.value = true;
};
const deleteSelectedCategories = async () => {
    try {
        const idsToDelete = selectedCategories.value.map((cat) => cat.id);
        await axios.post(`/role-categories/bulk-delete`, { ids: idsToDelete });
        categories.value = categories.value.filter(
            (val) => !selectedCategories.value.includes(val)
        );
        deleteCategoriesDialog.value = false;
        selectedCategories.value = null;
        toast.add({
            severity: "success",
            summary: "Successful",
            detail: "Selected Categories Deleted",
            life: 3000,
        });
    } catch (error) {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: "Failed to delete selected categories.",
            life: 3000,
        });
    }
};
const exportCSV = () => {
    dt.value.exportCSV();
};
</script>
