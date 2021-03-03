<template>
    <div>
        <page-title>
            Dashboard
        </page-title>

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
                        <div class="flex space-x-4">
                            <div v-for="(image, index) in imagePreviews">
                                <img :src="image" class="w-24 h-24" :key="index" :alt="'Image preview: ' + index"/>
                            </div>
                        </div>
                        <br><br>
                        <div class="flex justify-end">
                            <jet-button>
                                Tweet it
                            </jet-button>
                        </div>
                    </form>
                </div>
                <div class="bg-white overflow-hidden shadow sm:rounded-lg p-4 mb-4">
                    <h3 class="font-bold text-2xl">Tweets</h3>
                    <div v-for="(tweet, index) in tweets" :key="index" class="py-4 border-b border-gray-200">
                        {{ tweet.text }} - Fav: {{ tweet.favorite_count }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetButton from '@/Jetstream/Button'
import PageTitle from "@/Components/PageTitle"
import {Inertia} from '@inertiajs/inertia'


export default {
    components: {
        PageTitle,
        AppLayout,
        JetButton,
    },
    props: {
        errors: Object,
        tweets: Array
    },
    data() {
        return {
            imagePreviews: [],
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
            this.imagePreviews = [];
            if (this.$refs.image && typeof this.$refs.image.files !== 'undefined') {
                this.form.image = this.$refs.image.files

                for (var i = 0, len = this.$refs.image.files.length; i < len; i++) {
                    const reader = new FileReader()
                    reader.readAsDataURL(this.$refs.image.files[i])
                    reader.onload = (e) => {
                        this.imagePreviews.push(e.target.result)
                    }
                }
            }
        },
    },
}
</script>
