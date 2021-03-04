<template>
    <form @submit.prevent="submit">
        <label>
                            <textarea
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 shadow-sm focus:border-gray-300 focus:ring focus:ring-gray-200 focus:ring-opacity-50"
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
</template>

<script>
import {Inertia} from '@inertiajs/inertia'
import JetButton from '@/Jetstream/Button'

export default {
    components: {
        JetButton,
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
    }
}
</script>
