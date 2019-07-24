<script>/*!
 * WofhTools
 * DemoButtons.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Box from '@/components/Box';


export default {
    name: 'DemoButtons',

    components: {
        Box,
    },
};
</script>

<template>
    <section class="">

        <Box type="primary" title="Buttons" icon="edit">
            <table class="table table-invisible text-center">
                <tbody>
                <tr>
                    <td>
                        <button class="btn btn-default" type="button">Default</button>
                    </td>
                    <td>
                        <button class="btn btn-primary" type="button">Primary</button>
                    </td>
                    <td>
                        <button class="btn btn-info" type="button">Info</button>
                    </td>
                    <td>
                        <button class="btn btn-success" type="button">Success</button>
                    </td>
                    <td>
                        <button class="btn btn-warning" type="button">Warning</button>
                    </td>
                    <td>
                        <button class="btn btn-danger" type="button">Danger</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button class="btn btn-default" type="button" disabled>Default</button>
                    </td>
                    <td>
                        <button class="btn btn-primary" type="button" disabled>Primary</button>
                    </td>
                    <td>
                        <button class="btn btn-info" type="button" disabled>Info</button>
                    </td>
                    <td>
                        <button class="btn btn-success" type="button" disabled>Success</button>
                    </td>
                    <td>
                        <button class="btn btn-warning" type="button" disabled>Warning</button>
                    </td>
                    <td>
                        <button class="btn btn-danger" type="button" disabled>Danger</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button class="btn btn-default" type="button">Normal</button>
                    </td>
                    <td>
                        <button class="btn btn-primary btn-lg" type="button">Large</button>
                    </td>
                    <td>
                        <button class="btn btn-info btn-sm" type="button">Small</button>
                    </td>
                    <td>
                        <button class="btn btn-success btn-xs" type="button">XSmall</button>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-block" type="button">Block</button>
                    </td>
                    <td>
                        <!--<button class="btn btn-danger" type="button">Danger</button>-->
                    </td>
                </tr>
                </tbody>
            </table>
        </Box>

        <div class="row">
            <div class="col-md-6">
                <Box type="primary" title="Horizontal Button Group" icon="">
                    <p>
                        Horizontal button groups are easy to create with bootstrap. Just add your buttons
                        inside <code>&lt;div class="btn-group"&gt;&lt;/div&gt;</code>
                    </p>
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>Button</th>
                            <th>Icons</th>
                            <th>Dropdown</th>
                        </tr>
                        <tr>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-default" type="button">Left</button>
                                    <button class="btn btn-default" type="button">Middle</button>
                                    <button class="btn btn-default" type="button">Right</button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-default" type="button"><i class="fa fa-align-left"></i>
                                    </button>
                                    <button class="btn btn-default" type="button"><i class="fa fa-align-center"></i>
                                    </button>
                                    <button class="btn btn-default" type="button"><i class="fa fa-align-right"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-default" type="button">1</button>
                                    <button class="btn btn-default" type="button">2</button>

                                    <div class="btn-group">
                                        <button class="btn btn-default dropdown-toggle"
                                            type="button"
                                            data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Dropdown link</a></li>
                                            <li><a href="#">Dropdown link</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-info" type="button">Left</button>
                                    <button class="btn btn-info" type="button">Middle</button>
                                    <button class="btn btn-info" type="button">Right</button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-info" type="button"><i class="fa fa-align-left"></i>
                                    </button>
                                    <button class="btn btn-info" type="button"><i class="fa fa-align-center"></i>
                                    </button>
                                    <button class="btn btn-info" type="button"><i class="fa fa-align-right"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-info" type="button">1</button>
                                    <button class="btn btn-info" type="button">2</button>

                                    <div class="btn-group">
                                        <button class="btn btn-info dropdown-toggle"
                                            type="button"
                                            data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Dropdown link</a></li>
                                            <li><a href="#">Dropdown link</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-danger" type="button">Left</button>
                                    <button class="btn btn-danger" type="button">Middle</button>
                                    <button class="btn btn-danger" type="button">Right</button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-danger" type="button"><i class="fa fa-align-left"></i>
                                    </button>
                                    <button class="btn btn-danger" type="button"><i class="fa fa-align-center"></i>
                                    </button>
                                    <button class="btn btn-danger" type="button"><i class="fa fa-align-right"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-danger" type="button">1</button>
                                    <button class="btn btn-danger" type="button">2</button>

                                    <div class="btn-group">
                                        <button class="btn btn-danger dropdown-toggle"
                                            type="button"
                                            data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Dropdown link</a></li>
                                            <li><a href="#">Dropdown link</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning" type="button">Left</button>
                                    <button class="btn btn-warning" type="button">Middle</button>
                                    <button class="btn btn-warning" type="button">Right</button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning" type="button"><i class="fa fa-align-left"></i>
                                    </button>
                                    <button class="btn btn-warning" type="button"><i class="fa fa-align-center"></i>
                                    </button>
                                    <button class="btn btn-warning" type="button"><i class="fa fa-align-right"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning" type="button">1</button>
                                    <button class="btn btn-warning" type="button">2</button>

                                    <div class="btn-group">
                                        <button class="btn btn-warning dropdown-toggle"
                                            type="button"
                                            data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Dropdown link</a></li>
                                            <li><a href="#">Dropdown link</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-success" type="button">Left</button>
                                    <button class="btn btn-success" type="button">Middle</button>
                                    <button class="btn btn-success" type="button">Right</button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-success" type="button"><i class="fa fa-align-left"></i>
                                    </button>
                                    <button class="btn btn-success" type="button"><i class="fa fa-align-center"></i>
                                    </button>
                                    <button class="btn btn-success" type="button"><i class="fa fa-align-right"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-success" type="button">1</button>
                                    <button class="btn btn-success" type="button">2</button>

                                    <div class="btn-group">
                                        <button class="btn btn-success dropdown-toggle"
                                            type="button"
                                            data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Dropdown link</a></li>
                                            <li><a href="#">Dropdown link</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </Box>
            </div>
            <div class="col-md-6">
                <Box type="primary" title="Vertical Button Group" icon="">
                    <p>
                        Vertical button groups are easy to create with bootstrap. Just add your buttons
                        inside <code>&lt;div class="btn-group-vertical"&gt;&lt;/div&gt;</code>
                    </p>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>Button</th>
                            <td>
                                <div class="btn-group-vertical">
                                    <button class="btn btn-default" type="button">Top</button>
                                    <button class="btn btn-default" type="button">Middle</button>
                                    <button class="btn btn-default" type="button">Bottom</button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <button class="btn btn-info" type="button">Top</button>
                                    <button class="btn btn-info" type="button">Middle</button>
                                    <button class="btn btn-info" type="button">Bottom</button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <button class="btn btn-danger" type="button">Top</button>
                                    <button class="btn btn-danger" type="button">Middle</button>
                                    <button class="btn btn-danger" type="button">Bottom</button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <button class="btn btn-warning" type="button">Top</button>
                                    <button class="btn btn-warning" type="button">Middle</button>
                                    <button class="btn btn-warning" type="button">Bottom</button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <button class="btn btn-success" type="button">Top</button>
                                    <button class="btn btn-success" type="button">Middle</button>
                                    <button class="btn btn-success" type="button">Bottom</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Icons</th>
                            <td>
                                <div class="btn-group-vertical">
                                    <button class="btn btn-default" type="button"><i class="fa fa-align-left"></i>
                                    </button>
                                    <button class="btn btn-default" type="button"><i class="fa fa-align-center"></i>
                                    </button>
                                    <button class="btn btn-default" type="button"><i class="fa fa-align-right"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <button class="btn btn-info" type="button"><i class="fa fa-align-left"></i>
                                    </button>
                                    <button class="btn btn-info" type="button"><i class="fa fa-align-center"></i>
                                    </button>
                                    <button class="btn btn-info" type="button"><i class="fa fa-align-right"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <button class="btn btn-danger" type="button"><i class="fa fa-align-left"></i>
                                    </button>
                                    <button class="btn btn-danger" type="button"><i class="fa fa-align-center"></i>
                                    </button>
                                    <button class="btn btn-danger" type="button"><i class="fa fa-align-right"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <button class="btn btn-warning" type="button"><i class="fa fa-align-left"></i>
                                    </button>
                                    <button class="btn btn-warning" type="button"><i class="fa fa-align-center"></i>
                                    </button>
                                    <button class="btn btn-warning" type="button"><i class="fa fa-align-right"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <button class="btn btn-success" type="button"><i class="fa fa-align-left"></i>
                                    </button>
                                    <button class="btn btn-success" type="button"><i class="fa fa-align-center"></i>
                                    </button>
                                    <button class="btn btn-success" type="button"><i class="fa fa-align-right"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </Box>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <Box type="transparent" title="Button Addon" icon="">
                    <div class="input-group margin">
                        <div class="input-group-btn">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
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
                    <div class="input-group margin">
                        <div class="input-group-btn">
                            <button class="btn btn-danger" type="button">Action</button>
                        </div>
                        <input class="form-control" type="text">
                    </div>
                    <div class="input-group margin">
                        <input class="form-control" type="text">
                        <span class="input-group-btn">
                      <button class="btn btn-info btn-flat" type="button">Go!</button>
                    </span>
                    </div>
                </Box>
            </div>
            <div class="col-md-6">
                <Box type="transparent" title="Split buttons" icon="">
                    <div class="margin">
                        <div class="btn-group">
                            <button class="btn btn-default" type="button">Action</button>
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-info" type="button">Action</button>
                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-danger" type="button">Action</button>
                            <button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-success" type="button">Action</button>
                            <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-warning" type="button">Action</button>
                            <button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                            </ul>
                        </div>
                    </div>
                </Box>
            </div>
        </div>

        <Box type="primary" title="Application Buttons" icon="">
            <p>Add the classes <code>.btn.btn-app</code> to an <code>&lt;a&gt;</code> tag to achieve the
                following:</p>
            <a class="btn btn-app"><i class="fa fa-edit"></i> Edit</a>
            <a class="btn btn-app"><i class="fa fa-play"></i> Play</a>
            <a class="btn btn-app"><i class="fa fa-repeat"></i> Repeat</a>
            <a class="btn btn-app"><i class="fa fa-pause"></i> Pause</a>
            <a class="btn btn-app"><i class="fa fa-save"></i> Save</a>
            <a class="btn btn-app">
                <span class="badge bg-yellow">3</span>
                <i class="fa fa-bullhorn"></i> Notifications
            </a>
            <a class="btn btn-app">
                <span class="badge bg-green">300</span>
                <i class="fa fa-barcode"></i> Products
            </a>
            <a class="btn btn-app">
                <span class="badge bg-purple">891</span>
                <i class="fa fa-users"></i> Users
            </a>
            <a class="btn btn-app">
                <span class="badge bg-teal">67</span>
                <i class="fa fa-inbox"></i> Orders
            </a>
            <a class="btn btn-app">
                <span class="badge bg-aqua">12</span>
                <i class="fa fa-envelope"></i> Inbox
            </a>
            <a class="btn btn-app">
                <span class="badge bg-red">531</span>
                <i class="fa fa-heart-o"></i> Likes
            </a>
        </Box>
    </section>
</template>

<style>

</style>
