<template>
    <AppLayout title="Dashboard">
        <div
            class="bg-surface-50 dark:bg-surface-950 px-6 py-2 md:px-6 lg:px-6"
        >
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-6 lg:col-span-3 bg-white">
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
                                class="flex items-center justify-center bg-blue-100 dark:bg-blue-400/30 rounded-border w-10 h-10"
                            >
                                <i
                                    class="pi pi-address-book text-blue-500 dark:text-blue-200 !text-xl"
                                />
                            </div>
                        </div>
                        <!-- <span class="text-green-500 font-medium">24 new </span>
                        <span class="text-surface-500 dark:text-surface-300"
                            >since last visit</span
                        > -->
                    </div>
                </div>
                <div class="col-span-12 md:col-span-6 lg:col-span-3 bg-white">
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
                                class="flex items-center justify-center bg-orange-100 dark:bg-orange-400/30 rounded-border w-10 h-10"
                            >
                                <i
                                    class="pi pi-car text-orange-500 dark:text-orange-200 !text-xl"
                                />
                            </div>
                        </div>
                        <!-- <span class="text-green-500 font-medium">%52+ </span>
                        <span class="text-surface-500 dark:text-surface-300"
                            >since last week</span
                        > -->
                    </div>
                </div>
                <div class="col-span-12 md:col-span-6 lg:col-span-3 bg-white">
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
                                class="w-10 h-10 flex items-center justify-center bg-cyan-100 dark:bg-cyan-400/30 rounded-border"
                            >
                                <i
                                    class="pi pi-map-marker text-cyan-500 dark:text-cyan-200 !text-xl"
                                />
                            </div>
                        </div>
                        <!-- <span class="text-green-500 font-medium">520 </span>
                        <span class="text-surface-500 dark:text-surface-300"
                            ></span
                        > -->
                    </div>
                </div>
                <div class="col-span-12 md:col-span-6 lg:col-span-3 bg-white">
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
                                class="w-10 h-10 flex items-center justify-center bg-purple-100 dark:bg-purple-400/30 rounded-border"
                            >
                                <i
                                    class="pi pi-ban text-red-500 dark:text-purple-200 !text-xl"
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

        <div
            class="px-6 py-8 md:px-12 lg:px-6 mx-6 mt-6 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <Chart
                type="line"
                :data="chartData"
                :options="chartOptions"
                class="h-[30rem]"
            />
        </div>

        <div
            class="px-6 py-8 md:px-12 lg:px-6 mx-6 my-6 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <Chart
                type="bar"
                :data="barChartData"
                :options="barChartOptions"
                class="h-[30rem]"
            />
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Chart from "primevue/chart";
import Card from "primevue/card";

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
