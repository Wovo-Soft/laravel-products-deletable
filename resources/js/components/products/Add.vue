<template>
    <form @submit.prevent="onSubmit">
        <div class="row">
            <div class="col-md-4">
                <b-form-group label="Name *">
                    <b-form-input type="text" v-model="form.name" placeholder="Name" :required="true"/>
                </b-form-group>
                <b-form-group label="UPC *">
                    <b-form-input type="text" v-model="form.upc" placeholder="UPC" :required="true"/>
                </b-form-group>
                <b-form-group label="Type">
                    <b-form-select v-model="form.type" :options="product_types"></b-form-select>
                </b-form-group>
                <b-form-group label="Image">
                    <b-form-file v-model="form.image_upload" placeholder="Product Image"></b-form-file>
                </b-form-group>
            </div>
            <div class="col-md-4">
                <b-form-group label="Unit">
                    <b-form-select :options=" ['Kilogram', 'Quantity','Liter']" v-model="form.unit"></b-form-select>
                </b-form-group>
                <b-form-group label="Cost">
                    <b-form-input type="number" step="any" v-model="form.cost"
                                  placeholder="Product Purchase Cost"></b-form-input>
                </b-form-group>
                <b-form-group label="Price">
                    <b-form-input type="number" step="any" v-model="form.price"
                                  placeholder="Product Selling Price"></b-form-input>
                </b-form-group>
                <b-form-group label="URL">
                    <b-form-input type="url" v-model="form.url" placeholder="Product's External URL"></b-form-input>
                </b-form-group>
            </div>
            <div class="col-md-4">
                <b-form-group label="Categories">
                    <type-head
                        placeholder="Search Categories"
                        v-model="form.categories"
                        :is-duplicate="(items,item)=>!!items.find(i=>i.id===item.id)"
                        api_url="/backend/LaravelProducts/categories/search">
                        <template v-slot:tag="item">
                            {{item.tag.id}} # {{item.tag.name}}
                        </template>
                        <template v-slot:option="option">
                            {{option.item.id}} # {{option.item.name}}
                        </template>
                    </type-head>
                </b-form-group>
                <b-form-group label="Brands">
                    <type-head
                        placeholder="Search Brands"
                        v-model="form.brands"
                        :is-duplicate="(items,item)=>!!items.find(i=>i.id===item.id)"
                        api_url="/backend/LaravelProducts/brands/search">
                        <template v-slot:tag="item">
                            {{item.tag.id}} # {{item.tag.name}}
                        </template>
                        <template v-slot:option="option">
                            {{option.item.id}} # {{option.item.name}}
                        </template>
                    </type-head>
                </b-form-group>
                <!--                <pre v-html="form"></pre>-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <b-form-group label="Description">
                    <b-form-textarea placeholder="Product Description"
                                     v-model="form.description"
                                     :rows="5"
                    />
                </b-form-group>
            </div>
            <div class="col-md-8 col-sm-12">
                <b-form-group>
                    <template v-slot:label>
                        <b-row>
                            <b-col md="6" sm="12">Attributes</b-col>
                            <b-col md="6" sm="12" xs="12" class="text-right">
                                <b-button title="Add New Attribute" size="sm" variant="dark"
                                          @click="form.product_attributes.push({key:null,value:null})">
                                    Add
                                </b-button>
                            </b-col>
                        </b-row>
                    </template>
                    <b-table :items="form.product_attributes" small bordered hover striped head-variant="dark"
                             :fields="[{key:'key',sortable:true,tdClass: 'p-0'},{key:'value',sortable: true,tdClass: 'p-0'},{key:'action',thClass:'text-right',tdClass:'text-right'}]">
                        <template v-slot:cell(key)="row">
                            <b-form-input list="product_attributes_list"
                                          v-model="row.item.key"
                                          :required="true"
                                          class="border-0"
                                          placeholder="Attribute Name"></b-form-input>
                        </template>
                        <template v-slot:cell(value)="row">
                            <b-form-tags v-model="row.item.value"
                                         :required="true"
                                         remove-on-delete
                                         tag-pills
                                         class="border-0"
                                         tag-variant="primary"
                                         placeholder="Attribute Value"></b-form-tags>
                        </template>
                        <template v-slot:cell(action)="row">
                            <b-button size="sm" title="Remove Attribute"
                                      variant="dark"
                                      @click="form.product_attributes.splice(row.index,1)">
                                <i class="fa fa-trash"></i>
                            </b-button>
                        </template>
                    </b-table>
                    <b-form-datalist id="product_attributes_list"
                                     :options="product_attributes_list"/>
                </b-form-group>
            </div>
        </div>
        <b-form-group>
            <template v-slot:label>
                <b-row>
                    <b-col>Product Variations</b-col>
                    <b-col class="text-right">
                        <b-button-group size="sm">
                            <b-button variant="primary" title="Click to Generate Variations from Product Attributes"
                                      @click="generateProductVariations()">
                                Generate
                            </b-button>
                            <b-button title="Add New Variation" variant="dark"
                                      @click="form.variations.push({cost:0,price:0,description:null})">
                                Add
                            </b-button>
                        </b-button-group>
                    </b-col>
                </b-row>
            </template>
            <b-table bordered small hover striped head-variant="dark"
                     :items="form.variations"
                     :fields="['id','title','cost','price','description',{key:'action',thClass:'text-right',tdClass:'text-right'}]">
                <template v-slot:cell(title)="row">
                    <b-form-input type="text" :required="true" placeholder="Variation Title"
                                  v-model="row.item.title"></b-form-input>
                </template>
                <template v-slot:cell(cost)="row">
                    <b-form-input type="number" step="any" placeholder="Purchase Cost"
                                  :required="true"
                                  v-model="row.item.cost"></b-form-input>
                </template>
                <template v-slot:cell(price)="row">
                    <b-form-input type="number" step="any" placeholder="Selling Price"
                                  :required="true"
                                  v-model="row.item.price"></b-form-input>
                </template>
                <template v-slot:cell(description)="row">
                    <b-form-textarea v-model="row.item.description" :max-rows="8" placeholder="Description"></b-form-textarea>
                </template>
                <template v-slot:cell(action)="row">
                    <b-button size="sm" title="Remove Variation"
                              variant="dark"
                              @click="form.variations.splice(row.index,1)">
                        <i class="fa fa-trash"></i>
                    </b-button>
                </template>
            </b-table>
        </b-form-group>
        <b-button ref="submitBtn" variant="primary" :hidden="hide_submit" block type="submit">SUBMIT</b-button>
    </form>
