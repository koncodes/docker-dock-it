<?php
add_action('init', function(){
    // categories can make it easier for users to find your patterns
    register_block_pattern_category(
        'theme',
        array( 'label' => 'Author Theme'),
    );

    // register as many blocks as you want by duplicating this part
    register_block_pattern(
        'authortheme/author-front-page',
        array(
            'title'       => 'Author Front Page',
            'description' => 'Author front page layout.',
            'categories' => ['theme'], // needs to match an existing category
            'keywords' => ['front page', 'author theme'],
            'content'     => '<!-- wp:group {"className":"font-page-main-con","layout":{"type":"constrained"}} -->
<div class="wp-block-group font-page-main-con"><!-- wp:group {"tagName":"section","className":"front-page-main container","layout":{"type":"constrained"}} -->
<section class="wp-block-group front-page-main container"><!-- wp:heading -->
<h2 class="wp-block-heading"><strong>Header</strong> Header Subtitle</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Header</h3>
<!-- /wp:heading -->

<!-- wp:group {"className":"random-review-con","layout":{"type":"constrained"}} -->
<div class="wp-block-group random-review-con"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Header</h3>
<!-- /wp:heading -->

<!-- wp:shortcode -->
[random-review][/random-review]
<!-- /wp:shortcode --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"front-page-main-text","layout":{"type":"constrained"}} -->
<div class="wp-block-group front-page-main-text"><!-- wp:paragraph -->
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In vitae diam urna. Fusce et faucibus felis. Donec libero urna, elementum et ullamcorper ac, tincidunt sit amet velit.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Curabitur vitae diam sit amet velit efficitur lacinia vitae euismod turpis. Donec quis convallis massa, vitae hendrerit leo. Nulla felis orci, posuere ut scelerisque ac, aliquet sed nisi. Suspendisse quis tortor a purus vulputate luctus. Pellentesque ullamcorper velit ac facilisis posuere. Nullam finibus semper maximus. Cras sollicitudin mattis tellus. Donec faucibus, nibh eget congue feugiat, urna odio blandit metus, ut molestie nisi metus eu lacus. Ut vitae quam a sapien hendrerit rhoncus. Aliquam sodales, arcu et aliquam viverra, ex nisl accumsan nunc, eget ultrices nulla sapien ut lorem. Praesent nec justo ipsum.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></section>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"font-page-recent-books-con","layout":{"type":"constrained"}} -->
<div class="wp-block-group font-page-recent-books-con"><!-- wp:group {"tagName":"section","className":"front-page-recent-books container","layout":{"type":"constrained"}} -->
<section class="wp-block-group front-page-recent-books container"><!-- wp:heading -->
<h2 class="wp-block-heading">Header</h2>
<!-- /wp:heading -->

<!-- wp:shortcode -->
[recent-books posts_per_page="3"]
<!-- /wp:shortcode --></section>
<!-- /wp:group --></div>
<!-- /wp:group -->',
            // or 'filePath'     => '...',
        )
    );
});