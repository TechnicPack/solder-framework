<template>
    <div class="card card-default">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <span>Mods</span>
                <div class="input-group input-group-sm w-25">
                    <input class="form-control py-2 border-right-0 border shadow-none" type="search" value="search" v-model="search">
                    <span class="input-group-append">
                    <div class="input-group-text bg-white border"><i class="fa fa-search"></i></div>
                </span>
                </div>
            </div>
        </div>

        <div class="table-responsive" v-if="filteredDependencies.length">
            <table class="table table-valign-middle mb-0">
                <thead>
                <th>Mod</th>
                <th>Version</th>
                <th>&nbsp;</th>
                </thead>

                <tbody>
                <tr  v-for="dependency in filteredDependencies">
                    <td>
                        <router-link
                                :to="{ name: 'mod', params: { modId: dependency.mod.id }}"
                        >
                            {{ dependency.mod.name }}
                        </router-link>
                    </td>
                    <td>
                        {{ dependency.version.tag }}
                    </td>

                    <!-- Edit Button -->
                    <td class="td-fit">
                        <button class="btn btn-sm btn-outline-danger" @click="removeDependency(dependency.id)"
                        data-toggle="tooltip" title="Remove Mod">
                            <i class="fa fa-times"></i>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="card-body text-center text-muted" v-else>
            <svg class="fill-current dim-25 m-3" height="40" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.4 18H20v2H0v-2h2.6L8 0h4l5.4 18zm-3.2-4H5.8l-1.2 4h10.8l-1.2-4zm-2.4-8H8.2L7 10h6l-1.2-4z"></path>
            </svg>
            <p class="lead">No dependencies matched the given criteria.</p>
        </div>
    </div>
</template>

<script>
    import Dependency from "../../models/Dependency";

    export default {
        props: ['dependencies'],

        data() {
            return {
                search: '',
            }
        },

        computed: {
            filteredDependencies() {
                var self=this;
                return this.dependencies.filter(function(dependency) {
                    return dependency.mod.name.toLowerCase().indexOf(self.search.toLowerCase())>=0;
                });
            }
        },

        methods: {
            async removeDependency(id) {
                let dependency = new Dependency({id: id});
                await dependency.delete();
                Bus.$emit('updateBuild');
            }
        }
    }
</script>


<style>
    .fill-current {
        fill: currentColor;
    }

    .dim-25 {
        opacity: 0.25;
    }
</style>