</template>

<script>
    import j2FD from '@/partials/jsonToFormData'
    import TypeHead from "../../partials/TypeHead";
    import {cartesianProduct} from "../../partials/datatable";

    export default {
        props: {
            submit_url: {
                type: String,
                default: null
            },
            value: {
                type: Object,
                default: function () {
                    return {
                        id: null
                    }
                }
            },
            hide_submit: {
                type: Boolean,
                default: false
            }
        },
        components: {
            TypeHead
        },
        mounted() {
            this.form = JSON.parse(JSON.stringify(this.value));
            if (!this.form.variations) {
                this.$set(this.form, 'variations', []);
            }
            if (!this.form.product_attributes) {
                this.$set(this.form, 'product_attributes', []);
            }
        },
        watch: {
            form: {
                handler(value) {
                    this.$emit('input', value);
                },
                deep: true
            }
        },
        data() {
            return {
                product_types: [
                    {text: 'Countable', value: 'countable'},
                    {text: 'Uncountable', value: 'uncountable'},
                ],
                product_attributes_list: ['Size', 'Color', 'Height', 'Width', 'Gender', 'Type', 'Brand'],
                form: {},
            }
        },
        methods: {
            cartesianProduct,
            onSubmit() {
                axios.post(this.submit_url, j2FD(this.form))
                    .then(res => {
                        console.log(res)
                        this.$emit('created', res.data);
                    })
                    .catch(err => {
                        this.$emit('error', err.response);
                        console.log(err.response);
                        // this.$emit('error',res)
                    });
            },
            hitSubmit() {
                this.$refs.submitBtn.click();
            },
            generateProductVariations() {
                let ids = this.form.variations.map(i => i.title);
                this.cartesianProduct(...this.form.product_attributes.filter(i => i.key && i.value).map(i => i.value))
                    .forEach(i => {
                        if (!ids.includes(i.join(' '))) {
                            this.form.variations.push({
                                title: i.join(' '),
                                cost: this.form.cost || 0,
                                price: this.form.price || 0,
                                description: this.form.description || null
                            })
                        }
                    });
            }
        }
    }
</script>

