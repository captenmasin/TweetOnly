<template>
    <div>
        <page-title>
            Profile
        </page-title>
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                <delete-user-form class="mt-10 sm:mt-0"/>
            </template>
        </div>
    </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import DeleteUserForm from './DeleteUserForm'
import JetSectionBorder from '@/Jetstream/SectionBorder'
import LogoutOtherBrowserSessionsForm from './LogoutOtherBrowserSessionsForm'
import TwoFactorAuthenticationForm from './TwoFactorAuthenticationForm'
import UpdatePasswordForm from './UpdatePasswordForm'
import UpdateProfileInformationForm from './UpdateProfileInformationForm'
import PageTitle from "@/Components/PageTitle";

export default {
    props: ['sessions'],

    components: {
        PageTitle,
        AppLayout,
        DeleteUserForm,
        JetSectionBorder,
        LogoutOtherBrowserSessionsForm,
        TwoFactorAuthenticationForm,
        UpdatePasswordForm,
        UpdateProfileInformationForm,
    },
}
</script>
