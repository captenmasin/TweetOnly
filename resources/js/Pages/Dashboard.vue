<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form @submit.prevent="submit">
                        <label>
                            <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" v-model="form.tweet_content" :error="form.errors.tweet_content" ></textarea>
                        </label>
                        <br>
                        <input type="file" @change="selectImage" ref="image" multiple />
                        <br><br>
                        {{ form }}
                        <br><br>
                        <jet-button class="ml-2">
                            Tweet it
                        </jet-button>
                    </form>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetButton from '@/Jetstream/Button'

export default {
    components: {
        AppLayout,
        JetButton,
    },
    props: {
        errors: Object
    },
    data () {
        return {
            form: this.$inertia.form({
                tweet_content: 'test',
                image: null
            })
        }
    },
    methods: {
        submit () {
            this.form.post(route('tweet'))
        },
        selectImage () {
            // const reader = new FileReader()
            // reader.onload = (e) => {
            //     this.photoPreview = e.target.result
            // }
            // reader.readAsDataURL(this.$refs.image.files)
            if (this.$refs.image && typeof this.$refs.image.files !== 'undefined') {
                this.form.image = this.$refs.image.files
            }
        },
    }
}
</script>
