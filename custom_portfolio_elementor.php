<?php 
/*
 * Plugin Name:       Custom Portfolio Elementor
 * Plugin URI:        https://wppatch.com/how-to-modify-portfolio-widget-in-elementor-pro/
 * Description:       Small plugin to modify the default layout of Portfolio Widget in Elementor.
 * Version:           1.0
 * Author:            wppatch.com
 * Author URI:        https://wppatch.com/

===== Desired Layout =====

* Remove thumbnail overlay including link.
* Display Post Title under Featured Image.
* Display Post Excerpt under Post Title with read more link. 
* Add post link to Featured image and Post Title
*/

 // exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

//extends default portfolio widget
add_action( 'elementor/widgets/register', function( $widgets_manager ) {
	class New_Portfolio extends \ElementorPro\Modules\Posts\Widgets\Portfolio {
		
		protected function render_post_header() {
			global $post;
	
			$tags_classes = array_map( function( $tag ) {
				return 'elementor-filter-' . $tag->term_id;
			}, $post->tags );
	
			$classes = [
				'elementor-portfolio-item',
				'elementor-post',
				implode( ' ', $tags_classes ),
			];
	
			// PHPCS - `get_permalink` is safe.
			?>
			<article <?php post_class( $classes ); ?>>
			<?php
		}

		protected function render_post_footer() {
			?>
			</article>
			<?php
		}

		protected function render_post_excerpt() { ?>
			<div class="elementor-portfolio-item__excerpt">
				<?php
					$more = sprintf( '<a class="more" href="%1$s">%2$s</a>', get_permalink( get_the_ID() ), __( 'MORE', 'text_domain' ) );
					echo wp_trim_words( get_the_excerpt(), 10, $more );
				?>
			</div>			
			<?php
		}

        protected function render_post_link_header() { ?>
            <a class="new_portfolio_post_link" href="<?php echo esc_url( get_permalink() ); ?>">
            <?php 
        }

        protected function render_post_link_footer() { ?>
                </a>
            <?php
        }

		protected function render_post() {
			$this->render_post_header();
			$this->render_post_link_header();
			$this->render_thumbnail();
			$this->render_post_link_footer();
			$this->render_overlay_header();
			$this->render_post_link_header();
			$this->render_title();
			$this->render_post_link_footer();
			$this->render_post_excerpt();
			//$this->render_categories_names();
			$this->render_overlay_footer();
			$this->render_post_footer();
		}
	}

	$widgets_manager->register( new \New_Portfolio() );
}, 250 );

//add some styling. feel free to modify as per your needs
add_action( 'wp_head','wppatch_custom_header_styles', 99 );
function wppatch_custom_header_styles() { 
    ?>
    <style type="text/css">
        .elementor-widget-portfolio .elementor-portfolio.elementor-has-item-ratio .elementor-post__thumbnail,
        .elementor-widget-portfolio .elementor-portfolio.elementor-has-item-ratio .elementor-post__thumbnail img {
            position: relative;
            top: auto;
            bottom: auto;
            left: auto;
            transform: none;
            min-width: 100%;
        }
        .elementor-widget-portfolio .elementor-portfolio-item__overlay {
            position: relative;
            opacity: 1;
            top: auto;
            bottom: auto;
            padding: 15px 0;
            text-align: left;
            background-color: transparent;
        }
        .elementor-widget-portfolio .elementor-portfolio-item__title {
            color: #333;
            padding-bottom: 15px;
        }
        .elementor-widget-portfolio .more {
            font-weight: bold;
            text-decoration: underline !important;
        }
    </style>
<?php 
}