<!--
  WofhTools
  DemoButtons.vue
  (c) 2019 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import 'vue-awesome/icons/regular/heart';

import Box from '@/components/Widgets/Box';


export default {
  name: 'DemoButtons',

  components: {
    Box,
  },

  data: () => ({
    buttons: [
      { cls: 'btn_default', txt: 'Default', disabled: false },
      { cls: 'btn_primary', txt: 'Primary', disabled: false },
      { cls: 'btn_info', txt: 'Info', disabled: false },
      { cls: 'btn_success', txt: 'Success', disabled: false },
      { cls: 'btn_warning', txt: 'Warning', disabled: false },
      { cls: 'btn_danger', txt: 'Danger', disabled: false },

      { cls: 'btn_default active', txt: 'Active:', disabled: false },
      { cls: 'btn_primary active', txt: 'Primary', disabled: false },
      { cls: 'btn_info active', txt: 'Info', disabled: false },
      { cls: 'btn_success active', txt: 'Success', disabled: false },
      { cls: 'btn_warning active', txt: 'Warning', disabled: false },
      { cls: 'btn_danger active', txt: 'Danger', disabled: false },

      { cls: 'btn_default focus', txt: 'Focus:', disabled: false },
      { cls: 'btn_primary focus', txt: 'Primary', disabled: false },
      { cls: 'btn_info focus', txt: 'Info', disabled: false },
      { cls: 'btn_success focus', txt: 'Success', disabled: false },
      { cls: 'btn_warning focus', txt: 'Warning', disabled: false },
      { cls: 'btn_danger focus', txt: 'Danger', disabled: false },

      { cls: 'btn_default', txt: 'Disabled:', disabled: true },
      { cls: 'btn_primary', txt: 'Primary', disabled: true },
      { cls: 'btn_info', txt: 'Info', disabled: true },
      { cls: 'btn_success', txt: 'Success', disabled: true },
      { cls: 'btn_warning', txt: 'Warning', disabled: true },
      { cls: 'btn_danger', txt: 'Danger', disabled: true },

      { cls: 'btn_default', txt: 'Sizes:', disabled: false },
      { cls: 'btn_primary btn_lg', txt: 'Large', disabled: false },
      { cls: 'btn_info', txt: 'Normal', disabled: false },
      { cls: 'btn_success btn_sm', txt: 'Small', disabled: false },
      { cls: 'btn_warning btn_block', txt: 'Block', disabled: false },
      { cls: 'btn_danger btn_block', txt: 'Block', disabled: false },
    ],
  }),

};
</script>

<template>
  <section class="">

    <Box type="primary" title="Buttons" icon="edit">
      <h3 class="type-title">Button tag &lt;button&gt;</h3>
      <div class="demo-buttons">
        <div v-for="btn in buttons">
          <button
            class="btn"
            :class="btn.cls"
            :disabled="btn.disabled"
            type="button"
          >{{ btn.txt }}</button>
        </div>
      </div>

      <h3 class="type-title">Anchor tag &lt;a&gt;</h3>
      <div class="demo-buttons">
        <div v-for="(btn, index) in buttons">
          <a class="btn"
            href="#"
            :class="btn.cls"
            :disabled="btn.disabled"
            role="button"
          >{{ btn.txt }}</a>
        </div>
      </div>
    </Box>

    <Box type="primary" title="Horizontal Button Group" icon="">
      <p>
        Horizontal button groups are easy to create.
        Just add your buttons inside <code>&lt;div class="control-group"&gt;&lt;/div&gt;</code>
      </p>
      <div class="demo-buttons col3">
        <div>
          <div class="control-group">
            <button class="btn btn_default" type="button">Left</button>
            <button class="btn btn_danger" type="button">Middle</button>
            <button class="btn btn_default" type="button">Right</button>
          </div>
        </div>
        <div>
          <div class="control-group">
            <button class="btn btn_default" type="button">
              <FaIcon name="align-left"/>
            </button>
            <button class="btn btn_default" type="button">
              <FaIcon name="align-center"/>
            </button>
            <button class="btn btn_default" type="button">
              <FaIcon name="align-right"/>
            </button>
          </div>
        </div>
        <div>
          <div class="control-group">
            <button class="btn btn_default" type="button">1</button>
            <button class="btn btn_default" type="button">2</button>

            <div class="btn-group">
              <button class="btn btn_default dropdown-toggle"
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
        </div>
      </div>
    </Box>

    <div class="row">
      <div class="col-md-6">
        <Box type="transparent" title="Button Addon" icon="">
          <div class="input-group margin">
            <div class="input-group-btn">
              <button class="btn btn_default dropdown-toggle" type="button" data-toggle="dropdown">
                Action
                <FaIcon name="caret-down"/>
              </button>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li><a href="#">Separated link</a></li>
              </ul>
            </div>
            <Inputbox/>
          </div>
          <div class="input-group margin">
            <div class="input-group-btn">
              <button class="btn btn_danger" type="button">Action</button>
            </div>
            <Inputbox/>
          </div>
          <div class="input-group margin">
            <Inputbox/>
            <span class="input-group-btn">
               <button class="btn btn_info btn_flat" type="button">Go!</button>
            </span>
          </div>
        </Box>
      </div>
      <div class="col-md-6">
        <Box type="transparent" title="Split buttons" icon="">
          <div class="margin">
            <div class="btn_group">
              <button class="btn btn_default" type="button">Action</button>
              <button class="btn btn_default dropdown-toggle" type="button" data-toggle="dropdown">
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
      <p>Add the classes <code>.btn.btn_app</code> to an <code>&lt;a&gt;</code> tag to achieve the following:</p>
      <a class="btn btn_app"><FaIcon name="edit"/> Edit</a>
      <a class="btn btn_app"><FaIcon name="play"/> Play</a>
      <a class="btn btn_app"><FaIcon name="redo-alt"/> Repeat</a>
      <a class="btn btn_app"><FaIcon name="pause"/> Pause</a>
      <a class="btn btn_app"><FaIcon name="save"/> Save</a>
      <a class="btn btn_app">
        <span class="badge bg-yellow">3</span>
        <FaIcon name="bullhorn"/> Notifications
      </a>
      <a class="btn btn_app">
        <span class="badge bg-green">300</span>
        <FaIcon name="barcode"/> Products
      </a>
      <a class="btn btn_app">
        <span class="badge bg-purple">891</span>
        <FaIcon name="users"/> Users
      </a>
      <a class="btn btn_app">
        <span class="badge bg-teal">67</span>
        <FaIcon name="inbox"/> Orders
      </a>
      <a class="btn btn_app">
        <span class="badge bg-aqua">12</span>
        <FaIcon name="envelope"/> Inbox
      </a>
      <a class="btn btn_app">
        <span class="badge bg-red">531</span>
        <FaIcon name="regular.heart"/> Likes
      </a>
    </Box>
  </section>
</template>

<style lang="scss">
.demo-buttons {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  background: $background-base;
  grid-gap: rhythm(1);
  padding: rhythm(0.5);

  > div {
    display: flex;
    justify-content: center;
    align-items: flex-start;
  }

  &.col3 {
    grid-template-columns: repeat(3, 1fr);
  }
}
</style>
