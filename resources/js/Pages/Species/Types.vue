<template>
    <AppLayout title="Specie Categories">
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
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} specie categories"
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
                        header="Category Name"
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
                    <h2>No specie types found</h2>
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
                <div class="flex flex-col gap-6">
                    <div>
                        <label
                            for="description"
                            class="block font-bold mb-3 mt-3"
                            >Description</label
                        >
                        <Textarea
                            id="description"
                            v-model.trim="category.description"
                            required="true"
                            rows="3"
                            cols="20"
                            autofocus
                            :invalid="submitted && !category.description"
                            fluid
                            placeholder="Enter Description"
                        />
                        <small
                            v-if="submitted && !category.description"
                            class="text-red-500"
                            >Description is required.</small
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
                            @click="deleteCategory"
                        />
                    </div>
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
                            @click="deleteSelectedCategories"
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
import Textarea from "primevue/textarea";
import IconField from "primevue/iconfield";
import ProgressSpinner from "primevue/progressspinner";
import Breadcrumb from "primevue/breadcrumb";
import { Link } from "@inertiajs/vue3";
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
    speciesCategory: Array,
});

const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([{ label: "Specie Types" }]);

const categories = ref(props.speciesCategory);

// onMounted(async () => {
//     try {
//         const response = await axios.get("/species-types");
//         categories.value = response.data.specieCategories;
//     } catch (error) {
//         toast.add({
//             severity: "error",
//             summary: "Error",
//             detail: "Failed to load specieF categories.",
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
                    `/species-types/${category.value.id}`,
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
                    "/species-types",
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
    loading.value = true;

    try {
        await axios.delete(`/species-types/${category.value.id}`);
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
    }
};
const confirmDeleteSelected = () => {
    deleteCategoriesDialog.value = true;
};
const deleteSelectedCategories = async () => {
    loading.value = true;

    try {
        const idsToDelete = selectedCategories.value.map((cat) => cat.id);
        await axios.post(`/species-types/bulk-delete`, { ids: idsToDelete });
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
    }
};
const exportCSV = () => {
    dt.value.exportCSV();
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
