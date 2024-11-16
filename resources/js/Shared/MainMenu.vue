<template>
    <div>
        <div v-for="(item, index) in items" :key="index" class="mb-4">
            <!-- Main Link -->
            <div
                class="group flex items-center py-2 px-4 cursor-pointer"
                :class="[
                    isUrl(item.route) ? 'bg-white rounded-xl' : 'hover:bg-dark',
                    item.class,
                ]"
                @click.stop="toggleDropdown(index, item)"
            >
                <Icon
                    :name="item.icon"
                    class="mr-2 w-4 h-4"
                    :class="
                        isUrl(item.route)
                            ? 'fill-green-900'
                            : 'fill-white group-hover:fill-green-900'
                    "
                />
                <div
                    :class="
                        isUrl(item.route)
                            ? 'text-green-900'
                            : 'text-white group-hover:text-green-900'
                    "
                >
                    {{ item.label }}
                </div>

                <Icon
                    v-if="item.forwardIcon"
                    :name="
                        isDropdownVisible(index)
                            ? 'cheveron-down'
                            : 'cheveron-right'
                    "
                    class="mr-2 w-4 h-4 ml-2"
                    :class="
                        isUrl(item.route)
                            ? 'fill-green-900'
                            : 'fill-white group-hover:fill-green-900'
                    "
                />
            </div>

            <!-- Sublinks -->
            <div v-if="isDropdownVisible(index)" class="ml-6">
                <div v-for="(subItem, subIndex) in item.items" :key="subIndex">
                    <Link
                        v-if="subItem.route"
                        :href="subItem.route"
                        class="group flex items-center my-3 py-2 px-4"
                        :class="
                            isUrl(subItem.route)
                                ? 'bg-white rounded-xl'
                                : 'hover:bg-dark'
                        "
                        @click.stop="navigateSubLink(subItem)"
                    >
                        <Icon
                            :name="subItem.icon"
                            class="mr-2 w-4 h-4"
                            :class="
                                isUrl(subItem.route)
                                    ? 'fill-green-900'
                                    : 'fill-white group-hover:fill-green-900'
                            "
                        />
                        <div
                            :class="
                                isUrl(subItem.route)
                                    ? 'text-green-900'
                                    : 'text-white group-hover:text-green-900'
                            "
                        >
                            {{ subItem.label }}
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import Icon from "@/Shared/Icon.vue";
import { router } from "@inertiajs/vue3";

const page = usePage();
const items = ref([
    {
        label: "Dashboard",
        icon: "dashboard",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,
        route: "/dashboard",
    },
    {
        label: "Users",
        icon: "users",
        class: "parent-link-with-sublinks",
        forwardIcon: true,

        items: [
            {
                label: "Users List",
                icon: "dot",
                route: "/users-list",
            },
            {
                label: "Role Categories",
                icon: "dot",

                route: "/role-categories",
            },
            {
                label: "Roles",
                icon: "dot",
                route: "/roles",
            },
            {
                label: "User Roles",
                icon: "dot",
                route: "/user-roles",
            },
            {
                label: "Permissions",
                icon: "dot",
                route: "/permissions",
            },
        ],
    },
    {
        label: "Districts",
        icon: "districts",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,

        route: "/districts",
    },
    {
        label: "Stations",
        icon: "stations",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,
        route: "/stations",
    },
    {
        label: "Crimes",
        icon: "crimes",
        forwardIcon: false,
        class: "parent-link-with-no-sublinks",

        route: "/crimes",
    },
    {
        label: "Arrests",
        icon: "arrests",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,

        route: "/arrests",
    },
    {
        label: "Confiscates",
        icon: "confiscated-items",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,

        route: "/confiscates",
    },
    {
        label: "Encroached Areas",
        icon: "encroached-areas",
        class: "parent-link-with-no-sublinks",

        route: "/encroached-areas",
    },
    {
        label: "Routes",
        icon: "road",
        class: "parent-link-with-sublinks",
        forwardIcon: true,

        items: [
            {
                label: "Route List",
                icon: "dot",
                route: "/route-list",
            },
            {
                label: "Route Types",
                icon: "dot",
                route: "/route-types",
            },
        ],
    },

    {
        label: "Species",
        icon: "road",
        class: "parent-link-with-sublinks",
        forwardIcon: true,

        items: [
            {
                label: "Species List",
                icon: "dot",
                route: "/species-list",
            },
            {
                label: "Species Category",
                icon: "dot",
                route: "/species-types",
            },
        ],
    },
]);

const dropdownVisibility = ref([]);

const toggleDropdown = (index, item) => {
    if (item.items && item.items.length > 0) {
        // Ensure the dropdownVisibility array has enough entries
        if (dropdownVisibility.value.length <= index) {
            dropdownVisibility.value = Array.from(
                { length: items.value.length },
                () => false
            );
        }

        // Toggle the clicked dropdown while closing others
        dropdownVisibility.value = dropdownVisibility.value.map((visible, i) =>
            i === index ? !visible : false
        );

        sessionStorage.setItem(
            "dropdownVisibility",
            JSON.stringify(dropdownVisibility.value)
        );
    } else {
        // For links without sublinks, close all dropdowns
        dropdownVisibility.value = dropdownVisibility.value.map(() => false);

        sessionStorage.setItem(
            "dropdownVisibility",
            JSON.stringify(dropdownVisibility.value)
        );

        router.visit(item.route); // Navigate to the link
    }
};

onMounted(() => {
    const savedVisibility = JSON.parse(
        sessionStorage.getItem("dropdownVisibility")
    );
    if (
        Array.isArray(savedVisibility) &&
        savedVisibility.length === items.value.length
    ) {
        dropdownVisibility.value = savedVisibility;
    } else {
        // Reset the dropdownVisibility to match items length
        dropdownVisibility.value = Array.from(
            { length: items.value.length },
            () => false
        );
    }
});

const navigateSubLink = (subItem) => {
    router.visit(subItem.route);
};

const isUrl = (route) => {
    let currentUrl = window.location.pathname;

    return currentUrl === `${route}`;
};

const isDropdownVisible = (index) => {
    return dropdownVisibility.value[index] || false;
};
</script>
<style>
.bg-dark {
    background-color: #ffffff;
}

.text-gray-900 {
    color: #e0e0e0;
}

.hover\:bg-dark:hover {
    background: #ffffff;
    border-radius: 10px;
}
</style>
