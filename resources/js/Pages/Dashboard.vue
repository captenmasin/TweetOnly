<template>
    <app-layout>
        <template #header>
            <page-title>
                Dashboard
            </page-title>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow sm:rounded-lg p-4 mb-4">
                    <form @submit.prevent="submit">
                        <label>
                            <textarea
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-300 focus:ring focus:ring-gray-200 focus:ring-opacity-50"
                                v-model="form.tweet_content" :error="form.errors.tweet_content"></textarea>
                        </label>
                        <br>
                        <input type="file" id="images" @change="selectImage" ref="image" multiple class="hidden"/>
                        <label for="images">
                            Select images!
                        </label>
                        <br>
                        {{ form.image }}
                        <br><br>
                        <div class="flex justify-end">
                            <jet-button>
                                Tweet it
                            </jet-button>
                        </div>
                    </form>
                </div>
                <div class="bg-white overflow-hidden shadow sm:rounded-lg p-4 mb-4">
                    Tweets:<br>
                    <div v-for="(tweet, index) in tweets" :key="index" class="py-2 border-b border-gray-200">
                        {{ tweet.text }} - Fav: {{ tweet.favorite_count }}
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetButton from '@/Jetstream/Button'
import PageTitle from "@/Components/PageTitle";

export default {
    components: {
        PageTitle,
        AppLayout,
        JetButton,
    },
    props: {
        errors: Object
    },
    data() {
        return {
            tweets: [],
            form: this.$inertia.form({
                tweet_content: 'test',
                image: null
            })
        }
    },
    methods: {
        submit() {
            this.form.post(route('tweet'))
        },
        selectImage() {
            if (this.$refs.image && typeof this.$refs.image.files !== 'undefined') {
                this.form.image = this.$refs.image.files
            }
        },
        getTweets(){
            axios.get(route('tweets'), {}).then(response => {
                this.tweets = response.data
            })
        }
    },
    mounted() {
        this.getTweets()
    }
}
</script>
