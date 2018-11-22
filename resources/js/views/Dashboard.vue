<template>
    <div class="container">
        <div class="card card-default">
            <div class="card-header">Create Content</div>
            <div class="card-body">

                <nav>
                    <div class="nav d-flex flex-nowrap" id="nav-tab" role="tablist">
                        <!-- Modpack -->
                        <button class="btn btn-content shadow-none d-flex flex-column align-items-center justify-content-between"
                                href="#modpack" aria-controls="modpack" aria-selected="false" role="tab" data-toggle="tab"
                        >
                            <svg class="fill-current dim-50 icon-60" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M0 2C0 .9.9 0 2 0h16a2 2 0 0 1 2 2v2H0V2zm1 3h18v13a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V5zm6 2v2h6V7H7z"/></svg>
                            <span>Modpack</span>
                        </button>

                        <div class="divider ml-4"></div>

                        <!-- Mod -->
                        <button class="btn btn-content shadow-none d-flex flex-column align-items-center justify-content-between ml-4"
                                href="#mod" aria-controls="mod" aria-selected="false" role="tab" data-toggle="tab"
                        >
                            <svg class="fill-current dim-50 icon-60" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M20 14v4a2 2 0 0 1-2 2h-4v-2a2 2 0 1 0-4 0v2H6a2 2 0 0 1-2-2v-4H2a2 2 0 1 1 0-4h2V6c0-1.1.9-2 2-2h4V2a2 2 0 1 1 4 0v2h4a2 2 0 0 1 2 2v4h-2a2 2 0 1 0 0 4h2z"/></svg>
                            <span>Minecraft Mod</span>
                        </button>
                        
                        <!-- Texture Pack -->
                        <button class="btn btn-content shadow-none d-flex flex-column align-items-center justify-content-between ml-4"
                                href="#texture" aria-controls="texture" aria-selected="false" role="tab" data-toggle="tab"
                        >
                            <svg class="fill-current dim-50 icon-60" viewBox="0 0 14 20" xmlns="http://www.w3.org/2000/svg"><path d="M8 9.27V0l6 11-4 6H4l-4-6L6 0v9.27a2 2 0 1 0 2 0zM3 18h8v2H3v-2z"/></svg>
                            <span>Texture Pack</span>
                        </button>

                        <!-- Worlds -->
                        <button class="btn btn-content shadow-none d-flex flex-column align-items-center justify-content-between ml-4"
                                href="#worlds" aria-controls="worlds" aria-selected="false" role="tab" data-toggle="tab"
                        >
                            <svg class="fill-current dim-50 icon-60" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 20C4.477 20 0 15.523 0 10S4.477 0 10 0s10 4.477 10 10-4.477 10-10 10zm2-2.25a8 8 0 0 0 4-2.46V9a2 2 0 0 1-2-2V3.07a7.95 7.95 0 0 0-3-1V3a2 2 0 0 1-2 2v1a2 2 0 0 1-2 2v2h3a2 2 0 0 1 2 2v5.75zm-4 0V15a2 2 0 0 1-2-2v-1h-.5A1.5 1.5 0 0 1 4 10.5V8H2.25A8.01 8.01 0 0 0 8 17.75z"/></svg>
                            <span>Worlds</span>
                        </button>

                        <!-- Import -->
                        <button class="btn btn-content shadow-none d-flex flex-column align-items-center justify-content-between ml-4"
                                href="#import" aria-controls="import" aria-selected="false" role="tab" data-toggle="tab"
                        >
                            <svg class="fill-current dim-50 icon-60" viewBox="0 0 20 18" xmlns="http://www.w3.org/2000/svg"><path d="M13 8v6H7V8H2l8-8 8 8h-5zM0 16h20v2H0v-2z"/></svg>
                            <span>Import Mod</span>
                        </button>
                    </div>
                </nav>

                <div class="tab-content">
                    <!-- Modpack -->
                    <div role="tabcard" class="tab-pane mt-5" id="modpack">
                        <create-modpack></create-modpack>
                    </div>

                    <!-- Mod -->
                    <div role="tabcard" class="tab-pane mt-5" id="mod">
                        <create-mod></create-mod>
                    </div>

                    <!-- Texture pack -->
                    <div role="tabcard" class="tab-pane mt-5 text-center" id="texture">
                        <h1 class="display-4">Soon &trade;</h1>
                    </div>

                    <!-- Worlds -->
                    <div role="tabcard" class="tab-pane mt-5 text-center" id="worlds">
                        <h1 class="display-4">Soon &trade;</h1>
                    </div>

                    <!-- Import -->
                    <div role="tabcard" class="tab-pane mt-5 text-center" id="import">
                        <h1 class="display-4">Soon &trade;</h1>
                    </div>
                </div>
            </div>
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

<style>
    .btn-content {
        border: 2px solid #d7d7d7;
        padding: 20px 20px 10px;
        height: 132px;
        width: 100%;
        color: #424c54;
        background-color: transparent;
    }

    .btn-content.active {
        border-color: #41A2DB;
    }

    .btn-content:hover {
        border-color: #90979b;
    }

    .divider {
        border-left: 2px solid #d7d7d7;
    }

    .fill-current {
        fill: currentColor;
    }

    .dim-50 {
        opacity: 0.5;
    }

    .icon-60 {
        height: 60px;
    }
</style>