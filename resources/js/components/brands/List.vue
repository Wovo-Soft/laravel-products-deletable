<template>
    <div>
        <dt-table :title="title" v-model="search" :fields="fields" :datatable="datatable"
                  :custom_buttons="custom_buttons"
        >
            <template v-slot:table>
                <b-table ref="datatable" variant="primary" responsive="md" hover bordered small striped
                         head-variant="dark"
                         :items="getItems"
                         class="mb-0"
                         :fields="fields" id="datatable" :filter="search"
                         :per-page="datatable.per_page" :current-page="datatable.current_page"
                >
                    <template v-slot:cell(action)="row">
                        <b-button-group size="sm">
                            <b-button variant="primary"
                                      @click="()=>{
                                            currentItem=JSON.parse(JSON.stringify(row.item));
                                      }"
                                      v-b-modal.viewModal>
                                <i class="fa fa-eye"></i>
                            </b-button>
                            <b-button variant="warning"
                                      @click="()=>{
                                          form=JSON.parse(JSON.stringify(row.item));
                                      }"
                                      v-b-modal.addModal>
                                <i class="fa fa-edit"></i>
                            </b-button>
                            <b-button variant="danger" @click="trash(row.item.id)">
                                <i class="fa fa-trash"></i>
                            </b-button>
                        </b-button-group>
                    </template>
                </b-table>
            </template>
        </dt-table>
        <b-modal id="addModal" :title="add_modal_title " size="md"
                 header-bg-variant="dark"
                 header-text-variant="light"
                 lazy>
            <add-item @created="(v)=>{$refs.datatable.refresh();$bvModal.hide('addModal');msgBox(v);}"
                      @error="v=>msgBox(v.data)"
                      :hide_submit="true"
                      :submit_url="submit_url"
                      v-model="form"
                      :password_enabled="password_enabled"
                      ref="ItemAdd"></add-item>
            <template v-slot:modal-footer="{close}">
                <b-button-group class="float-right">
                    <b-button variant="primary" @click="$refs.ItemAdd.hitSubmit()">SUBMIT</b-button>
                    <b-button @click="close()">Close</b-button>
                </b-button-group>
            </template>
        </b-modal>
        <b-modal id="viewModal" :title="view_modal_title"
                 size="xl"
                 header-bg-variant="dark"
                 header-text-variant="light"
                 no-body
                 lazy>
            <b-row>
                <b-col>
                    <b-table small bordered hover striped
                             head-variant="dark"
                             :items="obj2Table(currentItem,['deleted_at'])"
                             :fields="[
                            {
                                key: 'key', sortable: true,
                                formatter: (v) => startCase(v)
                            },
                            {
                                key: 'value',
                                sortable: true
                            }
                       ]">
                        <template v-slot:cell(value)="row">
                            <template v-if="['created_at','updated_at'].includes(row.item.key)">
                                {{row.item.value | dayjs}}
                            </template>
                            <template v-else>{{row.item.value}}</template>
                        </template>
                    </b-table>
                </b-col>
            </b-row>

        </b-modal>
    </div>
</template>

<script>
    import DtHeader from '../../partials/DtHeader'
    import DtFooter from '../../partials/DtFooter'
    import AddItem from "./Add";
    import Datatable from "@/partials/datatable";
    import DtTable from "../../partials/DtTable";
    import DataView from "../../partials/DataView";
    import custom_buttons from "../custom_buttons";

    export default {
        name: "BrandsList",
        components: {
            DtHeader,
            DtFooter,
            AddItem,
            DtTable,
            DataView
        },
        mixins: [Datatable],
        props: {
            password_enabled: {
                type: Boolean,
                default: true
            },
            title: {
                type: String,
                default: ''
            },
            api_url: {
                type: String,
                default: "/backend/LaravelProducts/brands/list"
            },
            trash_url: {
                type: String,
                default: "/backend/LaravelProducts/brands/delete"
            },
            submit_url: {
                type: String,
                default: "/backend/LaravelProducts/brands/store"
            },
            add_modal_title: {
                type: String,
                default: 'Add / Edit Brand'
            },
            view_modal_title: {
                type: String,
                default: 'View Brand'
            },
            custom_buttons: {
                type: Array,
                default: () => custom_buttons()
            },
        },
        mounted() {
            this.custom_buttons.push({
                text: 'Add',
                variant: 'outline-dark',
                method: () => {
                    this.$bvModal.show("addModal");
                }
            });
        },
        data() {
            return {
                form: {},
                fields: [
                    {key: 'id', sortable: true},
                    {key: 'name', sortable: true},
                    {key: 'description', sortable: true, formatter: v => this.truncate(v)},
                    {key: 'created_at', sortable: true, visible: false, formatter: v => this.dayjs(v)},
                    {key: 'updated_at', sortable: true, visible: false, formatter: v => this.dayjs(v)},
                    {key: 'action', sortable: false, searchable: false, thClass: 'text-right', tdClass: 'text-right'},
                ]
            }
        }
    }
</script>
