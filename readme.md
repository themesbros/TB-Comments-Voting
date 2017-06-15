# TB Comments Voting

Extends WordPress comments and adds comment voting feature (like / dislike) for each comment.

## Installation

1. Upload `tb-comments-voting` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to "Comments > TB Comments Voting" in the admin to set options.

## How do I use it? =

After plugin activation go to "Comments > TB Comments Voting" in the admin to set options. You'll be able to choose appearance position:

* Before comment
* After comment
* Custom position

If you choose custom position, you'll have to place a function call in your comments template file like this:
`<?php if ( function_exists( 'tbcv_shortcode_voting' ) ) { echo tbcv_shortcode_voting(); }; ?>`
or via shortcode
`<?php echo do_shortcode( '[tbcv_voting]' ); ?>`

## Will it work with my theme?

The plugin should work fine with any theme that's coded to WordPress standards.