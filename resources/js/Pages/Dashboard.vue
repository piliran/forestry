<template>
    <AppLayout title="Dashboard">
        <div
            class="bg-surface-50 dark:bg-surface-950 px-6 py-2 md:px-0 lg:px-0"
        >
            <div class="grid grid-cols-12 gap-4">
                <div
                    class="col-span-12 md:col-span-6 lg:col-span-3 bg-white shadow-md overflow-hidden sm:rounded-lg"
                >
                    <div
                        class="bg-surface-0 dark:bg-surface-900 shadow p-4 rounded-border"
                    >
                        <div class="flex justify-between mb-4">
                            <div>
                                <span
                                    class="block text-surface-500 dark:text-surface-300 font-medium mb-4"
                                    >Arrests</span
                                >
                                <div
                                    class="text-surface-900 dark:text-surface-0 font-medium !text-xl"
                                >
                                    152
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-center dark:bg-blue-400/30 rounded-border border-1 w-10 h-10"
                            >
                                <i
                                    class="pi pi-address-book text-green-900 dark:text-blue-200 !text-xl"
                                />
                            </div>
                        </div>
                        <!-- <span class="text-green-500 font-medium">24 new </span>
                        <span class="text-surface-500 dark:text-surface-300"
                            >since last visit</span
                        > -->
                    </div>
                </div>
                <div
                    class="col-span-12 md:col-span-6 lg:col-span-3 bg-white shadow-md overflow-hidden sm:rounded-lg"
                >
                    <div
                        class="bg-surface-0 dark:bg-surface-900 shadow p-4 rounded-border"
                    >
                        <div class="flex justify-between mb-4">
                            <div>
                                <span
                                    class="block text-surface-500 dark:text-surface-300 font-medium mb-4"
                                    >Confiscated Items</span
                                >
                                <div
                                    class="text-surface-900 dark:text-surface-0 font-medium !text-xl"
                                >
                                    280
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-center dark:bg-blue-400/30 rounded-border border-1 w-10 h-10"
                            >
                                <i
                                    class="pi pi-car text-green-900 dark:text-blue-200 !text-xl"
                                />
                            </div>
                        </div>
                        <!-- <span class="text-green-500 font-medium">%52+ </span>
                        <span class="text-surface-500 dark:text-surface-300"
                            >since last week</span
                        > -->
                    </div>
                </div>
                <div
                    class="col-span-12 md:col-span-6 lg:col-span-3 bg-white shadow-md overflow-hidden sm:rounded-lg"
                >
                    <div
                        class="bg-surface-0 dark:bg-surface-900 shadow p-4 rounded-border"
                    >
                        <div class="flex justify-between mb-4">
                            <div>
                                <span
                                    class="block text-surface-500 dark:text-surface-300 font-medium mb-4"
                                    >Encroached Areas</span
                                >
                                <div
                                    class="text-surface-900 dark:text-surface-0 font-medium !text-xl"
                                >
                                    28441
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-center dark:bg-blue-400/30 rounded-border border-1 w-10 h-10"
                            >
                                <i
                                    class="pi pi-map-marker text-green-900 dark:text-blue-200 !text-xl"
                                />
                            </div>
                        </div>
                        <!-- <span class="text-green-500 font-medium">520 </span>
                        <span class="text-surface-500 dark:text-surface-300"
                            ></span
                        > -->
                    </div>
                </div>
                <div
                    class="col-span-12 md:col-span-6 lg:col-span-3 bg-white shadow-md overflow-hidden sm:rounded-lg"
                >
                    <div
                        class="bg-surface-0 dark:bg-surface-900 shadow p-4 rounded-border"
                    >
                        <div class="flex justify-between mb-4">
                            <div>
                                <span
                                    class="block text-surface-500 dark:text-surface-300 font-medium mb-4"
                                    >Convictions</span
                                >
                                <div
                                    class="text-surface-900 dark:text-surface-0 font-medium !text-xl"
                                >
                                    152
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-center dark:bg-blue-400/30 rounded-border border-1 w-10 h-10"
                            >
                                <i
                                    class="pi pi-ban text-green-900 dark:text-blue-200 !text-xl"
                                />
                            </div>
                        </div>
                        <!-- <span class="text-green-500 font-medium">85 </span>
                        <span class="text-surface-500 dark:text-surface-300"
                            >responded</span
                        > -->
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-4 py-4">
            <div
                class="col-span-12 md:col-span-6 lg:col-span-8 py-6 bg-white shadow-md overflow-hidden sm:rounded-lg"
            >
                <Chart
                    type="line"
                    :data="chartData"
                    :options="chartOptions"
                    class="h-[30rem]"
                />
            </div>

            <!-- <div
                class="col-span-12 md:col-span-6 lg:col-span-6 py-6 bg-white shadow-md overflow-hidden sm:rounded-lg"
            >
                <Chart
                    type="bar"
                    :data="barChartData"
                    :options="barChartOptions"
                    class="h-[30rem]"
                />
            </div> -->

            <!-- Timeline -->
            <div
                class="col-span-12 md:col-span-6 lg:col-span-4 py-6 bg-white shadow-md overflow-hidden sm:rounded-lg"
            >
                <!-- Heading -->
                <div class="ps-2 my-2 first:mt-0">
                    <h3 class="text-xs font-medium uppercase text-gray-500">
                        1 Aug, 2023
                    </h3>
                </div>
                <!-- End Heading -->

                <!-- Item -->
                <div class="flex gap-x-3">
                    <!-- Icon -->
                    <div
                        class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200"
                    >
                        <div
                            class="relative z-10 size-7 flex justify-center items-center"
                        >
                            <div class="size-2 rounded-full bg-gray-400"></div>
                        </div>
                    </div>
                    <!-- End Icon -->

                    <!-- Right Content -->
                    <div class="grow pt-0.5 pb-8">
                        <h3 class="flex gap-x-1.5 font-semibold text-gray-800">
                            <svg
                                class="shrink-0 size-4 mt-1"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"
                                ></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" x2="8" y1="13" y2="13"></line>
                                <line x1="16" x2="8" y1="17" y2="17"></line>
                                <line x1="10" x2="8" y1="9" y2="9"></line>
                            </svg>
                            Created "Preline in React" task
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Find more detailed insctructions here.
                        </p>
                        <button
                            type="button"
                            class="mt-1 -ms-1 p-1 inline-flex items-center gap-x-2 text-xs rounded-lg border border-transparent text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none"
                        >
                            <img
                                class="shrink-0 size-4 rounded-full"
                                src="https://images.unsplash.com/photo-1659482633369-9fe69af50bfb?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8auto=format&fit=facearea&facepad=3&w=320&h=320&q=80"
                                alt="Avatar"
                            />
                            James Collins
                        </button>
                    </div>
                    <!-- End Right Content -->
                </div>
                <!-- End Item -->

                <!-- Item -->
                <div class="flex gap-x-3">
                    <!-- Icon -->
                    <div
                        class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200"
                    >
                        <div
                            class="relative z-10 size-7 flex justify-center items-center"
                        >
                            <div class="size-2 rounded-full bg-gray-400"></div>
                        </div>
                    </div>
                    <!-- End Icon -->

                    <!-- Right Content -->
                    <div class="grow pt-0.5 pb-8">
                        <h3 class="flex gap-x-1.5 font-semibold text-gray-800">
                            Release v5.2.0 quick bug fix 🐞
                        </h3>
                        <button
                            type="button"
                            class="mt-1 -ms-1 p-1 inline-flex items-center gap-x-2 text-xs rounded-lg border border-transparent text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none"
                        >
                            <span
                                class="flex shrink-0 justify-center items-center size-4 bg-white border border-gray-200 text-[10px] font-semibold uppercase text-gray-600 rounded-full"
                            >
                                A
                            </span>
                            Alex Gregarov
                        </button>
                    </div>
                    <!-- End Right Content -->
                </div>
                <!-- End Item -->

                <!-- Item -->
                <div class="flex gap-x-3">
                    <!-- Icon -->
                    <div
                        class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200"
                    >
                        <div
                            class="relative z-10 size-7 flex justify-center items-center"
                        >
                            <div class="size-2 rounded-full bg-gray-400"></div>
                        </div>
                    </div>
                    <!-- End Icon -->

                    <!-- Right Content -->
                    <div class="grow pt-0.5 pb-8">
                        <h3 class="flex gap-x-1.5 font-semibold text-gray-800">
                            Marked "Install Charts" completed
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Finally! You can check it out here.
                        </p>
                        <button
                            type="button"
                            class="mt-1 -ms-1 p-1 inline-flex items-center gap-x-2 text-xs rounded-lg border border-transparent text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none"
                        >
                            <img
                                class="shrink-0 size-4 rounded-full"
                                src="https://images.unsplash.com/photo-1659482633369-9fe69af50bfb?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=3&w=320&h=320&q=80"
                                alt="Avatar"
                            />
                            James Collins
                        </button>
                    </div>
                    <!-- End Right Content -->
                </div>
                <!-- End Item -->

                <!-- Heading -->
                <div class="ps-2 my-2 first:mt-0">
                    <h3 class="text-xs font-medium uppercase text-gray-500">
                        31 Jul, 2023
                    </h3>
                </div>
                <!-- End Heading -->

                <!-- Item -->
                <div class="flex gap-x-3">
                    <!-- Icon -->
                    <div
                        class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200"
                    >
                        <div
                            class="relative z-10 size-7 flex justify-center items-center"
                        >
                            <div class="size-2 rounded-full bg-gray-400"></div>
                        </div>
                    </div>
                    <!-- End Icon -->

                    <!-- Right Content -->
                    <div class="grow pt-0.5 pb-8">
                        <h3 class="flex gap-x-1.5 font-semibold text-gray-800">
                            Take a break ⛳️
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Just chill for now... 😉
                        </p>
                    </div>
                    <!-- End Right Content -->
                </div>
                <!-- End Item -->
            </div>
            <!-- End Timeline -->
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Chart from "primevue/chart";
import Card from "primevue/card";
import Icon from "@/Shared/Icon.vue";

