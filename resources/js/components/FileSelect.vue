<template>
    <label class="file-select">
        <div class="btn">
            <slot></slot>
        </div>
        <input type="file" @change="handleFileChange"/>
    </label>
</template>

<script>
    export default {
        props: {
            value: File
        },

        methods: {
            handleFileChange(e) {
                this.$emit('input', e.target.files[0])
            }
        }
    }
</script>

<style scoped>
    .file-select > input[type="file"] {
        display: none;
    }
</style>