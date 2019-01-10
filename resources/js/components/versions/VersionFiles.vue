<template>
    <div>
        <div class="card card-default">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-baseline">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0 p-0">
                            <li class="breadcrumb-item active"><i class="fa fa-home" aria-hidden="true"></i></li>
                        </ol>
                    </nav>

                    <div>
                        <button type="button" class="btn btn-sm btn-secondary disabled"><i class="fa fa-fw fa-folder-o" aria-hidden="true"></i> New Folder</button>
                        <button type="button" class="btn btn-sm btn-secondary disabled"><i class="fa fa-fw fa-file-o" aria-hidden="true"></i> New File</button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-borderless mb-0">
                    <thead>
                    <th>Name</th>
                    <th>Size</th>
                    <th>Kind</th>
                    </thead>
                    <tbody>
                    <tr v-for="file in files">
                        <td>
                            <span v-for="space in spaces(file)">&emsp;</span>
                            <i class="fa fa-fw" :class="[ file.type === 'dir' ? 'fa-folder-o': 'fa-file-o' ]" aria-hidden="true"></i>
                            {{ file.basename }}
                        </td>
                        <td>
                            {{ file.size }}
                        </td>
                        <td>
                            {{ file.extension }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!--<div class="card-body text-center text-muted" v-else>-->
                <!--<svg class="fill-current dim-25 m-3" height="40" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">-->
                    <!--<path d="M17.4 18H20v2H0v-2h2.6L8 0h4l5.4 18zm-3.2-4H5.8l-1.2 4h10.8l-1.2-4zm-2.4-8H8.2L7 10h6l-1.2-4z"></path>-->
                <!--</svg>-->
                <!--<p class="lead">No dependencies matched the given criteria.</p>-->
            <!--</div>-->
        </div>
    </div>
</template>

<script>
    import Solder from "../../solder";

    export default {
        props: ['version'],

        /**
         * The component's data.
         */
        data() {
            return {
                files: [],
            }
        },

        /**
         * Watch for changes.
         */
        watch: {
            version: function() {
                this.getFiles();
            }
        },

        methods: {
            /**
             * Load the version files.
             */
            getFiles() {
                axios
                    .get(Solder.apiBaseUrl + `/versions/${this.version.id}/package`)
                    .then(response => (this.files = response.data));
            },

            spaces(file) {
                return file.path.split('/').length - 1;
            }
        }
    }
</script>

<style scoped>
    .table td {
        padding: 0.5rem 1.25rem;
    }
</style>