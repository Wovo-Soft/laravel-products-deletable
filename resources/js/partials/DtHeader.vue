<template>
    <div class="row">
        <div class="col-md-3">
            <b-input-group :size="perPageSize" prepend="Per Page">
                <b-form-select class="form-control" v-model="datatable.per_page" :options="range()">
                </b-form-select>
            </b-input-group>
        </div>
        <div class="col-md-5">
            <b-form-input
                type="search"
                v-model="search"
                :size="searchSize"
                @keydown.esc="()=>{$event.target.value='';$emit('input','')}"
                @change="datatable.current_page=1"
                @input="$emit('input', search)"
                placeholder="Type and Hit Enter to Search the table, ESC to clear"/>
        </div>
        <div class="col-md-4 text-right">
            <b-button-group :size="customButtonSize" v-if="custom_buttons && custom_buttons.length">
                <template v-for="(btn,btn_key) in custom_buttons">
                    <b-button
                        v-if="btn.to"
                        :variant="btn.variant?btn.variant:'dark'"
                        :to="btn.to"
                        exact exact-active-class="active"
                        :key="btn_key">
                        <i :class="btn.icon" v-if="btn.icon"></i> {{btn.text}}
                    </b-button>
                    <b-button
                        v-else
                        :variant="btn.variant?btn.variant:'dark'"
                        :key="btn_key"
                        @click="btn.method">
                        <i :class="btn.icon" v-if="btn.icon"></i> {{btn.text}}
                    </b-button>
                </template>
            </b-button-group>
            <b-dropdown text="Columns" :size="columnDdSize" right class="columns-dropdown p-0" variant="primary">
                <ul class="list-group">
                    <li class="list-group-item" v-for="(field,key) in fields" :key="key">
                        <b-form-checkbox v-model="field.visible" :value="true"
                                         :uncheched-value="false"
                                         @change="changeVisibility(field,key)"
                        >
                            {{startCase(field.label || field.key)}}
                        </b-form-checkbox>
                    </li>
                </ul>
            </b-dropdown>
        </div>
    </div>
</template>

<script>
    import {range, changeVisibility, startCase} from "./datatable";

    export default {
        props: {
            fields: {
                type: Array,
                default: () => []
            },
            datatable: Object,
            value: {
                type: String,
                default: ""
            },
            custom_buttons: {
                type: Array,
                default: () => []
            },
            columnDdSize: {
                type: String,
                default: 'sm'
            },
            customButtonSize: {
                type: String,
                default: 'sm'
            },
            searchSize: {
                type: String,
                default: 'sm'
            },
            perPageSize: {
                type: String,
                default: 'sm'
            }
        },
        data: () => {
            return {
                search: ''
            }
        },

        methods: {
            changeVisibility,
            startCase,
            range
        }
    }
</script>

<style lang="scss">
    .columns-dropdown {
        .dropdown-menu {
            padding: 0 !important;
            border: 0 !important;
        }

        .list-group-item {
            label {
                cursor: pointer;
            }
        }
    }
</style>
