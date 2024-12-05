<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import { Tabs, TabList, Tab, TabPanels, TabPanel } from 'primevue';

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>

<template>
    <AppLayout title="Profile">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Profile
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto md:py-10 py-0 sm:px-6 lg:px-8">
                <div class="hidden md:block">
                    <Tabs value="0">
                        <TabList>
                            <Tab value="0">User Information</Tab>
                            <Tab value="1">Security</Tab>
                            <Tab value="2">Account</Tab>
                        </TabList>
                        <TabPanels>
                            <TabPanel value="0">
                                <div class="m-10" v-if="$page.props.jetstream.canUpdateProfileInformation">
                                    <UpdateProfileInformationForm :user="$page.props.auth.user" />
                                    <!-- <SectionBorder /> -->
                                </div>
                            </TabPanel>
                            <TabPanel value="1">
                                <div class="m-10" v-if="$page.props.jetstream.canUpdatePassword">
                                    <UpdatePasswordForm class="mt-10 sm:mt-0" />

                                    <!-- <SectionBorder /> -->
                                </div>
                            </TabPanel>
                            <TabPanel value="2">
                                <div class="m-10">
                                    <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                                        <!-- <SectionBorder /> -->

                                        <DeleteUserForm class="mt-10 sm:mt-0" />
                                    </template>
                                </div>
                            </TabPanel>
                        </TabPanels>
                    </Tabs>
                </div>
                

                <div class="md:hidden m-4" v-if="$page.props.jetstream.canUpdateProfileInformation">
                    <UpdateProfileInformationForm :user="$page.props.auth.user" />
                    <SectionBorder />
                </div>

                <div class="md:hidden m-4" v-if="$page.props.jetstream.canUpdatePassword">
                    <UpdatePasswordForm class=" sm:mt-0" />

                    <SectionBorder />
                </div>

                <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                    <!-- <SectionBorder /> -->

                    <DeleteUserForm class="md:hidden mt-10 sm:mt-0" />
                </template>

                

                <!-- <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
                    <TwoFactorAuthenticationForm
                        :requires-confirmation="confirmsTwoFactorAuthentication"
                        class="mt-10 sm:mt-0"
                    />

                    <SectionBorder />
                </div> -->

                <!-- <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0" /> -->

                
            </div>
        </div>
    </AppLayout>
</template>
