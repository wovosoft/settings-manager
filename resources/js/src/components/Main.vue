<template>
    <div>
        <b-card>
            <template v-slot:header>
                <div class="row">
                    <div class="col">
                        {{header}}
                    </div>
                    <div class="col text-right">
                        <button type="button" v-b-modal.addModal
                                title="Click to Add New Settings Option"
                                class="btn btn-sm btn-outline-dark">
                            <i class="fa fa-plus"></i> New
                        </button>
                    </div>
                </div>
            </template>
            <div class="row">
                <div class="col-md-4" v-for="(item,key) in items" :key="key">
                    <b-card :header="startCase(key)||'Ungrouped'">
                        <b-form-group v-for="(v,key) in item" :key="key">
                            <template v-slot:label>
                                <b-row>
                                    <b-col>
                                        {{startCase(key)}}
                                    </b-col>
                                    <b-col class="text-right">
                                        <b-button-group size="sm">
                                            <b-button title="Save" variant="primary"
                                                      @click="()=>{
                                                            addData=JSON.parse(JSON.stringify(item[key]));
                                                            addOption();
                                                       }">
                                                <i class="fa fa-save"></i>
                                            </b-button>
                                            <b-button title="Modify" variant="dark"
                                                      v-b-modal.addModal
                                                      @click="addData=JSON.parse(JSON.stringify(item[key]))">
                                                <i class="fa fa-edit"></i>
                                            </b-button>
                                            <b-button title="Delete" variant="danger"
                                                      @click="trash(item[key].id)">
                                                <i class="fa fa-trash"></i>
                                            </b-button>
                                        </b-button-group>
                                    </b-col>
                                </b-row>
                            </template>
                            <component :is="types[v.type].el" v-model="v.value"
                                       :placeholder="'Enter \''+v.key +'\' value'"></component>
                        </b-form-group>
                        <!--                        <pre v-html="item"></pre>-->
                    </b-card>
                </div>
            </div>
        </b-card>

        <b-modal id="addModal" @hidden="addData=JSON.parse(JSON.stringify(temp))"
                 title="Add Settings Option"
                 header-bg-variant="dark"
                 size="lg"
                 header-text-variant="light" lazy>
            <form @submit.prevent="addOption">
                <div class="row">
                    <div class="col">
                        <b-form-group label="Type *">
                            <select class="form-control"
                                    v-model="addData.type"
                                    @change="typeChanged(addData.type)"
                                    :required="true">
                                <template v-for="(op,key) in types">
                                    <option v-bind:value="key">{{startCase(key)}}</option>
                                </template>
                            </select>
                        </b-form-group>
                    </div>
                    <div class="col">
                        <b-form-group label="Group">
                            <input type="text"
                                   class="form-control"
                                   v-model="addData.group"
                                   list="group-list"
                                   placeholder="Enter Group Name or Select Existing">
                            <datalist id="group-list">
                                <option v-for="(item,key) in items">{{ key }}</option>
                            </datalist>
                        </b-form-group>
                    </div>
                </div>
                <b-form-group label="Key (Unique)*">
                    <input type="text"
                           v-model="addData.key"
                           class="form-control"
                           placeholder="Enter Unique Settings Key"
                           :required="true">
                </b-form-group>
                <b-form-group label="Options">
                    <b-form-tags input-id="tags-basic"
                                 remove-on-delete
                                 placeholder="Add / Remove Options"
                                 v-model="addData.options"></b-form-tags>
                    <span class="form-text text-muted" v-if="addData.type==='checkbox'">
                        <b>Note for CheckBoxes</b>:   Add Two Options only. First Options will be
                        checked value and Second Options will be unchecked value.
                        Otherwise, default true and false value will be used.
                    </span>
                </b-form-group>

                <b-form-group label="Value">
                    <template v-if="inputs_elements.includes(addData.type)">
                        <component :is="types[addData.type].el"
                                   :type="types[addData.type].type"
                                   v-model="addData.value"
                                   list="input_list"
                                   autocomplete="off"
                                   placeholder="Enter Value"></component>
                        <datalist id="input_list">
                            <option v-for="i in addData.options">{{i}}</option>
                        </datalist>
                    </template>
                    <template v-else-if="['select','multi_select'].includes(addData.type)">
                        <component :is="types[addData.type].el"
                                   :options="addData.options"
                                   v-model="addData.value"></component>
                    </template>
                    <template v-else-if="addData.type==='checkbox'">
                        <component :is="types[addData.type].el"
                                   :options="addData.options"
                                   :value="addData.options.length>0?(['true','1'].includes(addData.options[0])?true:addData.options[0]):true"
                                   :unchecked-value="addData.options.length>0?(['false','0'].includes(addData.options[1])?false:addData.options[1]):false"
                                   v-model="addData.value">
                            {{startCase(addData.key)}} ({{addData.value}})
                        </component>
                    </template>
                    <template v-else-if="['checkbox_group','radio'].includes(addData.type)">
                        <component :is="types[addData.type].el"
                                   :options="addData.options"
                                   v-model="addData.value">
                        </component>
                    </template>
                    <template v-else>
                        <component :is="types[addData.type].el"
                                   v-model="addData.value"
                                   list="common_comp_list"
                                   placeholder="Enter Value"></component>
                        <datalist id="common_comp_list">
                            <option v-for="op in addData.options">{{op}}</option>
                        </datalist>
                    </template>
                </b-form-group>
