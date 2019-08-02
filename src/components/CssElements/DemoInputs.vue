<script>/*!
 * WofhTools
 * DemoInputs.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import 'vue-awesome/icons/check';
import 'vue-awesome/icons/regular/envelope';
import Box from '@/components/Widgets/Box';


export default {
    name: 'DemoInputs',

    components: {
        Box,
    },

    data: () => ({
        cb1: false,
        cb2: true,
        cb3: false,
        cb4: true,

        cbList: [],
        cbList2: [1, 2],

        rb1: 1,
        rb2: 2,
        rb3: 2,
    }),

};
</script>

<template>
    <section class="">
        <div class="row">
            <div class="col-md-6">
                <Box type="info" title="Quick Example">
                    <form class="demo">
                        <Inputbox
                            class="form-group"
                            value="Сергей delphinpro Безбородов"
                            type="text"
                            label="Your name"
                            placeholder="Input your name here..."
                        />
                        <Inputbox
                            class="form-group"
                            type="email"
                            label="Email address"
                            placeholder="Input email address here..."
                        />
                        <Inputbox
                            class="form-group"
                            type="password"
                            label="Password"
                            placeholder="Password"
                        />
                        <div class="form-group">
                            <label for="exampleInputFile">File input</label>
                            <input id="exampleInputFile" type="file">

                            <p class="help-block">Example block-level help text here.</p>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> Check me out
                            </label>
                        </div>
                        <button class="btn btn_primary" type="submit">Submit</button>
                    </form>
                </Box>

                <Box type="info" title="Input Addon">
                    <div class="demo">
                        <Inputbox
                            class="form-group"
                            placeholder="Username"
                            addon="@"
                        />
                        <Inputbox
                            class="form-group"
                            placeholder="Price"
                            addon=".00"
                            addon-position="end"
                        />

                        <h4 class="type-title">With icons</h4>

                        <Inputbox
                            class="form-group"
                            placeholder="Email"
                            addon-icon="regular/envelope"
                            addon-position="start"
                        />

                        <Inputbox
                            class="form-group"
                            placeholder="Email"
                            addon-icon="check"
                            addon-position="end"
                            status="success"
                        />

                        <Inputbox
                            class="form-group"
                            placeholder="Password"
                            addon-icon="exclamation-triangle"
                            addon-position="end"
                            status="danger"
                        />
                    </div>
                </Box>
            </div>


            <div class="col-md-6">
                <Box type="primary" title="General Elements">
                    <form class="demo">
                        <Inputbox
                            class="form-group"
                            value="Default value"
                            type="text"
                            placeholder="Enter ..."
                            label="Text Disabled"
                            disabled
                        />
                        <Inputbox
                            class="form-group"
                            value="Default value"
                            type="text"
                            placeholder="Enter ..."
                            label="Text Readonly"
                            readonly
                        />
                        <Inputbox
                            class="form-group"
                            type="textarea"
                            label="Textarea"
                            placeholder="Input your message here..."
                        />
                        <Inputbox
                            class="form-group"
                            type="textarea"
                            label="Textarea disabled"
                            placeholder="Input your message here..."
                            disabled
                        />
                    </form>
                </Box>

                <Box type="primary" title="Validation status">
                    <form class="demo">
                        <Inputbox
                            class="form-group"
                            value="Default value"
                            type="text"
                            placeholder="Enter ..."
                            label="Input with success"
                            label-icon="check"
                            help="Help block with success"
                            status="success"
                        />
                        <Inputbox
                            class="form-group"
                            value=""
                            type="text"
                            placeholder="Enter ..."
                            label="Input with warning"
                            help="Help block with warning"
                            status="warning"
                        />
                        <Inputbox
                            class="form-group"
                            value=""
                            type="text"
                            placeholder="Enter ..."
                            label="Input with error"
                            help="Help block with error"
                            status="danger"
                        />
                    </form>
                </Box>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <Box type="primary" title="Checkbox & Radio button">
                    <form class="demo">
                        <div class="form-group">
                            <Checkbox v-model="cb1">Checkbox 1 <code>{{cb1}}</code></Checkbox>
                        </div>
                        <div class="form-group">
                            <Checkbox v-model="cb2" :checked="true">Checkbox 2 <code>{{cb2}}</code></Checkbox>
                        </div>
                        <div class="form-group">
                            <Checkbox v-model="cb3" :disabled="true">Checkbox disabled</Checkbox>
                        </div>
                        <div class="form-group">
                            <Checkbox v-model="cb4" :disabled="true" :checked="true">Checkbox checked disabled
                            </Checkbox>
                        </div>
                        <div class="form-group">
                            <Checkbox v-model="cb2" :checked="true">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus dignissimos ex
                                maxime nemo provident.
                            </Checkbox>
                        </div>

                        <div class="form-group">
                            <RadioButton :checked="true" v-model="rb1" :value="1">
                                Option one is this and that—be sure to include why it's great
                            </RadioButton>
                            <RadioButton :value="2" v-model="rb1">
                                Option two can be something else and selecting it will deselect option one
                            </RadioButton>
                            <RadioButton :disabled="true" v-model="rb1" :value="3">
                                Option three is disabled
                            </RadioButton>
                        </div>
                    </form>
                </Box>
            </div>
            <div class="col-md-6">
                <Box type="primary" title="Checkbox & Radio button variations">
                    <form class="demo">
                        <div class="form-group">
                            <div>Один чекбокс, привязанный к булю через v-model:</div>
                            <div class="demo-checkboxes">
                                <Checkbox v-model="cb1">1 <code>{{cb1}}</code></Checkbox>
                                <Checkbox v-model="cb2">2 <code>{{cb2}}</code></Checkbox>
                            </div>
                        </div>
                        <div class="form-group">
                            <div>Список чекбоксов, привязанных к одному массиву:</div>
                            <div class="demo-checkboxes">
                                <Checkbox value="1" v-model="cbList" :checked="true">1</Checkbox>
                                <Checkbox value="2" v-model="cbList">2</Checkbox>
                                <Checkbox value="3" v-model="cbList" :checked="true">3</Checkbox>
                                <code>{{cbList}}</code>
                            </div>
                        </div>
                        <div class="form-group">
                            <div>Список чекбоксов, инициализация по массиву:</div>
                            <div class="demo-checkboxes">
                                <Checkbox :value="1" v-model="cbList2">1</Checkbox>
                                <Checkbox :value="2" v-model="cbList2">2</Checkbox>
                                <Checkbox :value="3" v-model="cbList2">3</Checkbox>
                                <code>{{cbList2}}</code>
                            </div>
                        </div>
                    </form>
                </Box>
            </div>
            <div class="col-md-12">
                <Box type="primary" title="Checkbox & Radio button Color Variations">
                    <form class="demo">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="demo-checkboxes">
                                        <Checkbox>Default</Checkbox>
                                        <Checkbox :checked="true">Checked</Checkbox>
                                        <Checkbox :disabled="true">Disabled</Checkbox>
                                        <Checkbox :disabled="true" :checked="true">✓ Disabled</Checkbox>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="demo-checkboxes">
                                        <Checkbox theme="info">Info</Checkbox>
                                        <Checkbox theme="info" :checked="true">Checked</Checkbox>
                                        <Checkbox theme="info" :disabled="true">Disabled</Checkbox>
                                        <Checkbox theme="info" :disabled="true" :checked="true">✓ Disabled</Checkbox>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="demo-checkboxes">
                                        <Checkbox theme="success">Success</Checkbox>
                                        <Checkbox theme="success" :checked="true">Checked</Checkbox>
                                        <Checkbox theme="success" :disabled="true">Disabled</Checkbox>
                                        <Checkbox theme="success" :disabled="true" :checked="true">✓ Disabled</Checkbox>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="demo-checkboxes">
                                        <Checkbox theme="warning">Warning</Checkbox>
                                        <Checkbox theme="warning" :checked="true">Checked</Checkbox>
                                        <Checkbox theme="warning" :disabled="true">Disabled</Checkbox>
                                        <Checkbox theme="warning" :disabled="true" :checked="true">✓ Disabled</Checkbox>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="demo-checkboxes">
                                        <Checkbox theme="danger">Danger</Checkbox>
                                        <Checkbox theme="danger" :checked="true">Checked</Checkbox>
                                        <Checkbox theme="danger" :disabled="true">Disabled</Checkbox>
                                        <Checkbox theme="danger" :disabled="true" :checked="true">✓ Disabled</Checkbox>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="demo-checkboxes">
                                        <RadioButton v-model="rb2" :value="1">Default</RadioButton>
                                        <RadioButton v-model="rb2" :value="2" :checked="true">Checked</RadioButton>
                                        <RadioButton v-model="rb3" :value="1" :disabled="true">Disabled</RadioButton>
                                        <RadioButton v-model="rb3" :value="2" :disabled="true" :checked="true">✓ Disabled</RadioButton>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="demo-checkboxes">
                                        <RadioButton v-model="rb2" :value="1" theme="info">Info</RadioButton>
                                        <RadioButton v-model="rb2" :value="2" theme="info" :checked="true">Checked</RadioButton>
                                        <RadioButton v-model="rb3" :value="1" theme="info" :disabled="true">Disabled</RadioButton>
                                        <RadioButton v-model="rb3" :value="2" theme="info" :disabled="true" :checked="true">✓ Disabled</RadioButton>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="demo-checkboxes">
                                        <RadioButton v-model="rb2" :value="1" theme="success">Success</RadioButton>
                                        <RadioButton v-model="rb2" :value="2" theme="success" :checked="true">Checked</RadioButton>
                                        <RadioButton v-model="rb3" :value="1" theme="success" :disabled="true">Disabled</RadioButton>
                                        <RadioButton v-model="rb3" :value="2" theme="success" :disabled="true" :checked="true">✓ Disabled</RadioButton>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="demo-checkboxes">
                                        <RadioButton v-model="rb2" :value="1" theme="warning">Warning</RadioButton>
                                        <RadioButton v-model="rb2" :value="2" theme="warning" :checked="true">Checked</RadioButton>
                                        <RadioButton v-model="rb3" :value="1" theme="warning" :disabled="true">Disabled</RadioButton>
                                        <RadioButton v-model="rb3" :value="2" theme="warning" :disabled="true" :checked="true">✓ Disabled</RadioButton>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="demo-checkboxes">
                                        <RadioButton v-model="rb2" :value="1" theme="danger">Danger</RadioButton>
                                        <RadioButton v-model="rb2" :value="2" theme="danger" :checked="true">Checked</RadioButton>
                                        <RadioButton v-model="rb3" :value="1" theme="danger" :disabled="true">Disabled</RadioButton>
                                        <RadioButton v-model="rb3" :value="2" theme="danger" :disabled="true" :checked="true">✓ Disabled</RadioButton>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </Box>
            </div>
            <!--
            <div class="col-md-6">
                <Box type="info" title="Other Elements">
                    <div class="demo">
                        <div class="form-group">
                            <label>Select</label>
                            <select class="form-control">
                                <option>option 1</option>
                                <option>option 2</option>
                                <option>option 3</option>
                                <option>option 4</option>
                                <option>option 5</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Select Disabled</label>
                            <select class="form-control" disabled="">
                                <option>option 1</option>
                                <option>option 2</option>
                                <option>option 3</option>
                                <option>option 4</option>
                                <option>option 5</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Select Multiple</label>
                            <select class="form-control" multiple="">
                                <option>option 1</option>
                                <option>option 2</option>
                                <option>option 3</option>
                                <option>option 4</option>
                                <option>option 5</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Select Multiple Disabled</label>
                            <select class="form-control" multiple="" disabled="">
                                <option>option 1</option>
                                <option>option 2</option>
                                <option>option 3</option>
                                <option>option 4</option>
                                <option>option 5</option>
                            </select>
                        </div>
                    </div>
                </Box>
            </div>
            -->
        </div>
    </section>
</template>

<style lang="scss" scoped>
    .demo {
        background: $background-base;
        padding: rhythm(0.5);
        margin-left: rhythm(-0.45);
        margin-right: rhythm(-0.45);
    }

    .demo-checkboxes {
        display: flex;
        align-items: flex-start;

        > * + * {
            margin-left: 1rem;
        }
    }
</style>
