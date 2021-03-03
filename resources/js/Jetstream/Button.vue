<template>
    <button :type="type" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                default: 'submit',
            },
        }
    }
</script>
