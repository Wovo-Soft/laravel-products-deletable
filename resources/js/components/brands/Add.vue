<template>
    <form @submit.prevent="onSubmit">
        <b-form-group label="Name *">
            <b-form-input v-model="form.name" placeholder="Name" :required="true"/>
        </b-form-group>
        <b-form-group label="Description">
            <b-form-textarea placeholder="Product Description"
                             v-model="form.description"
                             :rows="5"
            />
        </b-form-group>
        <b-button ref="submitBtn" variant="primary" :hidden="hide_submit" block type="submit">SUBMIT</b-button>
    </form>
</template>

<script>
    import j2FD from '@/partials/jsonToFormData'
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
        mounted() {
            this.form = JSON.parse(JSON.stringify(this.value));
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
                form: {},
            }
        },
        methods: {
            onSubmit() {
                axios.post(this.submit_url, j2FD(this.form))
                    .then(res => {
                        // console.log(res)
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
            }
        }
    }
</script>

