<template>
    <div v-model="selected_tags" class="border-light border p-1" style="border-color: #ced4da !important;">
        <ul v-if="selected_tags.length > 0" class="list-inline d-inline-block mb-2">
            <li v-for="(tag,i) in selected_tags"
                :key="i"
                class="list-inline-item mb-2">
                <b-button-group :size="size">
                    <b-button :variant="tagVariant">
                        <slot name="tag" v-bind:tag="tag">
                            {{ tag }}
                        </slot>
                    </b-button>
                    <b-button :variant="tagCloseVariant" @click="removeTag(i)">
                        x
                    </b-button>
                </b-button-group>
            </li>
        </ul>
        <b-form-input
            @focus="setDropdownOpen(true)"
            v-model="query"
            @input="getItems"
            :debounce="100"
            type="search"
            :size="size"
            :placeholder="placeholder"
            :autocomplete="autocomplete"
        ></b-form-input>
        <b-dropdown size="sm" v-if="open_dropdown"
                    :variant="dropdownVariant"
                    block
                    toggle-class="d-none"
                    :menu-class="{'show':open_dropdown,'scrollable-dropdown w-100':true}">
            <b-dropdown-item-button
                v-for="(option,option_key) in getOptions"
                :key="option_key"
                @click="onOptionClick(option)"
            >
                <slot name="option" v-bind:item="option">
                    {{ option }}
                </slot>
            </b-dropdown-item-button>
            <b-dropdown-text v-if="options.length === 0">
                {{emptyOptionText}}
            </b-dropdown-text>
        </b-dropdown>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: Array,
                default: () => []
            },
            api_url: {
                type: String,
                default: null,
                required: true
            },
            placeholder: {
                type: String,
                default: 'Search Items'
            },
            size: {
                type: String,
                default: 'sm'
            },
            autocomplete: {
                type: String,
                default: 'off'
            },
            dropdownVariant: {
                type: String,
                default: 'outline-secondary'
            },
            tagVariant: {
                type: String,
                default: 'dark'
            },
            tagCloseVariant: {
                type: String,
                default: 'secondary'
            },
            emptyOptionText: {
                type: String,
                default: 'No items available to select'
            },
            isDuplicate: {
                type: Function,
                default(items = [], item) {
                    return items.includes(item);
                }
            },
            clearOptionsOnDropdownHidden: {
                type: Boolean,
                default: false
            },
            clearSearchOnDropdownHidden: {
                type: Boolean,
                default: true
            },
            clearOptionsOnItemSelected: {
                type: Boolean,
                default: false
            },
            clearSearchOnItemSelected: {
                type: Boolean,
                default: true
            },
            closeDropdownOnSelect: {
                type: Boolean,
                default: false
            },
            showDuplicateOptions: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                open_dropdown: false,
                options: [],
                query: '',
                selected_tags: []
            }
        },
        mounted() {
            setTimeout(() => {
                this.$set(this, 'selected_tags', this.value);
            }, 10);
        },
        computed: {
            getOptions() {
                if (this.showDuplicateOptions) {
                    return this.options;
                }
                return this.options.filter(option => {
                    return !this.isDuplicate(this.selected_tags, option);
                });
            },
        },
        methods: {
            clickOutside(e) {
                if (!(this.$el.contains(e.target) || e.target === this.$el)) {
                    document.removeEventListener('click', this.clickOutside);
                    this.open_dropdown = false;
                    if (this.clearSearchOnDropdownHidden) {
                        this.query = '';
                    }
                    if (this.clearOptionsOnDropdownHidden) {
                        this.options = [];
                    }
                }
            },
            setDropdownOpen(v) {
                this.open_dropdown = !!v;
                if (!!v) {
                    document.addEventListener("click", this.clickOutside);
                }
            },
            onOptionClick(option) {
                if (!this.isDuplicate(this.selected_tags, option)) {
                    this.selected_tags.push(option);
                    if (this.clearOptionsOnItemSelected) {
                        this.options = [];
                    }
                    if (this.clearSearchOnItemSelected) {
                        this.query = '';
                    }
                    this.$emit('input', this.selected_tags);
                }
                if (this.closeDropdownOnSelect) {
                    this.open_dropdown = false;
                    document.removeEventListener('click', this.clickOutside);
                }
            },
            removeTag(index) {
                this.selected_tags.splice(index, 1);
                this.$emit('input', this.selected_tags);
            },

            getItems(e) {
                axios
                    .post(this.api_url, {query: this.query})
                    .then(res => {
                        this.options = res.data || [];
                    })
                    .catch(err => {
                        this.options = [];
                        console.log(err.response);
                    });
            }
        }
    }
</script>
<style>
    .scrollable-dropdown {
        max-height: 300px;
        overflow-y: auto;
    }
</style>
