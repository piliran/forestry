<template>
    <div>
        <div class="mb-4">
            <Link
                class="group flex items-center py-2 px-4"
                :class="
                    isUrl('dashboard') ? 'bg-white rounded-xl' : 'hover:bg-dark'
                "
                href="/dashboard"
            >
                <icon
                    name="dashboard"
                    class="mr-2 w-4 h-4"
                    :class="
                        isUrl('dashboard')
                            ? 'fill-green-900'
                            : 'fill-white group-hover:fill-green-900'
                    "
                />
                <div
                    :class="
                        isUrl('dashboard')
                            ? 'text-green-900'
                            : 'text-white group-hover:text-green-900'
                    "
                >
                    Dashboard
                </div>
            </Link>
        </div>
        <div class="mb-4">
            <Link
                class="group flex items-center py-2 px-4"
                :class="
                    isUrl('districts') ? 'bg-white rounded-xl' : 'hover:bg-dark'
                "
                href="/districts"
            >
                <icon
                    name="office"
                    class="mr-2 w-4 h-4"
                    :class="
                        isUrl('districts')
                            ? 'fill-green-900'
                            : 'fill-white group-hover:fill-green-900'
                    "
                />
                <div
                    :class="
                        isUrl('districts')
                            ? 'text-green-900'
                            : 'text-white group-hover:text-green-900'
                    "
                >
                    Districts
                </div>
            </Link>
        </div>
        <div class="mb-4">
            <Link
                class="group flex items-center py-2 px-4"
                :class="
                    isUrl('stations') ? 'bg-white rounded-xl' : 'hover:bg-dark'
                "
                href="/stations"
            >
                <icon
                    name="users"
                    class="mr-2 w-4 h-4"
                    :class="
                        isUrl('stations')
                            ? 'fill-green-900'
                            : 'fill-white group-hover:fill-green-900'
                    "
                />
                <div
                    :class="
                        isUrl('stations')
                            ? 'text-green-900'
                            : 'text-white group-hover:text-green-900'
                    "
                >
                    Stations
                </div>
            </Link>
        </div>
        <div class="mb-4">
            <Link
                class="group flex items-center py-2 px-4"
                :class="
                    isUrl('crimes') ? 'bg-white rounded-xl' : 'hover:bg-dark'
                "
                href="/crimes"
            >
                <icon
                    name="printer"
                    class="mr-2 w-4 h-4"
                    :class="
                        isUrl('crimes')
                            ? 'fill-green-900'
                            : 'fill-white group-hover:fill-green-900'
                    "
                />
                <div
                    :class="
                        isUrl('crimes')
                            ? 'text-green-900'
                            : 'text-white group-hover:text-green-900'
                    "
                >
                    Crimes
                </div>
            </Link>
        </div>
        <div class="mb-4">
            <Link
                class="group flex items-center py-2 px-4"
                :class="
                    isUrl('arrests') ? 'bg-white rounded-xl' : 'hover:bg-dark'
                "
                href="/arrests"
            >
                <icon
                    name="printer"
                    class="mr-2 w-4 h-4"
                    :class="
                        isUrl('arrests')
                            ? 'fill-green-900'
                            : 'fill-white group-hover:fill-green-900'
                    "
                />
                <div
                    :class="
                        isUrl('arrests')
                            ? 'text-green-900'
                            : 'text-white group-hover:text-green-900'
                    "
                >
                    Arrests
                </div>
            </Link>
        </div>

        <div class="mb-4">
            <Link
                class="group flex items-center py-2 px-4"
                :class="
                    isUrl('confiscicates')
                        ? 'bg-white rounded-xl'
                        : 'hover:bg-dark'
                "
                href="/confiscicates"
            >
                <icon
                    name="printer"
                    class="mr-2 w-4 h-4"
                    :class="
                        isUrl('confiscicates')
                            ? 'fill-green-900'
                            : 'fill-white group-hover:fill-green-900'
                    "
                />
                <div
                    :class="
                        isUrl('confiscicates')
                            ? 'text-green-900'
                            : 'text-white group-hover:text-green-900'
                    "
                >
                    Confiscicates
                </div>
            </Link>
        </div>

        <div class="mb-4">
            <Link
                class="group flex items-center py-2 px-4"
                :class="
                    isUrl('encroached-areas')
                        ? 'bg-white rounded-xl'
                        : 'hover:bg-dark'
                "
                href="/encroached-areas"
            >
                <icon
                    name="printer"
                    class="mr-2 w-4 h-4"
                    :class="
                        isUrl('encroached-areas')
                            ? 'fill-green-900'
                            : 'fill-white group-hover:fill-green-900'
                    "
                />
                <div
                    :class="
                        isUrl('encroached-areas')
                            ? 'text-green-900'
                            : 'text-white group-hover:text-green-900'
                    "
                >
                    Ancroached Areas
                </div>
            </Link>
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import Icon from "@/Shared/Icon.vue";

export default {
    components: {
        Icon,
        Link,
    },
    methods: {
        isUrl(...urls) {
            let currentUrl = this.$page.url.substr(1);
            if (urls[0] === "") {
                return currentUrl === "";
            }
            return urls.filter((url) => currentUrl.startsWith(url)).length;
        },
    },
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
    /* background: linear-gradient(135deg, #273320, #3a5228); */
    /* background: linear-gradient(135deg, #1c3d19, #b8d9b3); */
    background: #ffffff;
    border-radius: 10px;
}
</style>