onMounted(() => {
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
    barChartData.value = setBarChartData();
    barChartOptions.value = setBarChartOptions();
});

const chartData = ref();
const chartOptions = ref();
const barChartData = ref();
const barChartOptions = ref();

const setChartData = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
        ],
        datasets: [
            {
                label: "First Dataset",
                data: [65, 59, 80, 81, 56, 55, 40],
                fill: false,
                tension: 0.4,
                borderColor: documentStyle.getPropertyValue("--p-cyan-500"),
            },
            {
                label: "Second Dataset",
                data: [28, 48, 40, 19, 86, 27, 90],
                fill: false,
                borderDash: [5, 5],
                tension: 0.4,
                borderColor: documentStyle.getPropertyValue("--p-orange-500"),
            },
            {
                label: "Third Dataset",
                data: [12, 51, 62, 33, 21, 62, 45],
                fill: true,
                borderColor: documentStyle.getPropertyValue("--p-gray-500"),
                tension: 0.4,
                backgroundColor: "rgba(107, 114, 128, 0.2)",
            },
        ],
    };
};
const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue("--p-text-color");
    const textColorSecondary = documentStyle.getPropertyValue(
        "--p-text-muted-color"
    );
    const surfaceBorder = documentStyle.getPropertyValue(
        "--p-content-border-color"
    );

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.6,
        plugins: {
            legend: {
                labels: {
                    color: textColor,
                },
            },
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary,
                },
                grid: {
                    color: surfaceBorder,
                },
            },
            y: {
                ticks: {
                    color: textColorSecondary,
                },
                grid: {
                    color: surfaceBorder,
                },
            },
        },
    };
};

