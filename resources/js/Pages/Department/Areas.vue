<template>
    <AppLayout title="Arrests">
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
                                !selectedProducts || !selectedProducts.length
                            "
                        />
                    </template>

                    <template #end>
                        <FileUpload
                            mode="basic"
                            accept="image/*"
                            :maxFileSize="1000000"
                            label="Import"
                            customUpload
                            chooseLabel="Import"
                            class="mr-2"
                            auto
                            :chooseButtonProps="{ severity: 'secondary' }"
                        />
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
                    ref="dt"
                    v-model:selection="selectedProducts"
                    :value="products"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products"
                >
                    <template #header>
                        <div
                            class="flex flex-wrap gap-2 items-center justify-between"
                        >
                            <h4 class="m-0">Manage Products</h4>
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
                        field="code"
                        header="Code"
                        sortable
                        style="min-width: 12rem"
                    ></Column>
                    <Column
                        field="name"
                        header="Name"
                        sortable
                        style="min-width: 16rem"
                    ></Column>
                    <Column header="Image">
                        <template #body="slotProps">
                            <img
                                :src="`https://primefaces.org/cdn/primevue/images/product/${slotProps.data.image}`"
                                :alt="slotProps.data.image"
                                class="rounded"
                                style="width: 64px"
                            />
                        </template>
                    </Column>
                    <Column
                        field="price"
                        header="Price"
                        sortable
                        style="min-width: 8rem"
                    >
                        <template #body="slotProps">
                            {{ formatCurrency(slotProps.data.price) }}
                        </template>
                    </Column>
                    <Column
                        field="category"
                        header="Category"
                        sortable
                        style="min-width: 10rem"
                    ></Column>
                    <Column
                        field="rating"
                        header="Reviews"
                        sortable
                        style="min-width: 12rem"
                    >
                        <template #body="slotProps">
                            <Rating
                                :modelValue="slotProps.data.rating"
                                :readonly="true"
                            />
                        </template>
                    </Column>
                    <Column
                        field="inventoryStatus"
                        header="Status"
                        sortable
                        style="min-width: 12rem"
                    >
                        <template #body="slotProps">
                            <Tag
                                :value="slotProps.data.inventoryStatus"
                                :severity="
                                    getStatusLabel(
                                        slotProps.data.inventoryStatus
                                    )
                                "
                            />
                        </template>
                    </Column>
                    <Column :exportable="false" style="min-width: 12rem">
                        <template #body="slotProps">
                            <Button
                                icon="pi pi-pencil"
                                outlined
                                rounded
                                class="mr-2"
                                @click="editProduct(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                outlined
                                rounded
                                severity="danger"
                                @click="confirmDeleteProduct(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
            </div>

            <Dialog
                v-model:visible="productDialog"
                :style="{ width: '450px' }"
                header="Product Details"
                :modal="true"
            >
                <div class="flex flex-col gap-6">
                    <img
                        v-if="product.image"
                        :src="`https://primefaces.org/cdn/primevue/images/product/${product.image}`"
                        :alt="product.image"
                        class="block m-auto pb-4"
                    />
                    <div>
                        <label for="name" class="block font-bold mb-3"
                            >Name</label
                        >
                        <InputText
                            id="name"
                            v-model.trim="product.name"
                            required="true"
                            autofocus
                            :invalid="submitted && !product.name"
                            fluid
                        />
                        <small
                            v-if="submitted && !product.name"
                            class="text-red-500"
                            >Name is required.</small
                        >
                    </div>
                    <div>
                        <label for="description" class="block font-bold mb-3"
                            >Description</label
                        >
                        <Textarea
                            id="description"
                            v-model="product.description"
                            required="true"
                            rows="3"
                            cols="20"
                            fluid
                        />
                    </div>
                    <div>
                        <label
                            for="inventoryStatus"
                            class="block font-bold mb-3"
                            >Inventory Status</label
                        >
                        <Select
                            id="inventoryStatus"
                            v-model="product.inventoryStatus"
                            :options="statuses"
                            optionLabel="label"
                            placeholder="Select a Status"
                            fluid
                        ></Select>
                    </div>

                    <div>
                        <span class="block font-bold mb-4">Category</span>
                        <div class="grid grid-cols-12 gap-4">
                            <div class="flex items-center gap-2 col-span-6">
                                <RadioButton
                                    id="category1"
                                    v-model="product.category"
                                    name="category"
                                    value="Accessories"
                                />
                                <label for="category1">Accessories</label>
                            </div>
                            <div class="flex items-center gap-2 col-span-6">
                                <RadioButton
                                    id="category2"
                                    v-model="product.category"
                                    name="category"
                                    value="Clothing"
                                />
                                <label for="category2">Clothing</label>
                            </div>
                            <div class="flex items-center gap-2 col-span-6">
                                <RadioButton
                                    id="category3"
                                    v-model="product.category"
                                    name="category"
                                    value="Electronics"
                                />
                                <label for="category3">Electronics</label>
                            </div>
                            <div class="flex items-center gap-2 col-span-6">
                                <RadioButton
                                    id="category4"
                                    v-model="product.category"
                                    name="category"
                                    value="Fitness"
                                />
                                <label for="category4">Fitness</label>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label for="price" class="block font-bold mb-3"
                                >Price</label
                            >
                            <InputNumber
                                id="price"
                                v-model="product.price"
                                mode="currency"
                                currency="USD"
                                locale="en-US"
                                fluid
                            />
                        </div>
                        <div class="col-span-6">
                            <label for="quantity" class="block font-bold mb-3"
                                >Quantity</label
                            >
                            <InputNumber
                                id="quantity"
                                v-model="product.quantity"
                                integeronly
                                fluid
                            />
                        </div>
                    </div>
                </div>

                <template #footer>
                    <Button
                        label="Cancel"
                        icon="pi pi-times"
                        text
                        @click="hideDialog"
                    />
                    <Button
                        label="Save"
                        icon="pi pi-check"
                        @click="saveProduct"
                    />
                </template>
            </Dialog>

            <Dialog
                v-model:visible="deleteProductDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="product"
                        >Are you sure you want to delete
                        <b>{{ product.name }}</b
                        >?</span
                    >
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteProductDialog = false"
                    />
                    <Button
                        label="Yes"
                        icon="pi pi-check"
                        @click="deleteProduct"
                    />
                </template>
            </Dialog>

            <Dialog
                v-model:visible="deleteProductsDialog"
                :style="{ width: '450px' }"
                header="Confirm"
                :modal="true"
            >
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="product"
                        >Are you sure you want to delete the selected
                        products?</span
                    >
                </div>
                <template #footer>
                    <Button
                        label="No"
                        icon="pi pi-times"
                        text
                        @click="deleteProductsDialog = false"
                    />
                    <Button
                        label="Yes"
                        icon="pi pi-check"
                        text
                        @click="deleteSelectedProducts"
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
import { ProductService } from "@/primevue/service/ProductService";

