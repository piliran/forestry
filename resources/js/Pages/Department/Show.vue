<!-- component -->
<template>
    <AppLayout title="Department Details">
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

            <div class="h-full">
                <div class="grid grid-cols-1 gap-8 mt-4">
                    <!-- Staff Card -->
                    <div
                        class="p-6 bg-white border border-gray-200 rounded-xl flex shadow-lg items-center justify-between"
                    >
                        <div class="flex">
                            <div class="ml-4">
                                <h1
                                    class="text-3xl font-semibold text-gray-800"
                                >
                                    Department Of {{ department.name }}
                                </h1>
                                <span class="text-lg text-gray-800"
                                    ><span class="font-semibold">Email:</span>
                                    {{ department.email }}</span
                                >
                                <h1 class="text-lg text-gray-800">
                                    <span class="font-semibold">Website:</span>
                                    {{ department.website }}
                                </h1>
                                <div
                                    class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4"
                                >
                                    <!-- Staff Card -->
                                    <div
                                        class="p-2 px-6 bg-white border border-gray-200 rounded-xl shadow-lg flex items-center justify-between"
                                    >
                                        <div
                                            class="flex items-center space-x-3"
                                        >
                                            <span class="text-lg text-gray-800"
                                                >Staff</span
                                            >
                                        </div>
                                        <span
                                            class="text-2xl text-indigo-600 ml-3"
                                        >
                                            {{ department.staff_count }}
                                        </span>
                                    </div>

                                    <!-- Zones Card -->
                                    <div
                                        class="p-2 px-6 bg-white border border-gray-200 rounded-xl shadow-lg flex items-center justify-between"
                                    >
                                        <div class="flex items-center">
                                            <span class="text-lg text-gray-800"
                                                >Zones</span
                                            >
                                        </div>
                                        <span
                                            class="text-2xl text-green-600 ml-3"
                                        >
                                            {{ department.zone_count }}
                                        </span>
                                    </div>

                                    <!-- Stations Card -->
                                    <div
                                        class="p-2 px-6 bg-white border border-gray-200 rounded-xl shadow-lg flex items-center justify-between"
                                    >
                                        <div
                                            class="flex items-center space-x-3"
                                        >
                                            <span class="text-lg text-gray-800"
                                                >Stations</span
                                            >
                                        </div>
                                        <span
                                            class="text-2xl text-blue-600 ml-3"
                                        >
                                            {{ department.station_count }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Zones Card -->
                    <!-- <div
                        class="p-6 bg-white border border-gray-200 rounded-xl shadow-lg flex items-center justify-between"
                    >
                        <div class="flex items-center space-x-3">
                            <div class="bg-green-100 p-3 rounded-full">
                                <svg
                                    class="w-8 h-8 text-green-600"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                                    ></path>
                                </svg>
                            </div>
                            <span class="text-lg font-semibold text-gray-800"
                                >Zones</span
                            >
                        </div>
                        <span class="text-4xl font-extrabold text-green-600">
                            {{ department.zone_count }}
                        </span>
                    </div> -->

                    <!-- Stations Card -->
                    <!-- <div
                        class="p-6 bg-white border border-gray-200 rounded-xl shadow-lg flex items-center justify-between"
                    >
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-100 p-3 rounded-full">
                                <svg
                                    class="w-8 h-8 text-blue-600"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    ></path>
                                </svg>
                            </div>
                            <span class="text-lg font-semibold text-gray-800"
                                >Stations</span
                            >
                        </div>
                        <span class="text-4xl font-extrabold text-blue-600">
                            {{ department.station_count }}
                        </span>
                    </div> -->
                </div>

                <div
                    class="my-4 flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4"
                >
                    <!-- <div class="flex flex-col w-full lg:w-1/2 2xl:w-1/3">
                        <div class="flex-1 bg-white rounded-lg shadow-xl p-8">
                            <h4 class="text-xl text-gray-900 font-bold">
                                About Department
                            </h4>
                            <p class="mt-2 text-gray-700">
                                Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Nesciunt voluptates obcaecati
                                numquam error et ut fugiat asperiores. Sunt
                                nulla ad incidunt laboriosam, laudantium est
                                unde natus cum numquam, neque facere. Lorem
                                ipsum dolor sit amet consectetur adipisicing
                                elit. Ut, magni odio magnam commodi sunt ipsum
                                eum! Voluptas eveniet aperiam at maxime, iste id
                                dicta autem odio laudantium eligendi commodi
                                distinctio!
                            </p>
                        </div>
                    </div> -->
                    <div class="flex flex-col lg:w-1/2 2xl:w-1/3">
                        <div class="flex-1 bg-white rounded-lg shadow-xl p-8">
                            <h4 class="text-xl text-gray-900 font-bold">
                                Contact Person
                            </h4>

                            <div class="flex flex-col items-center">
                                <div class="flex flex-col items-center">
                                    <img
                                        v-if="
                                            department.contact_person
                                                .profile_photo_url
                                        "
                                        :src="
                                            department.contact_person
                                                .profile_photo_url
                                        "
                                        alt="user Photo"
                                        class="mt-5 h-40 w-40 border-4 border-white rounded-full"
                                    />
                                </div>
                            </div>
                            <ul class="mt-2 text-gray-700">
                                <li class="flex border-y py-2">
                                    <span class="font-bold w-24"
                                        >Full name:</span
                                    >
                                    <span class="text-gray-700">{{
                                        department.contact_person.name || "N/A"
                                    }}</span>
                                </li>
                                <li class="flex border-b py-2">
                                    <span class="font-bold w-24">Mobile:</span>

                                    <span class="text-gray-700">{{
                                        department.contact_person.phone || "N/A"
                                    }}</span>
                                </li>
                                <li class="flex border-b py-2">
                                    <span class="font-bold w-24">Email:</span>
                                    <span class="text-gray-700">{{
                                        department.contact_person.email || "N/A"
                                    }}</span>
                                </li>
                                <li
                                    class="flex items-center border-b py-2 space-x-2"
                                >
                                    <span class="font-bold w-24"
                                        >Elsewhere:</span
                                    >
                                    <a href="#" title="Facebook">
                                        <svg
                                            class="w-5 h-5"
                                            id="Layer_1"
                                            data-name="Layer 1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 506.86 506.86"
                                        >
                                            <defs>
                                                <!-- <style>.cls-1{fill:#1877f2;}.cls-2{fill:#fff;}</style> -->
                                            </defs>
                                            <path
                                                class="cls-1"
                                                d="M506.86,253.43C506.86,113.46,393.39,0,253.43,0S0,113.46,0,253.43C0,379.92,92.68,484.77,213.83,503.78V326.69H149.48V253.43h64.35V197.6c0-63.52,37.84-98.6,95.72-98.6,27.73,0,56.73,5,56.73,5v62.36H334.33c-31.49,0-41.3,19.54-41.3,39.58v47.54h70.28l-11.23,73.26H293V503.78C414.18,484.77,506.86,379.92,506.86,253.43Z"
                                            ></path>
                                            <path
                                                class="cls-2"
                                                d="M352.08,326.69l11.23-73.26H293V205.89c0-20,9.81-39.58,41.3-39.58h31.95V104s-29-5-56.73-5c-57.88,0-95.72,35.08-95.72,98.6v55.83H149.48v73.26h64.35V503.78a256.11,256.11,0,0,0,79.2,0V326.69Z"
                                            ></path>
                                        </svg>
                                    </a>
                                    <a href="#" title="Twitter">
                                        <svg
                                            class="w-5 h-5"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 333333 333333"
                                            shape-rendering="geometricPrecision"
                                            text-rendering="geometricPrecision"
                                            image-rendering="optimizeQuality"
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                        >
                                            <path
                                                d="M166667 0c92048 0 166667 74619 166667 166667s-74619 166667-166667 166667S0 258715 0 166667 74619 0 166667 0zm90493 110539c-6654 2976-13822 4953-21307 5835 7669-4593 13533-11870 16333-20535-7168 4239-15133 7348-23574 9011-6787-7211-16426-11694-27105-11694-20504 0-37104 16610-37104 37101 0 2893 320 5722 949 8450-30852-1564-58204-16333-76513-38806-3285 5666-5022 12109-5022 18661v4c0 12866 6532 24246 16500 30882-6083-180-11804-1876-16828-4626v464c0 17993 12789 33007 29783 36400-3113 845-6400 1313-9786 1313-2398 0-4709-247-7007-665 4746 14736 18448 25478 34673 25791-12722 9967-28700 15902-46120 15902-3006 0-5935-184-8860-534 16466 10565 35972 16684 56928 16684 68271 0 105636-56577 105636-105632 0-1630-36-3209-104-4806 7251-5187 13538-11733 18514-19185l17-17-3 2z"
                                                fill="#1da1f2"
                                            ></path>
                                        </svg>
                                    </a>
                                    <a href="#" title="LinkedIn">
                                        <svg
                                            class="w-5 h-5"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 333333 333333"
                                            shape-rendering="geometricPrecision"
                                            text-rendering="geometricPrecision"
                                            image-rendering="optimizeQuality"
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                        >
                                            <path
                                                d="M166667 0c92048 0 166667 74619 166667 166667s-74619 166667-166667 166667S0 258715 0 166667 74619 0 166667 0zm-18220 138885h28897v14814l418 1c4024-7220 13865-14814 28538-14814 30514-1 36157 18989 36157 43691v50320l-30136 1v-44607c0-10634-221-24322-15670-24322-15691 0-18096 11575-18096 23548v45382h-30109v-94013zm-20892-26114c0 8650-7020 15670-15670 15670s-15672-7020-15672-15670 7022-15670 15672-15670 15670 7020 15670 15670zm-31342 26114h31342v94013H96213v-94013z"
                                                fill="#0077b5"
                                            ></path>
                                        </svg>
                                    </a>
                                    <a href="#" title="Github">
                                        <svg
                                            class="w-5 h-5"
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="0"
                                            height="0"
                                            shape-rendering="geometricPrecision"
                                            text-rendering="geometricPrecision"
                                            image-rendering="optimizeQuality"
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            viewBox="0 0 640 640"
                                        >
                                            <path
                                                d="M319.988 7.973C143.293 7.973 0 151.242 0 327.96c0 141.392 91.678 261.298 218.826 303.63 16.004 2.964 21.886-6.957 21.886-15.414 0-7.63-.319-32.835-.449-59.552-89.032 19.359-107.8-37.772-107.8-37.772-14.552-36.993-35.529-46.831-35.529-46.831-29.032-19.879 2.209-19.442 2.209-19.442 32.126 2.245 49.04 32.954 49.04 32.954 28.56 48.922 74.883 34.76 93.131 26.598 2.882-20.681 11.15-34.807 20.315-42.803-71.08-8.067-145.797-35.516-145.797-158.14 0-34.926 12.52-63.485 32.965-85.88-3.33-8.078-14.291-40.606 3.083-84.674 0 0 26.87-8.61 88.029 32.8 25.512-7.075 52.878-10.642 80.056-10.76 27.2.118 54.614 3.673 80.162 10.76 61.076-41.386 87.922-32.8 87.922-32.8 17.398 44.08 6.485 76.631 3.154 84.675 20.516 22.394 32.93 50.953 32.93 85.879 0 122.907-74.883 149.93-146.117 157.856 11.481 9.921 21.733 29.398 21.733 59.233 0 42.792-.366 77.28-.366 87.804 0 8.516 5.764 18.473 21.992 15.354 127.076-42.354 218.637-162.274 218.637-303.582 0-176.695-143.269-319.988-320-319.988l-.023.107z"
                                            ></path>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-lg shadow-xl p-8 flex flex-col lg:w-2/3 2xl:w-2/3"
                    >
                        <div class="flex items-center justify-between">
                            <h4 class="text-xl text-gray-900 font-bold">
                                <Badge size="large" severity="contrast">{{
                                    department.staff_count
                                }}</Badge>
                                Members of Staff
                            </h4>
                        </div>
                        <div v-if="staffList.length > 0" class="p-2">
                            <div
                                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-8 gap-8 mt-8"
                            >
                                <div
                                    v-for="staff in paginatedStaffList"
                                    :key="staff.id"
                                    class="flex flex-col items-center justify-center bg-white p-4 rounded-lg"
                                >
                                    <a
                                        href="#"
                                        class="flex flex-col items-center justify-center text-gray-800 hover:text-blue-600"
                                        title="View Profile"
                                    >
                                        <Image
                                            :src="`${staff.user.profile_photo_url}`"
                                            :alt="staff.user.name"
                                            class="border-white rounded-full"
                                            preview
                                        >
                                            <template #image>
                                                <img
                                                    :src="
                                                        staff.user
                                                            .profile_photo_url
                                                    "
                                                    alt="Profile"
                                                    style="
                                                        width: 100%;
                                                        height: 100%;
                                                        object-fit: cover;
                                                    "
                                                    class="border-white rounded-full"
                                                />
                                            </template>
                                        </Image>

                                        <p
                                            class="text-center font-bold text-sm mt-1"
                                        >
                                            {{ staff.user.name }}
                                        </p>
                                        <p
                                            class="text-xs text-gray-500 text-center"
                                        >
                                            {{
                                                staff.user.roles
                                                    .map((role) => role.name)
                                                    .join(", ")
                                            }}
                                        </p>
                                    </a>
                                </div>
                            </div>
                            <!-- Paginator -->
                            <!-- <Paginator
                                :rows="rowsPerPage"
                                :totalRecords="staffList.length"
                                :rowsPerPageOptions="[4, 8, 16]"
                                :currentPage="currentPage"
                                @page="onPageChange"
                                class="mt-4"
                            /> -->
                        </div>
                        <div v-else class="flex items-center justify-center">
                            <h2>No Staff Members Found</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script setup>
import { ref, onMounted, computed } from "vue";
import { FilterMatchMode } from "@primevue/core/api";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import Image from "primevue/image";
import DataTable from "primevue/datatable";

import Paginator from "primevue/paginator";
import Badge from "primevue/badge";
import OverlayBadge from "primevue/overlaybadge";
import Column from "primevue/column";
import Button from "primevue/button";
import Toolbar from "primevue/toolbar";
import Dialog from "primevue/dialog";
import InputIcon from "primevue/inputicon";
import InputText from "primevue/inputtext";
import IconField from "primevue/iconfield";
import Select from "primevue/select";
import ProgressSpinner from "primevue/progressspinner";
import Breadcrumb from "primevue/breadcrumb";
import { Link, router } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";

const page = usePage();

const toast = useToast();
const staff = ref({});
const home = ref({
    icon: "pi pi-home",
    label: "Dashboard",
    route: "/dashboard",
});

const breadCumbItems = ref([
    { label: "Departments", route: "/departments" },
    { label: router.page.url.split("/").pop() },
]);

const props = defineProps({
    department: Object,
    staffList: Array,
});

if (page.props.auth.user == null) {
    console.log("no user");
    router.replace("login");
}

const department = ref(props.department);
const staffList = ref(props.staffList);
// console.log(department.value);

const rowsPerPage = ref(8);
const currentPage = ref(0);

const paginatedStaffList = computed(() => {
    const start = (currentPage.value || 0) * (rowsPerPage.value || 10);
    const end = start + (rowsPerPage.value || 10);
    return Array.isArray(staffList.value)
        ? staffList.value.slice(start, end)
        : [];
});

const start = Number(currentPage.value) * Number(rowsPerPage.value);
const end = start + Number(rowsPerPage.value);

// Handle Page Change
const onPageChange = (event) => {
    currentPage.value = event.page;
};
</script>
<style scoped>
::v-deep(.p-breadcrumb) {
    background: transparent !important;
    box-shadow: none !important;
}
</style>