const setBarChartData = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
        ],
        datasets: [
            {
                label: "My First dataset",
                backgroundColor: documentStyle.getPropertyValue("--p-cyan-500"),
                borderColor: documentStyle.getPropertyValue("--p-cyan-500"),
                data: [65, 59, 80, 81, 56, 55, 40],
            },
            {
                label: "My Second dataset",
                backgroundColor: documentStyle.getPropertyValue("--p-gray-500"),
                borderColor: documentStyle.getPropertyValue("--p-gray-500"),
                data: [28, 48, 40, 19, 86, 27, 90],
            },
        ],
    };
};
const setBarChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue("--p-text-color");
    const textColorSecondary = documentStyle.getPropertyValue(
        "--p-text-muted-color"
    );
    const surfaceBorder = documentStyle.getPropertyValue(
        "--p-content-border-color"
    );

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.8,
        plugins: {
            legend: {
                labels: {
                    color: textColor,
                },
            },
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary,
                    font: {
                        weight: 500,
                    },
                },
                grid: {
                    display: false,
                    drawBorder: false,
                },
            },
            y: {
                ticks: {
                    color: textColorSecondary,
                },
                grid: {
                    color: surfaceBorder,
                    drawBorder: false,
                },
            },
        },
    };
};
</script>
