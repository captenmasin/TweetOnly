<template>
    <div>
        <page-title>
            Dashboard
        </page-title>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-700 dark:text-white overflow-hidden shadow sm:rounded-lg p-4 mb-4">
                    <create-tweet/>
                </div>
                <div class="bg-white dark:bg-gray-700 dark:text-white overflow-hidden shadow sm:rounded-lg p-4 mb-4">
                    <h3 class="font-bold text-2xl">Tweets</h3>
                    <div v-for="(tweet, index) in tweets" :key="index" class="py-4 border-b border-gray-200">
                        <single-tweet :tweet="tweet"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import PageTitle from "@/Components/PageTitle"
import SingleTweet from "@/Components/SingleTweet";
import CreateTweet from "@/Components/CreateTweet";


export default {
    components: {
        CreateTweet,
        SingleTweet,
        PageTitle,
        AppLayout,
    },
    props: {
        errors: Object,
        tweets: Array
    },
}
</script>
