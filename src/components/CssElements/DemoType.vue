<!--
  WofhTools
  Component: DemoType.vue
  (c) 2019 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
export default {
  name: 'DemoType',
};
</script>

<template>
  <div class="pb-2">
    <h3 class="type-title">Headlines</h3>

    <h1>h1. Bootstrap heading</h1>
    <h2>h2. Bootstrap heading</h2>
    <h3>h3. Bootstrap heading</h3>
    <h4>h4. Bootstrap heading</h4>
    <h5>h5. Bootstrap heading</h5>
    <h6>h6. Bootstrap heading</h6>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate et ipsa iure maiores
      perferendis perspiciatis quae quod recusandae sunt voluptas. Aliquid facilis, itaque laborum
      odio omnis porro tempore vel velit.</p>

    <h3 class="type-title">Text Emphasis</h3>

    <p class="lead">Lead to emphasize importance Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi
      ducimus id laborum reprehenderit? Aspernatur deleniti dolore libero nemo nihil! Aut cum facere illo
      molestias officiis omnis rerum similique voluptatem. Lorem ipsum dolor sit amet, consectetur adipisicing
      elit. Blanditiis in, magnam odio perferendis sint veniam.</p>
    <p class="text-green">Text green to emphasize success</p>
    <p class="text-aqua">Text aqua to emphasize info</p>
    <p class="text-light-blue">Text light blue to emphasize info (2)</p>
    <p class="text-red">Text red to emphasize danger</p>
    <p class="text-yellow">Text yellow to emphasize warning</p>
    <p class="text-muted">Text muted to emphasize general</p>

    <div class="row">
      <div class="col-sm-6">
        <h3 class="type-title">Block Quotes</h3>

        <blockquote class="blockquote">
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
          <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
        </blockquote>
      </div>
      <div class="col-sm-6">

        <h3 class="type-title">Block Quotes Pulled Right</h3>

        <blockquote class="blockquote-reverse">
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
          <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
        </blockquote>
      </div>
    </div>

    <h3 class="type-title">Block Quotes Nested</h3>

    <blockquote>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
      <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>

      <blockquote>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
        <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>

        <blockquote>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
          <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
        </blockquote>
      </blockquote>
    </blockquote>

    <div class="row">
      <div class="col-lg-4">
        <h3 class="type-title">Unordered List</h3>
        <ul>
          <li>Lorem ipsum dolor sit amet</li>
          <li>Consectetur adipiscing elit</li>
          <li>Integer molestie lorem at massa</li>
          <li>Facilisis in pretium nisl aliquet</li>
          <li>Nulla volutpat aliquam velit
            <ul>
              <li>Phasellus iaculis neque</li>
              <li>Purus sodales ultricies</li>
              <li>Vestibulum laoreet porttitor sem</li>
              <li>Ac tristique libero volutpat at</li>
            </ul>
          </li>
          <li>Faucibus porta lacus fringilla vel</li>
          <li>Aenean sit amet erat nunc</li>
          <li>Eget porttitor lorem</li>
        </ul>
      </div>
      <div class="col-lg-4">
        <h3 class="type-title">Ordered Lists</h3>
        <ol>
          <li>Lorem ipsum dolor sit amet</li>
          <li>Consectetur adipiscing elit</li>
          <li>Integer molestie lorem at massa</li>
          <li>Facilisis in pretium nisl aliquet</li>
          <li>Nulla volutpat aliquam velit
            <ol>
              <li>Phasellus iaculis neque</li>
              <li>Purus sodales ultricies</li>
              <li>Vestibulum laoreet porttitor sem</li>
              <li>Ac tristique libero volutpat at</li>
            </ol>
          </li>
          <li>Faucibus porta lacus fringilla vel</li>
          <li>Aenean sit amet erat nunc</li>
          <li>Eget porttitor lorem</li>
        </ol>
      </div>
      <div class="col-lg-4">
        <h3 class="type-title">Unstyled List</h3>
        <ul class="list-unstyled">
          <li>Lorem ipsum dolor sit amet</li>
          <li>Consectetur adipiscing elit</li>
          <li>Integer molestie lorem at massa</li>
          <li>Facilisis in pretium nisl aliquet</li>
          <li>Nulla volutpat aliquam velit
            <ul class="list-unstyled">
              <li>Phasellus iaculis neque</li>
              <li>Purus sodales ultricies</li>
              <li>Vestibulum laoreet porttitor sem</li>
              <li>Ac tristique libero volutpat at</li>
            </ul>
          </li>
          <li>Faucibus porta lacus fringilla vel</li>
          <li>Aenean sit amet erat nunc</li>
          <li>Eget porttitor lorem</li>
        </ul>
      </div>
    </div>

    <h3 class="type-title">Description List</h3>

    <dl>
      <dt>Description lists</dt>
      <dd>A description list is perfect for defining terms.</dd>
      <dt>Euismod</dt>
      <dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.<br>
        Donec id elit non mi porta gravida at eget metus.
      </dd>
      <dt>Malesuada porta</dt>
      <dd>Etiam porta sem malesuada magna mollis euismod.</dd>
    </dl>

    <h3 class="type-title">Description Horizontal</h3>

    <dl class="dl-horizontal">
      <dt>Description lists</dt>
      <dd>A description list is perfect for defining terms.</dd>
      <dt>Euismod</dt>
      <dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.<br>
        Donec id elit non mi porta gravida at eget metus.
      </dd>
      <dt>Malesuada porta lorem</dt>
      <dd>Etiam porta sem malesuada magna mollis euismod.</dd>
    </dl>

    <h2 class="page-header">AdminLTE Custom Tabs</h2>
    <div class="row">
      <div class="col-md-6">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Tab 1</a></li>
            <li><a href="#tab_2" data-toggle="tab">Tab 2</a></li>
            <li><a href="#tab_3" data-toggle="tab">Tab 3</a></li>
            <li class="dropdown">
              <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                Dropdown <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li role="presentation"><a href="#" role="menuitem" tabindex="-1">Action</a>
                </li>
                <li role="presentation"><a href="#" role="menuitem" tabindex="-1">Another
                  action</a></li>
                <li role="presentation"><a href="#" role="menuitem" tabindex="-1">Something
                  else
                  here</a></li>
                <li class="divider" role="presentation"></li>
                <li role="presentation"><a href="#" role="menuitem" tabindex="-1">Separated
                  link</a></li>
              </ul>
            </li>
            <li class="pull-right">
              <a class="text-muted" href="#">
                <FaIcon name="cog"/>
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
              <b>How to use:</b>

              <p>Exactly like the original bootstrap tabs except you should use
                the custom wrapper <code>.nav-tabs-custom</code> to achieve this style.</p>
              A wonderful serenity has taken possession of my entire soul,
              like these sweet mornings of spring which I enjoy with my whole heart.
              I am alone, and feel the charm of existence in this spot,
              which was created for the bliss of souls like mine. I am so happy,
              my dear friend, so absorbed in the exquisite sense of mere tranquil existence,
              that I neglect my talents. I should be incapable of drawing a single stroke
              at the present moment; and yet I feel that I never was a greater artist than
              now.
            </div>
            <div class="tab-pane" id="tab_2">
              The European languages are members of the same family. Their separate existence
              is a
              myth.
              For science, music, sport, etc, Europe uses the same vocabulary. The languages
              only
              differ
              in their grammar, their pronunciation and their most common words. Everyone
              realizes
              why a
              new common language would be desirable: one could refuse to pay expensive
              translators. To
              achieve this, it would be necessary to have uniform grammar, pronunciation and
              more
              common
              words. If several languages coalesce, the grammar of the resulting language is
              more
              simple
              and regular than that of the individual languages.
            </div>
            <div class="tab-pane" id="tab_3">
              Lorem Ipsum is simply dummy text of the printing and typesetting industry.
              Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
              when an unknown printer took a galley of type and scrambled it to make a type
              specimen book.
              It has survived not only five centuries, but also the leap into electronic
              typesetting,
              remaining essentially unchanged. It was popularised in the 1960s with the
              release of
              Letraset
              sheets containing Lorem Ipsum passages, and more recently with desktop
              publishing
              software
              like Aldus PageMaker including versions of Lorem Ipsum.
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs pull-right">
            <li class="active"><a href="#tab_1-1" data-toggle="tab">Tab 1</a></li>
            <li><a href="#tab_2-2" data-toggle="tab">Tab 2</a></li>
            <li><a href="#tab_3-2" data-toggle="tab">Tab 3</a></li>
            <li class="dropdown">
              <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                Dropdown <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li role="presentation"><a href="#" role="menuitem" tabindex="-1">Action</a>
                </li>
                <li role="presentation"><a href="#" role="menuitem" tabindex="-1">Another
                  action</a></li>
                <li role="presentation"><a href="#" role="menuitem" tabindex="-1">Something
                  else
                  here</a></li>
                <li class="divider" role="presentation"></li>
                <li role="presentation"><a href="#" role="menuitem" tabindex="-1">Separated
                  link</a></li>
              </ul>
            </li>
            <li class="pull-left header">
              <FaIcon name="th"/>
              Custom Tabs
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_1-1">
              <b>How to use:</b>

              <p>Exactly like the original bootstrap tabs except you should use
                the custom wrapper <code>.nav-tabs-custom</code> to achieve this style.</p>
              A wonderful serenity has taken possession of my entire soul,
              like these sweet mornings of spring which I enjoy with my whole heart.
              I am alone, and feel the charm of existence in this spot,
              which was created for the bliss of souls like mine. I am so happy,
              my dear friend, so absorbed in the exquisite sense of mere tranquil existence,
              that I neglect my talents. I should be incapable of drawing a single stroke
              at the present moment; and yet I feel that I never was a greater artist than
              now.
            </div>
            <div class="tab-pane" id="tab_2-2">
              The European languages are members of the same family. Their separate existence
              is a
              myth.
              For science, music, sport, etc, Europe uses the same vocabulary. The languages
              only
              differ
              in their grammar, their pronunciation and their most common words. Everyone
              realizes
              why a
              new common language would be desirable: one could refuse to pay expensive
              translators. To
              achieve this, it would be necessary to have uniform grammar, pronunciation and
              more
              common
              words. If several languages coalesce, the grammar of the resulting language is
              more
              simple
              and regular than that of the individual languages.
            </div>
            <div class="tab-pane" id="tab_3-2">
              Lorem Ipsum is simply dummy text of the printing and typesetting industry.
              Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
              when an unknown printer took a galley of type and scrambled it to make a type
              specimen book.
              It has survived not only five centuries, but also the leap into electronic
              typesetting,
              remaining essentially unchanged. It was popularised in the 1960s with the
              release of
              Letraset
              sheets containing Lorem Ipsum passages, and more recently with desktop
              publishing
              software
              like Aldus PageMaker including versions of Lorem Ipsum.
            </div>
          </div>
        </div>
      </div>
    </div>
    <h2 class="page-header">Progress Bars</h2>
    <div class="row">
      <div class="col-md-6">
        <div class="box box-solid">
          <div class="box-header with-border">
            <h3 class="type-title">Progress Bars Different Sizes</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <p><code>.progress</code></p>

            <div class="progress">
              <div class="progress-bar progress-bar-primary progress-bar-striped"
                role="progressbar"
                aria-valuenow="40"
                aria-valuemin="0"
                aria-valuemax="100"
                style="width: 40%">
                <span class="sr-only">40% Complete (success)</span>
              </div>
            </div>
            <p>Class: <code>.sm</code></p>

            <div class="progress progress-sm active">
              <div class="progress-bar progress-bar-success progress-bar-striped"
                role="progressbar"
                aria-valuenow="20"
                aria-valuemin="0"
                aria-valuemax="100"
                style="width: 20%">
                <span class="sr-only">20% Complete</span>
              </div>
            </div>
            <p>Class: <code>.xs</code></p>

            <div class="progress progress-xs">
              <div class="progress-bar progress-bar-warning progress-bar-striped"
                role="progressbar"
                aria-valuenow="60"
                aria-valuemin="0"
                aria-valuemax="100"
                style="width: 60%">
                <span class="sr-only">60% Complete (warning)</span>
              </div>
            </div>
            <p>Class: <code>.xxs</code></p>

            <div class="progress progress-xxs">
              <div class="progress-bar progress-bar-danger progress-bar-striped"
                role="progressbar"
                aria-valuenow="60"
                aria-valuemin="0"
                aria-valuemax="100"
                style="width: 60%">
                <span class="sr-only">60% Complete (warning)</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="box box-solid">
          <div class="box-header with-border">
            <h3 class="type-title">Progress bars</h3>
          </div>
          <div class="box-body">
            <div class="progress">
              <div class="progress-bar progress-bar-green"
                role="progressbar"
                aria-valuenow="40"
                aria-valuemin="0"
                aria-valuemax="100"
                style="width: 40%">
                <span class="sr-only">40% Complete (success)</span>
              </div>
            </div>
            <div class="progress">
              <div class="progress-bar progress-bar-aqua"
                role="progressbar"
                aria-valuenow="20"
                aria-valuemin="0"
                aria-valuemax="100"
                style="width: 20%">
                <span class="sr-only">20% Complete</span>
              </div>
            </div>
            <div class="progress">
              <div class="progress-bar progress-bar-yellow"
                role="progressbar"
                aria-valuenow="60"
                aria-valuemin="0"
                aria-valuemax="100"
                style="width: 60%">
                <span class="sr-only">60% Complete (warning)</span>
              </div>
            </div>
            <div class="progress">
              <div class="progress-bar progress-bar-red"
                role="progressbar"
                aria-valuenow="80"
                aria-valuemin="0"
                aria-valuemax="100"
                style="width: 80%">
                <span class="sr-only">80% Complete</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="box box-solid">
          <div class="box-header with-border">
            <h3 class="type-title">Vertical Progress Bars Different Sizes</h3>
          </div>
          <div class="box-body text-center">
            <p>By adding the class <code>.vertical</code> and <code>.progress-sm</code>,
              <code>.progress-xs</code>
              or
              <code>.progress-xxs</code> we achieve:</p>

            <div class="progress vertical active">
              <div class="progress-bar progress-bar-primary progress-bar-striped"
                role="progressbar"
                aria-valuenow="40"
                aria-valuemin="0"
                aria-valuemax="100"
                style="height: 40%">
                <span class="sr-only">40%</span>
              </div>
            </div>
            <div class="progress vertical progress-sm">
              <div class="progress-bar progress-bar-success"
                role="progressbar"
                aria-valuenow="20"
                aria-valuemin="0"
                aria-valuemax="100"
                style="height: 100%">
                <span class="sr-only">100%</span>
              </div>
            </div>
            <div class="progress vertical progress-xs">
              <div class="progress-bar progress-bar-warning progress-bar-striped"
                role="progressbar"
                aria-valuenow="60"
                aria-valuemin="0"
                aria-valuemax="100"
                style="height: 60%">
                <span class="sr-only">60%</span>
              </div>
            </div>
            <div class="progress vertical progress-xxs">
              <div class="progress-bar progress-bar-info progress-bar-striped"
                role="progressbar"
                aria-valuenow="60"
                aria-valuemin="0"
                aria-valuemax="100"
                style="height: 60%">
                <span class="sr-only">60%</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="box box-solid">
          <div class="box-header with-border">
            <h3 class="type-title">Vertical Progress bars</h3>
          </div>
          <div class="box-body text-center">
            <p>By adding the class <code>.vertical</code> we achieve:</p>

            <div class="progress vertical">
              <div class="progress-bar progress-bar-green"
                role="progressbar"
                aria-valuenow="40"
                aria-valuemin="0"
                aria-valuemax="100"
                style="height: 40%">
                <span class="sr-only">40%</span>
              </div>
            </div>
            <div class="progress vertical">
              <div class="progress-bar progress-bar-aqua"
                role="progressbar"
                aria-valuenow="20"
                aria-valuemin="0"
                aria-valuemax="100"
                style="height: 20%">
                <span class="sr-only">20%</span>
              </div>
            </div>
            <div class="progress vertical">
              <div class="progress-bar progress-bar-yellow"
                role="progressbar"
                aria-valuenow="60"
                aria-valuemin="0"
                aria-valuemax="100"
                style="height: 60%">
                <span class="sr-only">60%</span>
              </div>
            </div>
            <div class="progress vertical">
              <div class="progress-bar progress-bar-red"
                role="progressbar"
                aria-valuenow="80"
                aria-valuemin="0"
                aria-valuemax="100"
                style="height: 80%">
                <span class="sr-only">80%</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <h2 class="page-header">Bootstrap Accordion &amp; Carousel</h2>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-solid">
          <div class="box-header with-border">
            <h3 class="type-title">Collapsible Accordion</h3>
          </div>
          <div class="box-body">
            <div class="box-group" id="accordion">
              <div class="panel box box-primary">
                <div class="box-header with-border">
                  <h4 class="type-title">
                    <a href="#collapseOne"
                      data-toggle="collapse"
                      data-parent="#accordion">
                      Collapsible Group Item #1
                    </a>
                  </h4>
                </div>
                <div class="panel-collapse collapse in" id="collapseOne">
                  <div class="box-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus
                    terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food
                    truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it
                    squid
                    single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer
                    labore wes anderson cred
                    nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo.
                    Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably
                    haven't
                    heard of them accusamus
                    labore sustainable VHS.
                  </div>
                </div>
              </div>
              <div class="panel box box-danger">
                <div class="box-header with-border">
                  <h4 class="type-title">
                    <a href="#collapseTwo"
                      data-toggle="collapse"
                      data-parent="#accordion">
                      Collapsible Group Danger
                    </a>
                  </h4>
                </div>
                <div class="panel-collapse collapse" id="collapseTwo">
                  <div class="box-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus
                    terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food
                    truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it
                    squid
                    single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer
                    labore wes anderson cred
                    nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo.
                    Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably
                    haven't
                    heard of them accusamus
                    labore sustainable VHS.
                  </div>
                </div>
              </div>
              <div class="panel box box-success">
                <div class="box-header with-border">
                  <h4 class="type-title">
                    <a href="#collapseThree"
                      data-toggle="collapse"
                      data-parent="#accordion">
                      Collapsible Group Success
                    </a>
                  </h4>
                </div>
                <div class="panel-collapse collapse" id="collapseThree">
                  <div class="box-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus
                    terry richardson ad squid. 3
                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food
                    truck quinoa nesciunt laborum
                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it
                    squid
                    single-origin coffee nulla
                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer
                    labore wes anderson cred
                    nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo.
                    Leggings occaecat craft beer
                    farm-to-table, raw denim aesthetic synth nesciunt you probably
                    haven't
                    heard of them accusamus
                    labore sustainable VHS.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
