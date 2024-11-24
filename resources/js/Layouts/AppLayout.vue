<script setup>
import { ref, computed, onMounted } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Banner from "@/Components/Banner.vue";
import Divider from "primevue/divider";

import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import MainMenu from "@/Shared/MainMenu.vue";
import Dropdown from "@/Shared/Dropdown.vue";
import Icon from "@/Shared/Icon.vue";
import Logo from "@/Shared/Logo.vue";
import FlashMessages from "@/Shared/FlashMessages.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import Breadcrumb from "@/Components/Breadcrumb.vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
const isSidebarVisible = ref(true); // State to track sidebar visibility

const sidebarRef = ref(null);
const savedScrollPosition = ref(0);

// Save the scroll position before navigating
const handleLinkClick = () => {
    if (sidebarRef.value) {
        savedScrollPosition.value = sidebarRef.value.scrollTop;
        localStorage.setItem(
            "sidebarScrollPosition",
            savedScrollPosition.value
        );
    }
};

// Restore the scroll position after navigation
onMounted(() => {
    const storedPosition = localStorage.getItem("sidebarScrollPosition");
    if (storedPosition && sidebarRef.value) {
        sidebarRef.value.scrollTop = parseInt(storedPosition, 10);
    }
});

const switchToTeam = (team) => {
    router.put(
        route("current-team.update"),
        {
            team_id: team.id,
        },
        {
            preserveState: false,
        }
    );
};

const logout = () => {
    sessionStorage.removeItem("dropdownVisibility");
    router.post(route("logout"));
};

// Function to toggle sidebar visibility
const toggleSidebar = () => {
    isSidebarVisible.value = !isSidebarVisible.value;
};
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />
        <div id="dropdown"></div>
        <div class="md:flex md:flex-col min-h-screen bg-gray-100">
            <div class="md:flex md:flex-col md:h-screen">
                <div class="md:flex md:flex-shrink-0">
                    <div
                        v-show="isSidebarVisible"
                        class="flex items-center border-b-2 border-r-2 border-transparent lg:border-white md:border-white justify-between px-0 py-1 bg-dark md:flex-shrink-0 md:w-56"
                    >
                        <div class="block w-full px-6">
                            <Link class="mb-1 py-1 flex" href="/">
                                <logo />
                            </Link>
                        </div>

                        <dropdown class="md:hidden px-6" placement="bottom-end">
                            <template #default>
                                <svg
                                    class="w-6 h-6 fill-white cursor-pointer"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"
                                    />
                                </svg>
                            </template>
                            <template #dropdown>
                                <div
                                    class="mt-2 px-8 py-4 bg-dark rounded shadow-lg overflow-y-auto"
                                >
                                    <main-menu />
                                </div>
                            </template>
                        </dropdown>

                        <dropdown class="md:hidden px-6" placement="bottom-end">
                            <template #default>
                                <div
                                    v-if="
                                        $page.props.jetstream
                                            .managesProfilePhotos
                                    "
                                >
                                    <img
                                        class="w-10 h-10 rounded-full me-3 object-cover"
                                        :src="
                                            $page.props.auth.user
                                                .profile_photo_url
                                        "
                                        :alt="$page.props.auth.user.name"
                                    />
                                </div>
                                <svg
                                    v-else
                                    class="w-6 h-6 fill-white cursor-pointer"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M10 10c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-3.333 0-10 1.667-10 5v3h20v-3c0-3.333-6.667-5-10-5z"
                                    />
                                </svg>
                            </template>
                            <template #dropdown>
                                <div
                                    class="mt-2 py-2 text-sm bg-white rounded shadow-xl"
                                >
                                    <Link
                                        class="block px-6 py-2 hover:text-white hover:bg-green-900"
                                        :href="route('profile.show')"
                                        >Profile</Link
                                    >
                                    <button
                                        type="submit"
                                        @click="logout"
                                        class="block px-6 py-2 hover:text-white hover:bg-green-900 width-full"
                                    >
                                        Log Out
                                    </button>
                                </div>
                            </template>
                        </dropdown>
                    </div>

                    <div
                        class="hidden md:text-md md:flex items-center justify-between p-1 w-full text-sm border-b md:px-12 md:py-3 min-h-12"
                        style="background-color: #007e4e"
                    >
                        <div class="mr-4 my-2">
                            <!-- Icon for toggling sidebar on medium and large devices -->
                            <button
                                class="hidden md:block fill-white text-white px-1"
                                @click="toggleSidebar"
                                aria-label="Toggle Sidebar"
                            >
                                <icon
                                    :name="isSidebarVisible ? 'menu' : 'menu'"
                                    class="w-6 h-6"
                                />
                            </button>
                        </div>
                        <Dropdown class="mt-1" placement="bottom-end">
                            <template #default>
                                <div
                                    v-if="
                                        $page.props.jetstream
                                            .managesProfilePhotos
                                    "
                                    class="shrink-0 me-3"
                                >
                                    <img
                                        class="h-10 w-10 rounded-full object-cover"
                                        :src="
                                            $page.props.auth.user
                                                .profile_photo_url
                                        "
                                        :alt="$page.props.auth.user.name"
                                    />
                                </div>
                                <div
                                    v-else
                                    class="group flex items-center cursor-pointer select-none"
                                >
                                    <div
                                        class="mr-1 text-gray-700 group-hover:text-lime-800 focus:text-lime-900 whitespace-nowrap"
                                    >
                                        <span>{{
                                            $page.props.auth.user.name
                                        }}</span>
                                    </div>
                                    <icon
                                        class="w-5 h-5 fill-gray-700 group-hover:fill-lime-800 focus:fill-lime-900"
                                        name="cheveron-down"
                                    />
                                </div>
                            </template>
                            <template #dropdown>
                                <div
                                    class="mt-2 py-2 text-sm bg-white rounded shadow-xl"
                                >
                                    <Link
                                        class="block px-6 py-2 hover:text-white hover:bg-green-900"
                                        :href="route('profile.show')"
                                        >Profile</Link
                                    >
                                    <button
                                        type="submit"
                                        @click="logout"
                                        class="block px-6 py-2 hover:text-white hover:bg-green-900 width-full"
                                    >
                                        Log Out
                                    </button>
                                </div>
                            </template>
                        </Dropdown>
                    </div>
                </div>

                <div class="md:flex md:flex-grow md:overflow-hidden">
                    <main-menu
                        v-show="isSidebarVisible"
                        class="hidden flex-shrink-0 py-4 px-4 w-56 bg-dark overflow-y-auto md:block"
                        scroll-region
                    />

                    <div
                        class="px-0 py-8 mx-2 md:flex-1 md:p-10 md:overflow-y-auto"
                        scroll-region
                    >
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.bg-dark {
    /* background: linear-gradient(135deg, #007e4e, rgb(0, 0, 0)); */
    /* background: linear-gradient(135deg, #1c3d19, #2b5c23); */
    background: #007e4e;
}

.text-gray-900 {
    color: #333;
}

.shadow-xl {
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.hover\:bg-green-500:hover {
    /* background-color: #ebe300; */
    background-color: linear-gradient(135deg, #273320, #3a5228);
}
.hover\:text-white:hover {
    color: #fff;
}

.text-gray-900 {
    color: #2e2e2e;
}
</style>