<!--                <pre v-html="addData"></pre>-->
                <button type="submit" class="d-none" ref="add_submit_btn"></button>
            </form>
            <template v-slot:modal-footer="{close}">
                <button class="btn btn-dark" @click="$refs.add_submit_btn.click()" type="button">SUBMIT</button>
            </template>
        </b-modal>
    </div>
</template>

<script>
    import {startCase, msgBox} from '@/partials/datatable'

    export default {
        name: "Main",
        props: {
            header: {
                type: String,
                default: 'Settings Manager'
            }
        },
        data: () => {
            return {
                the_el: {
                    el: "b-form-input",
                    type: 'text'
                },
                temp: {
                    type: "text",
                    options: [],
                    key: '',
                    value: '',
                    group: '',
                    getModel: false
                },
                addData: {
                    type: "text",
                    options: [],
                    key: '',
                    value: '',
                    group: '',
                    getModel: false
                },
                form: {},
                items: [],
                inputs_elements: [
                    "text", "number", "email", "password", "search",
                    "url", "tel", "date", "time", "range", "color"
                ],
                types: {
                    text: {
                        el: "b-form-input",
                        type: 'text'
                    },
                    number: {
                        el: "b-form-input",
                        type: "number"
                    },
                    email: {
                        el: "b-form-input",
                        type: "email"
                    },
                    password: {
                        el: "b-form-input",
                        type: "password"
                    },
                    search: {
                        el: "b-form-input",
                        type: "search"
                    },
                    url: {
                        el: "b-form-input",
                        type: "url"
                    },
                    tel: {
                        el: "b-form-input",
                        type: "tel"
                    },
                    date: {
                        el: "b-form-input",
                        type: "date"
                    },
                    time: {
                        el: "b-form-input",
                        type: "time"
                    },
                    range: {
                        el: "b-form-input",
                        type: "range"
                    },
                    color: {
                        el: "b-form-input",
                        type: "color"
                    },
                    textarea: {
                        el: "b-form-textarea",
                    },
                    checkbox: {
                        el: "b-form-checkbox",
                        has_options: false
                    },
                    checkbox_group: {
                        el: "b-form-checkbox-group",
                        has_options: true
                    },
                    datepicker: {
                        el: "b-form-datepicker",
                    },
                    timepicker: {
                        el: "b-form-timepicker"
                    },
                    radio: {
                        el: "b-form-radio-group",
                        has_options: true
                    },
                    select: {
                        el: "b-form-select",
                        multiple: false,
                        has_options: true
                    },
                    multi_select: {
                        el: "b-form-select",
                        multiple: true
                    }
                }
            }
        },
        mounted() {
            this.getOptionsGroupded();
        },
        methods: {
            startCase,
            msgBox,
            addOption() {
                axios
                    .post("backend/SettingsManager/set", JSON.parse(JSON.stringify(this.addData)))
                    .then(res => {
                        console.log(res)
                        this.msgBox(res.data);
                        if (res.data.status) {
                            this.$bvModal.hide("addModal");
                            this.getOptionsGroupded();
                        }
                    })
                    .catch(err => {
                        this.$log(err.response);
                        this.msgBox(err.response.data);
                    });
            },
            typeChanged(v) {
                if (['checkbox_group', 'multi_select'].includes(v)) {
                    this.addData.value = (typeof this.addData.value === "object") ? this.addData.value : [];
                } else {
                    this.addData.value = (typeof this.addData.value === "string") ? this.addData.value : "";
                }
            },
            getOptionsGroupded() {
                axios.post("/backend/SettingsManager/all_grouped", {
                    getModel: true,
                    grouped: true
                }).then(res => {
                    // console.log(res.data);
                    this.items = res.data;
                }).catch(err => {
                    console.log(err.response);
                });
            },
            trash(id) {
                if (!confirm("Are You Sure?")) {
                    return false;
                }
                axios.post("/backend/SettingsManager/delete", {
                    id: id
                }).then(res => {
                    this.msgBox(res.data);
                    if (res.data.status) {
                        this.getOptionsGroupded();
                    }
                }).catch(err => {
                    this.msgBox(err.response.data);
                    console.log(err.response);
                });
            }
        }
    }
</script>

<style scoped>

</style>