import DataTable from "primevue/datatable";
import Column from "primevue/column";

import Button from "primevue/button";
import FileUpload from "primevue/fileupload";
import Toolbar from "primevue/toolbar";
import Rating from "primevue/rating";
import Textarea from "primevue/textarea";
import RadioButton from "primevue/radiobutton";
import InputNumber from "primevue/inputnumber";
import Dialog from "primevue/dialog";

import InputIcon from "primevue/inputicon";
import Select from "primevue/select";
import IconField from "primevue/iconfield";

import InputText from "primevue/inputtext";
import Tag from "primevue/tag";

import AppLayout from "@/Layouts/AppLayout.vue";

onMounted(() => {
    ProductService.getProducts().then((data) => (products.value = data));
});

const toast = useToast();
const dt = ref();
const products = ref();
const productDialog = ref(false);
const deleteProductDialog = ref(false);
const deleteProductsDialog = ref(false);
const product = ref({});
const selectedProducts = ref();
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const submitted = ref(false);
const statuses = ref([
    { label: "INSTOCK", value: "instock" },
    { label: "LOWSTOCK", value: "lowstock" },
    { label: "OUTOFSTOCK", value: "outofstock" },
]);

const formatCurrency = (value) => {
    if (value)
        return value.toLocaleString("en-US", {
            style: "currency",
            currency: "USD",
        });
    return;
};
const openNew = () => {
    product.value = {};
    submitted.value = false;
    productDialog.value = true;
};
const hideDialog = () => {
    productDialog.value = false;
    submitted.value = false;
};
const saveProduct = async () => {
    submitted.value = true;

    if (product?.value?.name?.trim()) {
        loading.value = true;

        try {
            if (product.value.id) {
                // Update product
                const response = await axios.put(
                    `/products/${product.value.id}`,
                    product.value
                );

                // Find the index of the product to update
                const index = products.value.findIndex(
                    (prod) => prod.id === product.value.id
                );
                if (index !== -1) {
                    products.value[index] = response.data; // Update the product in the array
                }

                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Product Updated",
                    life: 3000,
                });
            } else {
                // Create new product
                const response = await axios.post("/products", product.value);
                products.value.push(response.data);

                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: "Product Created",
                    life: 3000,
                });
            }

            productDialog.value = false;
            product.value = {}; // Reset form after saving
        } catch (err) {
            if (err.response && err.response.status === 422) {
                // Display validation errors
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
                console.error(err);
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

const editProduct = (prod) => {
    product.value = { ...prod };
    productDialog.value = true;
};
const confirmDeleteProduct = (prod) => {
    product.value = prod;
    deleteProductDialog.value = true;
};
const deleteProduct = () => {
    products.value = products.value.filter(
        (val) => val.id !== product.value.id
    );
    deleteProductDialog.value = false;
    product.value = {};
    toast.add({
        severity: "success",
        summary: "Successful",
        detail: "Product Deleted",
        life: 3000,
    });
};
const findIndexById = (id) => {
    let index = -1;
    for (let i = 0; i < products.value.length; i++) {
        if (products.value[i].id === id) {
            index = i;
            break;
        }
    }

    return index;
};
const createId = () => {
    let id = "";
    var chars =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for (var i = 0; i < 5; i++) {
        id += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return id;
};
const exportCSV = () => {
    dt.value.exportCSV();
};
const confirmDeleteSelected = () => {
    deleteProductsDialog.value = true;
};
const deleteSelectedProducts = () => {
    products.value = products.value.filter(
        (val) => !selectedProducts.value.includes(val)
    );
    deleteProductsDialog.value = false;
    selectedProducts.value = null;
    toast.add({
        severity: "success",
        summary: "Successful",
        detail: "Products Deleted",
        life: 3000,
    });
};

const getStatusLabel = (status) => {
    switch (status) {
        case "INSTOCK":
            return "success";

        case "LOWSTOCK":
            return "warn";

        case "OUTOFSTOCK":
            return "danger";

        default:
            return null;
    }
};
</script>
