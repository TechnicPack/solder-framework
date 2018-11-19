<template>
    <div class="container">
        <div class="row">
            <div class="col"><create-modpack></create-modpack></div>
            <div class="col"><create-mod></create-mod></div>
        </div>
        <active-modpacks></active-modpacks>
    </div>
</template>

<script>
    import ActiveModpacks from '../components/dashboard/ActiveModpacks';
    import CreateModpack from '../components/dashboard/CreateModpack';
    import CreateMod from '../components/dashboard/CreateMod';

    export default {
        components: {
            ActiveModpacks,
            CreateModpack,
            CreateMod,
        }
    }
</script>
