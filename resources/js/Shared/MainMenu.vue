<template>
    <div>
        <div class="mb-4">
            <Link
                class="group flex items-center py-3 px-4"
                :class="isUrl('dashboard') ? 'bg-dark' : 'hover:bg-dark'"
                href="/dashboard"
            >
                <icon
                    name="dashboard"
                    class="mr-2 w-4 h-4"
                    :class="
                        isUrl('dashboard')
                            ? 'fill-white'
                            : 'fill-neutral-400 group-hover:fill-white'
                    "
                />
                <div
                    :class="
                        isUrl('dashboard')
                            ? 'text-white'
                            : 'text-neutral-300 group-hover:text-white'
                    "
                >
                    Dashboard
                </div>
            </Link>
        </div>
        <div class="mb-4">
            <Link
                class="group flex items-center py-3 px-4"
                :class="isUrl('districts') ? 'bg-dark' : 'hover:bg-dark'"
                href="/lenders"
            >
                <icon
                    name="office"
                    class="mr-2 w-4 h-4"
                    :class="
                        isUrl('districts')
                            ? 'fill-white'
                            : 'fill-neutral-400 group-hover:fill-white'
                    "
                />
                <div
                    :class="
                        isUrl('districts')
                            ? 'text-white'
                            : 'text-neutral-300 group-hover:text-white'
                    "
                >
                    Districts
                </div>
            </Link>
        </div>
        <div class="mb-4">
            <Link
                class="group flex items-center py-3 px-4"
                :class="isUrl('stations') ? 'bg-dark' : 'hover:bg-dark'"
                href="/stations"
            >
                <icon
                    name="users"
                    class="mr-2 w-4 h-4"
                    :class="
                        isUrl('stations')
                            ? 'fill-white'
                            : 'fill-neutral-400 group-hover:fill-white'
                    "
                />
                <div
                    :class="
                        isUrl('stations')
                            ? 'text-white'
                            : 'text-neutral-300 group-hover:text-white'
                    "
                >
                    Stations
                </div>
            </Link>
        </div>
        <div class="mb-4">
            <Link
                class="group flex items-center py-3 px-4"
                :class="isUrl('crimes') ? 'bg-dark' : 'hover:bg-dark'"
                href="/crimes"
            >
                <icon
                    name="printer"
                    class="mr-2 w-4 h-4"
                    :class="
                        isUrl('crimes')
                            ? 'fill-white'
                            : 'fill-neutral-400 group-hover:fill-white'
                    "
                />
                <div
                    :class="
                        isUrl('crimes')
                            ? 'text-white'
                            : 'text-neutral-300 group-hover:text-white'
                    "
                >
                    Crimes
                </div>
            </Link>
        </div>
        <div class="mb-4">
            <Link
                class="group flex items-center py-3 px-4"
                :class="isUrl('reports') ? 'bg-dark' : 'hover:bg-dark'"
                href="/arrests"
            >
                <icon
                    name="printer"
                    class="mr-2 w-4 h-4"
                    :class="
                        isUrl('arrests')
                            ? 'fill-white'
                            : 'fill-neutral-400 group-hover:fill-white'
                    "
                />
                <div
                    :class="
                        isUrl('arrests')
                            ? 'text-white'
                            : 'text-neutral-300 group-hover:text-white'
                    "
                >
                    Arrests
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
    background-color: #59873c;
}

.text-gray-900 {
    color: #e0e0e0;
}

.hover\:bg-dark:hover {
    background: linear-gradient(135deg, #273320, #3a5228);
}
</style>
