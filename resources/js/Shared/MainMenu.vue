<template>
    <div class="overflow-y-auto" scroll-region>
        <div v-for="(item, index) in items" :key="index" class="mb-4">
            <!-- Main Link -->
            <!-- v-if="hasAccess(item.allowedRoles)" -->
            <div
                ref="sidebarRef"
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

const roles = computed(() => {
    const userRoles = page.props.userRoles || [];
    return userRoles
        .filter((item) => item?.role?.name)
        .map((item) => item.role.name);
});

const permissions = computed(() => page.props.can || {});

const sidebarRef = ref(null);
const savedScrollPosition = ref(0);

if (roles.value.includes("admin")) {
    console.log("User is an admin");
}

onMounted(() => {
    const storedPosition = localStorage.getItem("sidebarScrollPosition");
    if (storedPosition && sidebarRef.value) {
        sidebarRef.value.scrollTop = parseInt(storedPosition, 10);
    }
});

const items = ref([
    {
        label: "Dashboard",
        icon: "home",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,
        route: "/dashboard",
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],
    },
    {
        label: "Countries",
        icon: "countries",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,
        route: "/countries",
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],
    },

    {
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,

        label: "Privileges",
        icon: "permissions",
        route: "/privileges",

        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],
    },

    {
        label: "Roles",
        icon: "roles",
        class: "parent-link-with-sublinks",
        forwardIcon: true,
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],

        items: [
            {
                label: "Categories",
                icon: "dot",

                route: "/role-categories",
            },
            {
                label: "Roles List",
                icon: "dot",
                route: "/roles",
            },
        ],
    },

    {
        label: "Users",
        icon: "users",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,
        route: "/users",
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],
    },

    {
        label: "Funders",
        icon: "funders",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,
        route: "/funders",
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],
    },
    {
        label: "Department",
        icon: "department",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,
        route: "/departments",
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],
    },
    {
        label: "Zones",
        icon: "zones",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,
        route: "/zones",
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],
    },
    {
        label: "Districts",
        icon: "districts",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,

        route: "/districts",
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],
    },
    {
        label: "Stations",
        icon: "stations",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,
        route: "/stations",
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],
    },
    {
        label: "Staff",
        icon: "team",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,
        route: "/staff",
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],
    },

    {
        label: "Offenses",
        icon: "crimes",
        forwardIcon: false,
        class: "parent-link-with-no-sublinks",

        route: "/offenses",
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],
    },

    {
        label: "Confiscates",
        icon: "confiscated-items",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,

        route: "/confiscates",
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],
    },

    {
        label: "Species",
        icon: "road",
        class: "parent-link-with-sublinks",
        forwardIcon: true,
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],

        items: [
            {
                label: "Categories",
                icon: "dot",
                route: "/species-types",
            },
            {
                label: "Species List",
                icon: "dot",
                route: "/species-list",
            },
        ],
    },

    {
        label: "Areas",
        icon: "location",
        class: "parent-link-with-sublinks",
        forwardIcon: true,
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],

        items: [
            {
                label: "List",
                icon: "dot",
                route: "/area-list",
            },
            {
                label: "Encroached",
                icon: "dot",
                route: "/encroached-areas",
            },
        ],
    },
    {
        label: "Routes",
        icon: "road",
        class: "parent-link-with-sublinks",
        forwardIcon: true,
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],

        items: [
            {
                label: "Types",
                icon: "dot",
                route: "/route-types",
            },
            {
                label: "Route List",
                icon: "dot",
                route: "/route-list",
            },
        ],
    },

    {
        label: "Operations",
        icon: "operations",
        class: "parent-link-with-sublinks",
        forwardIcon: true,
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],

        items: [
            {
                label: "Types",
                icon: "dot",
                route: "/operations-types",
            },
            {
                label: "List",
                icon: "dot",
                route: "/operations-list",
            },
        ],
    },

    {
        label: "Schedules",
        icon: "scheduling",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,

        route: "/schedules",
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],
    },

    {
        label: "Teams",
        icon: "team",
        class: "parent-link-with-sublinks",
        forwardIcon: true,
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],

        items: [
            {
                label: "Team List",
                icon: "dot",
                route: "/teams",
            },
            {
                label: "Team Members",
                icon: "dot",
                route: "/team-members",
            },
            {
                label: "Team Operations",
                icon: "dot",
                route: "/team-operations",
            },
        ],
    },

    {
        label: "Suspects",
        icon: "users",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,
        route: "/suspects",
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],
    },

    {
        label: "Arrests",
        icon: "arrests",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,

        route: "/arrests",
        allowedRoles: [
            "Monitoring and Evaluation Officer",
            "Natural Resources Management Team Leader",
            "Forestry Assistant",
            "District Forestry Officer",
            "Forestry Specialist",
            "admin",
        ],
    },

    {
        label: "Convictions",
        icon: "arrests",
        class: "parent-link-with-no-sublinks",
        forwardIcon: false,

        route: "/convictions",
        allowedRoles: ["admin", "User"],
    },
]);

const dropdownVisibility = ref([]);

const hasAccess = (allowedRoles) => {
    return allowedRoles.some((role) => roles.value.includes(role));
};

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

        if (sidebarRef.value) {
            savedScrollPosition.value = sidebarRef.value.scrollTop;
            localStorage.setItem(
                "sidebarScrollPosition",
                savedScrollPosition.value
            );
        }

        router.visit(item.route, { preserveScroll: false }); // Navigate to the link
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
    if (sidebarRef.value) {
        savedScrollPosition.value = sidebarRef.value.scrollTop;
        localStorage.setItem(
            "sidebarScrollPosition",
            savedScrollPosition.value
        );
    }
    router.visit(subItem.route, { preserveScroll: false });
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
