<template>
    <button class="rounded-full outline-none focus:outline-none hover:bg-gray-200 p-1 transition-all"
            :class="darkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-200'"
            @click="toggleDarkMode">
        <icon-sun class="w-5 h-5 text-white" v-if="darkMode"/>
        <icon-moon class="w-5 h-5" v-else/>
    </button>
</template>

<script>
import IconSun from "@/Components/Icons/IconSun";
import IconMoon from "@/Components/Icons/IconMoon";

export default {
    components: {IconMoon, IconSun},
    data() {
        return {
            darkMode: false,
        }
    },
    methods: {
        darkModeOn() {
            document.querySelector('body').classList.add('dark')
            this.darkMode = true
        },
        darkModeOff() {
            document.querySelector('body').classList.remove('dark')
            this.darkMode = false
        },
        toggleDarkMode() {
            if (this.darkMode || document.querySelector('body').classList.contains('dark')) {
                axios.post(route('settings', {user: this.$page.props.user.id}), {
                    setting: 'darkMode',
                    value: false
                }).then(response => {
                    this.darkModeOff()
                })
            } else {
                axios.post(route('settings', {user: this.$page.props.user.id}), {
                    setting: 'darkMode',
                    value: true
                }).then(response => {
                    this.darkModeOn()
                })
            }
        },
    },
    mounted() {
        if (document.querySelector('body').classList.contains('dark')) {
            this.darkMode = true;
        } else {
            this.darkMode = false
        }
    }
}
</script>
