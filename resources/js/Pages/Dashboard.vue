<template>
    <AppLayout title="Dashboard">
        <div class="md:flex md:flex-grow gap-8 xl:flex-row">
            <div
                class="py-8 w-full mt-6 px-6 bg-white shadow-md overflow-hidden sm:rounded-lg"
            >
                50
            </div>

            <div
                class="py-8 w-full mt-6 px-6 bg-white shadow-md overflow-hidden sm:rounded-lg"
            >
                50
            </div>

            <div
                class="py-8 w-full mt-6 px-6 bg-white shadow-md overflow-hidden sm:rounded-lg"
            >
                50
            </div>

            <div
                class="py-8 w-full mt-6 px-6 bg-white shadow-md overflow-hidden sm:rounded-lg"
            >
                50
            </div>
        </div>

        <div
            class="py-8 w-full mt-6 px-6 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <Chart
                type="line"
                :data="chartData"
                :options="chartOptions"
                class="h-[30rem]"
            />
        </div>

        <div
            class="py-8 w-full mt-6 px-6 bg-white shadow-md overflow-hidden sm:rounded-lg"
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
