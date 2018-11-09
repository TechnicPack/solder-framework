<template>
    <div class="solder-body">
        <main class="solder-content">
            <router-view />
        </main>

        <div class="solder-nav">
            <solder-menu />
        </div>
    </div>
</template>

<script>
    import SolderMenu from "./components/menu.vue";

    export default {
        name: "solder-app",
        components: {
            SolderMenu
        }
    }
</script>

<style>
    .solder-body {
        display: flex;
        flex: 1;
    }

    .solder-content {
        min-height: 100%;
        width: 100%;
        padding: 0 15px;
        margin: 1.5rem 0;
        flex: 1;
    }

    .solder-nav {
        flex: 0 0 24rem;
        order: -1;
    }
</style>