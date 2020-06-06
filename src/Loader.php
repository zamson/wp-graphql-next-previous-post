<?php

namespace WPGraphQL\Extensions\NextPreviousPost;

use WPGraphQL\AppContext;
use WPGraphQL\Data\DataSource;
use WPGraphQL\Model\Post;

/**
 * Class Laoder
 *
 * This class allows you to see the next and previous posts in the 'post' type.
 *
 * @package WNextPreviousPost
 * @since   0.1.0
 */
class Loader
{
    public static function init()
    {
        define('WP_GRAPHQL_NEXT_PREVIOUS_POST', 'initialized');
        (new Loader())->bind_hooks();
    }

    public function bind_hooks()
    {
        add_action(
            'graphql_register_types',
            [$this, 'npp_action_register_types'],
            9,
            0
        );

    }

    public function npp_action_register_types()
    {
        register_graphql_field('Work', 'next', [
            'type' => 'Work',
            'description' => __(
                'Next work post'
            ),
            'resolve' => function (Post $post, array $args, AppContext $context) {
                global $post;

                // get post
                $post = get_post($post->ID, OBJECT);

                // setup global $post variable
                setup_postdata($post);

                $next = get_next_post();

                wp_reset_postdata();

                return DataSource::resolve_post_object($next->ID, $context);
            },
        ]);

        register_graphql_field('Work', 'previous', [
            'type' => 'Work',
            'description' => __(
                'Previous work post'
            ),

            'resolve' => function (Post $post, array $args, AppContext $context) {
                global $post;

                // get post
                $post = get_post($post->ID, OBJECT);

                // setup global $post variable
                setup_postdata($post);

                $prev = get_previous_post();

                wp_reset_postdata();

                return DataSource::resolve_post_object($prev->ID, $context);
            },
        ]);
    }
}
