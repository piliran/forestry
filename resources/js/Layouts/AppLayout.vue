<script setup>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Banner from "@/Components/Banner.vue";
// import Dropdown from '@/Components/Dropdown.vue';
// import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import MainMenu from "@/Shared/MainMenu.vue";
import Dropdown from "@/Shared/Dropdown.vue";
import Icon from "@/Shared/Icon.vue";
import Logo from "@/Shared/Logo.vue";
import FlashMessages from "@/Shared/FlashMessages.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

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
    router.post(route("logout"));
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
                    <!-- <div
                        class="flex items-center justify-between px-6 py-4 bg-green-900 md:flex-shrink-0 md:justify-center md:w-56"
                    > -->
                    <div
                        class="flex items-center justify-between px-6 py-4 bg-dark md:flex-shrink-0 md:justify-center md:w-56"
                    >
                        <Link class="mt-1 flex" href="/">
                            <logo class="fill-white" width="120" height="28" />
                        </Link>
                        <dropdown class="md:hidden" placement="bottom-end">
                            <template #default>
                                <svg
                                    class="w-6 h-6 fill-white"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"
                                    />
                                </svg>
                            </template>
                            <template #dropdown>
                                <!-- <div
                                    class="mt-2 px-8 py-4 bg-green-800 rounded shadow-lg"
                                > -->
                                <div
                                    class="mt-2 px-8 py-4 bg-dark rounded shadow-lg"
                                >
                                    <main-menu />
                                </div>
                            </template>
                        </dropdown>
                    </div>

                    <div
                        class="md:text-md flex items-center justify-between p-4 w-full text-sm bg-white border-b md:px-12 md:py-0"
                    >
                        <div class="mr-4 mt-1">
                            <!-- {{ $page.props.auth.user.name }} -->
                        </div>
                        <Dropdown class="mt-1" placement="bottom-end">
                            <template #default>
                                <div
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
                                        class="block px-6 py-2 hover:text-white hover:bg-lime-900"
                                        :href="route('profile.show')"
                                        >Profile</Link
                                    >

                                    <button
                                        type="submit"
                                        @click="logout"
                                        class="block px-6 py-2 hover:text-white hover:bg-lime-900 width-full"
                                    >
                                        Log Out
                                    </button>
                                </div>
                            </template>
                        </Dropdown>
                    </div>
                </div>

                <div class="md:flex md:flex-grow md:overflow-hidden">
                    <!-- <main-menu
                        class="hidden flex-shrink-0 p-12 w-56 bg-green-800 overflow-y-auto md:block"
                    /> -->
                    <main-menu
                        class="hidden flex-shrink-0 py-4 px-4 w-56 bg-dark overflow-y-auto md:block"
                    />
                    <div
                        class="px-4 py-8 md:flex-1 md:p-12 md:overflow-y-auto"
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
    /* background: linear-gradient(135deg, #273320, #3a5228); */
    background: linear-gradient(135deg, #1c3d19, #2b5c23);
}

.text-gray-900 {
    color: #333;
}

.shadow-xl {
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.hover\:bg-green-500:hover {
    /* background-color: #4caf50; */
    background-color: linear-gradient(135deg, #273320, #3a5228);
}
.hover\:text-white:hover {
    color: #fff;
}

.text-gray-900 {
    color: #2e2e2e;
}
</style>
