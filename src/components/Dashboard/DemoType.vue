<script>/*!
 * WofhTools
 * Component: DemoType.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {
    name: 'DemoType',
};
</script>

<template>
    <div>
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

        <p class="lead">Lead to emphasize importance</p>
        <p class="text-green">Text green to emphasize success</p>
        <p class="text-aqua">Text aqua to emphasize info</p>
        <p class="text-light-blue">Text light blue to emphasize info (2)</p>
        <p class="text-red">Text red to emphasize danger</p>
        <p class="text-yellow">Text yellow to emphasize warning</p>
        <p class="text-muted">Text muted to emphasize general</p>

        <div class="row">
            <div class="col-sm-12">
                <h3 class="type-title">Block Quotes</h3>

                <blockquote>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <small>Someone famous in <cite title="Source Title">Source Title</cite></small>
                </blockquote>

                <h3 class="type-title">Block Quotes Pulled Right</h3>

                <blockquote class="pull-right">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <small>Someone famous in <cite title="Source Title">Source Title</cite></small>
                </blockquote>
            </div>
        </div>

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
        </div>

        <h3 class="type-title">Description</h3>

        <dl>
            <dt>Description lists</dt>
            <dd>A description list is perfect for defining terms.</dd>
            <dt>Euismod</dt>
            <dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
            <dd>Donec id elit non mi porta gravida at eget metus.</dd>
            <dt>Malesuada porta</dt>
            <dd>Etiam porta sem malesuada magna mollis euismod.</dd>
        </dl>

        <h3 class="type-title">Description Horizontal</h3>

        <dl class="dl-horizontal">
            <dt>Description lists</dt>
            <dd>A description list is perfect for defining terms.</dd>
            <dt>Euismod</dt>
            <dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
            <dd>Donec id elit non mi porta gravida at eget metus.</dd>
            <dt>Malesuada porta</dt>
            <dd>Etiam porta sem malesuada magna mollis euismod.</dd>
            <dt>Felis euismod semper eget lacinia</dt>
            <dd>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo.</dd>
        </dl>
    </div>
</template>

<style lang="scss">
    .type-title {
        position: relative;

        &::after {
            content: '';
            position: absolute;
            left: 0;
            top: 100%;
            width: 100%;
            height: 1px;
            background: currentColor;
            opacity: 0.4;
            margin-top: -1px;
        }
    }
</style>
