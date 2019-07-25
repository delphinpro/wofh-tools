<script>/*!
 * WofhTools
 * DemoInputs.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Inputbox from '@/components/Inputbox';
import Box from '@/components/Box';


export default {
    name: 'DemoInputs',

    components: {
        Box,
        Inputbox,
    },
};
</script>

<template>
    <section class="">
        <div class="row">
            <div class="col-md-6">
                <Box type="primary" title="Quick Example">
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

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Input Addon</h3>
                    </div>
                    <div class="box-body">
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input class="form-control" type="text" placeholder="Username">
                        </div>
                        <br>

                        <div class="input-group">
                            <input class="form-control" type="text">
                            <span class="input-group-addon">.00</span>
                        </div>
                        <br>

                        <div class="input-group">
                            <span class="input-group-addon">$</span>
                            <input class="form-control" type="text">
                            <span class="input-group-addon">.00</span>
                        </div>

                        <h4>With icons</h4>

                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                            <input class="form-control" type="email" placeholder="Email">
                        </div>
                        <br>

                        <div class="input-group">
                            <input class="form-control" type="text">
                            <span class="input-group-addon"><i class="fa fa-check"></i></span>
                        </div>
                        <br>

                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                            <input class="form-control" type="text">
                            <span class="input-group-addon"><i class="fa fa-ambulance"></i></span>
                        </div>

                        <h4>With checkbox and radio inputs</h4>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="input-group">
                        <span class="input-group-addon">
                          <input type="checkbox">
                        </span>
                                    <input class="form-control" type="text">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group">
                        <span class="input-group-addon">
                          <input type="radio">
                        </span>
                                    <input class="form-control" type="text">
                                </div>
                            </div>
                        </div>

                        <h4>With buttons</h4>

                        <p class="margin">Large: <code>.input-group.input-group-lg</code></p>

                        <div class="input-group input-group-lg">
                            <div class="input-group-btn">
                                <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
                                    Action
                                    <span class="fa fa-caret-down"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Action</a></li>
                                    <li><a href="#">Another action</a></li>
                                    <li><a href="#">Something else here</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">Separated link</a></li>
                                </ul>
                            </div>
                            <input class="form-control" type="text">
                        </div>
                        <p class="margin">Normal</p>

                        <div class="input-group">
                            <div class="input-group-btn">
                                <button class="btn btn-danger" type="button">Action</button>
                            </div>
                            <input class="form-control" type="text">
                        </div>
                        <p class="margin">Small <code>.input-group.input-group-sm</code></p>

                        <div class="input-group input-group-sm">
                            <input class="form-control" type="text">
                            <span class="input-group-btn">
                      <button class="btn btn-info btn-flat" type="button">Go!</button>
                    </span>
                        </div>
                    </div>
                </div>
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

                <Box type="primary" title="Other elements">
                    <form class="demo">
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox">
                                    Checkbox 1
                                </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox">
                                    Checkbox 2
                                </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" disabled="">
                                    Checkbox disabled
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="radio">
                                <label>
                                    <input id="optionsRadios1"
                                        name="optionsRadios"
                                        value="option1"
                                        type="radio"
                                        checked="">
                                    Option one is this and that—be sure to include why it's great
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input id="optionsRadios2" name="optionsRadios" value="option2" type="radio">
                                    Option two can be something else and selecting it will deselect option one
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input id="optionsRadios3"
                                        name="optionsRadios"
                                        value="option3"
                                        type="radio"
                                        disabled="">
                                    Option three is disabled
                                </label>
                            </div>
                        </div>

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

                    </form>
                </Box>
            </div>
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
</style>
