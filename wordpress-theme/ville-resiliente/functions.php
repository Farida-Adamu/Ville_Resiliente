<?php
/**
 * Ville Résiliente — theme functions.
 *
 * @package VilleResiliente
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VR_VERSION', '1.2.0' );

/** Posts shown on the homepage: one featured + the "Autres récits" grid. */
define( 'VR_HOME_COUNT', 7 );

/* ------------------------------------------------------------------
 * Setup
 * ------------------------------------------------------------------ */

function vr_setup() {
	load_theme_textdomain( 'ville-resiliente', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );

	add_image_size( 'vr-lead', 1520, 1013, true );
	add_image_size( 'vr-card', 900, 900, true );

	register_nav_menus(
		array(
			'primary' => __( 'Menu principal (en-tête)', 'ville-resiliente' ),
		)
	);
}
add_action( 'after_setup_theme', 'vr_setup' );

function vr_content_width() {
	$GLOBALS['content_width'] = 760;
}
add_action( 'after_setup_theme', 'vr_content_width', 0 );

function vr_assets() {
	wp_enqueue_style(
		'vr-fonts',
		'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;600;700;800&family=Instrument+Serif:ital@0;1&family=JetBrains+Mono:wght@400;500&family=Newsreader:ital,wght@0,400;0,500;1,400&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'vr-style', get_stylesheet_uri(), array( 'vr-fonts' ), VR_VERSION );
	wp_enqueue_script( 'vr-menu', get_template_directory_uri() . '/assets/menu.js', array(), VR_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'vr_assets' );

function vr_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'vr_resource_hints', 10, 2 );

function vr_favicon() {
	if ( has_site_icon() ) {
		return;
	}
	printf( '<link rel="icon" type="image/png" href="%s" />' . "\n", esc_url( get_template_directory_uri() . '/assets/logo-marque.png' ) );
}
add_action( 'wp_head', 'vr_favicon' );

function vr_excerpt_length() {
	return 32;
}
add_filter( 'excerpt_length', 'vr_excerpt_length' );

function vr_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'vr_excerpt_more' );

/* ------------------------------------------------------------------
 * Customizer (Apparence → Personnaliser → Ville Résiliente)
 * ------------------------------------------------------------------ */

function vr_defaults() {
	return array(
		'vr_hero_title'           => '',
		'vr_hero_subtitle'        => 'Un carnet sur Abidjan, mobilité, mémoire et résilience urbaine. Essais, récits photographiques et notes de terrain.',
		'vr_footer_tagline'       => 'Penser la ville lentement, depuis Abidjan.',
		'vr_byline_place'         => 'Cocody',
		'vr_newsletter_shortcode' => '',
		'vr_newsletter_action'    => '',
	);
}

function vr_opt( $key ) {
	$defaults = vr_defaults();
	return get_theme_mod( $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
}

function vr_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'vr_options',
		array(
			'title'    => __( 'Ville Résiliente', 'ville-resiliente' ),
			'priority' => 30,
		)
	);

	$fields = array(
		'vr_hero_title'           => array( __( "Titre de l'accueil (vide = nom du site)", 'ville-resiliente' ), 'text', 'sanitize_text_field' ),
		'vr_hero_subtitle'        => array( __( "Sous-titre de l'accueil", 'ville-resiliente' ), 'textarea', 'sanitize_textarea_field' ),
		'vr_footer_tagline'       => array( __( 'Phrase du pied de page', 'ville-resiliente' ), 'text', 'sanitize_text_field' ),
		'vr_byline_place'         => array( __( 'Lieu de signature (« Aldo, depuis … »)', 'ville-resiliente' ), 'text', 'sanitize_text_field' ),
		'vr_newsletter_shortcode' => array( __( "Infolettre : shortcode d'un plugin (ex. [mc4wp_form id=\"12\"])", 'ville-resiliente' ), 'text', 'sanitize_text_field' ),
		'vr_newsletter_action'    => array( __( "Infolettre : ou URL d'envoi du formulaire (Mailchimp, Brevo…)", 'ville-resiliente' ), 'url', 'esc_url_raw' ),
	);

	$defaults = vr_defaults();
	foreach ( $fields as $id => $field ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $defaults[ $id ],
				'sanitize_callback' => $field[2],
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $field[0],
				'section' => 'vr_options',
				'type'    => $field[1],
			)
		);
	}
}
add_action( 'customize_register', 'vr_customize_register' );

/* ------------------------------------------------------------------
 * Pages created on activation (only when missing)
 * ------------------------------------------------------------------ */

function vr_page_slugs() {
	return array(
		'about'   => array( 'a-propos', 'apropos', 'about', 'qui-suis-je', 'a-propos-de-nous' ),
		'gallery' => array( 'galerie', 'gallery', 'photos' ),
	);
}

function vr_find_page( $kind ) {
	$slugs = vr_page_slugs();
	foreach ( $slugs[ $kind ] as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page && 'trash' !== $page->post_status ) {
			return $page;
		}
	}
	return null;
}

function vr_create_pages() {
	if ( ! vr_find_page( 'about' ) ) {
		wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => 'À propos',
				'post_name'    => 'a-propos',
				'post_content' => vr_about_content(),
			)
		);
	}

	if ( ! vr_find_page( 'gallery' ) ) {
		wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => 'Galerie',
				'post_name'    => 'galerie',
				'post_content' => vr_gallery_content(),
			)
		);
	}
}
add_action( 'after_switch_theme', 'vr_create_pages' );

function vr_about_content() {
	return <<<'HTML'
<!-- wp:paragraph -->
<p>Ville Résiliente est un carnet sur Abidjan. On y écrit lentement, à hauteur de trottoir, sur ce qui fait tenir une ville de six millions d'habitants : ses transports, ses repères, son énergie, et la manière dont elle se réinvente chaque matin.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Ce que l'on trouve ici</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Des essais, des récits photographiques et des notes de terrain. Des textes sur la mobilité, du gbaka à la pinasse ; sur le patrimoine, des monuments aux gestes quotidiens ; sur l'énergie et le climat, et sur ce que veut dire, concrètement, une ville résiliente.</p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><!-- wp:paragraph -->
<p>Une ville qui plie sans rompre, c'est d'abord une ville qui sait par où passer.</p>
<!-- /wp:paragraph --></blockquote>
<!-- /wp:quote -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Écrire</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Une remarque, une correction, une histoire à partager ? Écrivez-nous, chaque message est lu.</p>
<!-- /wp:paragraph -->
HTML;
}

function vr_gallery_content() {
	return <<<'HTML'
<!-- wp:paragraph -->
<p>Abidjan en images : la lagune, les gares de gbaka, les façades du Plateau et les marchés de Treichville. Les photographies du carnet, rassemblées au fil des récits.</p>
<!-- /wp:paragraph -->
HTML;
}

/* ------------------------------------------------------------------
 * Navigation
 * ------------------------------------------------------------------ */

/**
 * Links for the header nav: the "Menu principal" menu if one is assigned,
 * otherwise the About and Gallery pages.
 *
 * @return array[] Each item: url, label, current.
 */
function vr_nav_links() {
	$links     = array();
	$locations = get_nav_menu_locations();

	if ( ! empty( $locations['primary'] ) ) {
		$items = wp_get_nav_menu_items( $locations['primary'] );
		if ( $items ) {
			_wp_menu_item_classes_by_context( $items );
			foreach ( $items as $item ) {
				if ( (int) $item->menu_item_parent ) {
					continue;
				}
				$links[] = array(
					'url'     => $item->url,
					'label'   => $item->title,
					'current' => in_array( 'current-menu-item', (array) $item->classes, true ),
				);
			}
			return $links;
		}
	}

	foreach ( array( 'about' => 'À propos', 'gallery' => 'Galerie' ) as $kind => $label ) {
		$page = vr_find_page( $kind );
		if ( $page && 'publish' === $page->post_status ) {
			$links[] = array(
				'url'     => get_permalink( $page ),
				'label'   => $label,
				'current' => is_page( $page->ID ),
			);
		}
	}
	return $links;
}

/** URL of the full list of posts ("Le carnet"). */
function vr_blog_url() {
	$posts_page = (int) get_option( 'page_for_posts' );
	return $posts_page ? get_permalink( $posts_page ) : home_url( '/' );
}

/**
 * When the homepage lists latest posts, page 2 onward starts after the
 * posts already shown on the homepage instead of after one full page.
 */
function vr_home_paged_offset( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_home() || ! $query->is_paged() ) {
		return;
	}
	if ( 'posts' !== get_option( 'show_on_front' ) ) {
		return;
	}
	$per_page = (int) get_option( 'posts_per_page' );
	$query->set( 'offset', VR_HOME_COUNT + ( $query->get( 'paged' ) - 2 ) * $per_page );
}
add_action( 'pre_get_posts', 'vr_home_paged_offset' );

function vr_home_paged_found_posts( $found, $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_home() || ! $query->is_paged() || 'posts' !== get_option( 'show_on_front' ) ) {
		return $found;
	}
	return max( 0, $found - VR_HOME_COUNT ) + (int) get_option( 'posts_per_page' );
}
add_filter( 'found_posts', 'vr_home_paged_found_posts', 10, 2 );

/* ------------------------------------------------------------------
 * Post helpers
 * ------------------------------------------------------------------ */

/**
 * Whether an image has a see-through background (a drawing or logo rather than a photo).
 * Such images are shown whole on the coloured panel instead of being cropped to fill it.
 * The answer is cached on the attachment and recomputed when its files are regenerated.
 */
function vr_image_is_transparent( $attachment_id ) {
	$attachment_id = (int) $attachment_id;
	if ( ! $attachment_id ) {
		return false;
	}

	$cached = get_post_meta( $attachment_id, '_vr_transparent', true );
	if ( 'yes' === $cached || 'no' === $cached ) {
		return 'yes' === $cached;
	}

	$transparent = vr_detect_transparency( $attachment_id );
	update_post_meta( $attachment_id, '_vr_transparent', $transparent ? 'yes' : 'no' );
	return $transparent;
}

function vr_forget_transparency( $data, $attachment_id ) {
	delete_post_meta( $attachment_id, '_vr_transparent' );
	return $data;
}
add_filter( 'wp_update_attachment_metadata', 'vr_forget_transparency', 10, 2 );

function vr_detect_transparency( $attachment_id ) {
	$mime = get_post_mime_type( $attachment_id );
	if ( 'image/svg+xml' === $mime ) {
		return true;
	}
	if ( ! in_array( $mime, array( 'image/png', 'image/gif', 'image/webp' ), true ) ) {
		return false;
	}

	$file = get_attached_file( $attachment_id );
	if ( ! $file || ! is_readable( $file ) ) {
		return false;
	}

	// The file format must be able to hold transparency at all.
	$head = (string) file_get_contents( $file, false, null, 0, 65536 );
	if ( 'image/png' === $mime ) {
		$color_type = isset( $head[25] ) ? ord( $head[25] ) : 0;
		$has_alpha  = in_array( $color_type, array( 4, 6 ), true ) || false !== strpos( (string) strstr( $head, 'IDAT', true ), 'tRNS' );
	} elseif ( 'image/webp' === $mime ) {
		$has_alpha = 'VP8X' === substr( $head, 12, 4 ) && ( ord( $head[20] ) & 0x10 );
	} else {
		$has_alpha = true;
	}
	if ( ! $has_alpha ) {
		return false;
	}

	// A photo saved with an alpha channel is still opaque: look at the corners and edge
	// midpoints of a small copy, where a drawing's background shows.
	if ( ! function_exists( 'imagecreatefromstring' ) ) {
		return true;
	}
	$sample = $file;
	$medium = image_get_intermediate_size( $attachment_id, 'medium' );
	if ( $medium && ! empty( $medium['path'] ) ) {
		$uploads = wp_get_upload_dir();
		$sample  = trailingslashit( $uploads['basedir'] ) . $medium['path'];
	} else {
		$size = wp_getimagesize( $file );
		if ( ! $size || $size[0] * $size[1] > 4000000 ) {
			return true;
		}
	}
	$img = is_readable( $sample ) ? @imagecreatefromstring( (string) file_get_contents( $sample ) ) : false; // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	if ( ! $img ) {
		return true;
	}

	$w      = imagesx( $img ) - 1;
	$h      = imagesy( $img ) - 1;
	$points = array( array( 0, 0 ), array( $w, 0 ), array( 0, $h ), array( $w, $h ), array( (int) ( $w / 2 ), 0 ), array( (int) ( $w / 2 ), $h ), array( 0, (int) ( $h / 2 ) ), array( $w, (int) ( $h / 2 ) ) );
	$clear  = imagecolortransparent( $img );
	$found  = false;
	foreach ( $points as $point ) {
		$index = imagecolorat( $img, $point[0], $point[1] );
		$rgba  = imagecolorsforindex( $img, $index );
		if ( $rgba['alpha'] >= 100 || ( $clear >= 0 && $index === $clear ) ) {
			$found = true;
			break;
		}
	}
	unset( $img );
	return $found;
}

/** Whether the post's featured image is a drawing (see-through background). */
function vr_thumbnail_is_drawing( $post = null ) {
	return has_post_thumbnail( $post ) && vr_image_is_transparent( get_post_thumbnail_id( $post ) );
}

/** First real category of a post; null when it only has the default "Non classé". */
function vr_primary_category( $post = null ) {
	$default = (int) get_option( 'default_category' );
	foreach ( (array) get_the_category( $post ) as $cat ) {
		if ( (int) $cat->term_id !== $default && ! in_array( $cat->slug, array( 'uncategorized', 'non-classe' ), true ) ) {
			return $cat;
		}
	}
	return null;
}

function vr_read_time( $post = null ) {
	$text  = wp_strip_all_tags( strip_shortcodes( get_post_field( 'post_content', $post ) ) );
	$words = count( preg_split( '/\s+/u', trim( $text ), -1, PREG_SPLIT_NO_EMPTY ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

function vr_date( $post = null ) {
	return date_i18n( 'j M Y', get_post_timestamp( $post ) );
}

function vr_byline( $post = null ) {
	$post   = get_post( $post );
	$author = get_the_author_meta( 'display_name', $post->post_author );
	$place  = vr_opt( 'vr_byline_place' );
	if ( ! $author ) {
		return '';
	}
	return $place ? sprintf( '%s, depuis %s', $author, $place ) : $author;
}

/* ------------------------------------------------------------------
 * SVG artwork
 * ------------------------------------------------------------------ */

function vr_marque( $size = 30 ) {
	$size = (int) $size;
	return '<svg viewBox="0 0 40 40" width="' . $size . '" height="' . $size . '" fill="none" stroke="var(--ink)" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
		. '<circle cx="20" cy="20" r="17.5"></circle>'
		. '<circle cx="20" cy="16.5" r="4.2" fill="var(--red)" stroke="none"></circle>'
		. '<path d="M8.5 25 Q14 22 20 25 T31.5 25"></path>'
		. '<path d="M8.5 30 Q14 27 20 30 T31.5 30" opacity="0.55"></path>'
		. '</svg>';
}

/**
 * Line illustration markup.
 *
 * @param string $kind  gbaka | cathedrale | soleil | lagune.
 * @param int    $width Rendered width in px (height follows the viewBox).
 * @param string $color Stroke colour (CSS value).
 */
function vr_illustration( $kind, $width = 240, $color = 'var(--ink)' ) {
	$width = (int) $width;
	$color = esc_attr( $color );

	switch ( $kind ) {
		case 'gbaka':
			$h = (int) round( $width * 175 / 360 );
			return '<svg viewBox="0 0 360 175" width="' . $width . '" height="' . $h . '" fill="none" stroke="' . $color . '" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
				. '<ellipse cx="185" cy="156" rx="135" ry="4" fill="var(--ink)" opacity="0.14" stroke="none"></ellipse>'
				. '<path d="M40 130 L40 96 L300 96 L312 120 Q314 130 304 130 Z" fill="var(--red)"></path>'
				. '<path d="M40 96 L40 70 Q40 52 60 50 L268 50 Q292 52 300 96 Z" fill="var(--sand-2)"></path>'
				. '<path d="M58 50 Q60 38 76 38 L262 38 Q280 40 286 56" fill="var(--sand-2)"></path>'
				. '<path d="M168 50 L168 96"></path>'
				. '<rect x="58" y="58" width="46" height="30" rx="3" fill="var(--sand-2)"></rect>'
				. '<rect x="112" y="58" width="46" height="30" rx="3" fill="var(--sand-2)"></rect>'
				. '<rect x="176" y="58" width="44" height="30" rx="3" fill="var(--sand-2)"></rect>'
				. '<rect x="228" y="58" width="42" height="30" rx="3" fill="var(--sand-2)"></rect>'
				. '<path d="M66 60 L72 84 M120 60 L126 84 M184 60 L190 84 M236 60 L242 84" opacity="0.45"></path>'
				. '<path d="M48 64 L48 84 M52 64 L52 84"></path>'
				. '<path d="M282 92 L282 128"></path>'
				. '<circle cx="108" cy="132" r="20" fill="var(--sand-2)"></circle>'
				. '<circle cx="108" cy="132" r="8" fill="var(--ink)" stroke="none"></circle>'
				. '<circle cx="108" cy="132" r="3" fill="var(--sand-2)" stroke="none"></circle>'
				. '<circle cx="262" cy="132" r="20" fill="var(--sand-2)"></circle>'
				. '<circle cx="262" cy="132" r="8" fill="var(--ink)" stroke="none"></circle>'
				. '<circle cx="262" cy="132" r="3" fill="var(--sand-2)" stroke="none"></circle>'
				. '<path d="M36 124 L40 124 M304 124 L312 124"></path>'
				. '<circle cx="296" cy="108" r="3.5" fill="var(--sand-2)"></circle>'
				. '</svg>';

		case 'cathedrale':
			$h = (int) round( $width * 150 / 220 );
			return '<svg viewBox="0 0 220 150" width="' . $width . '" height="' . $h . '" fill="none" stroke="' . $color . '" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
				. '<path d="M24 140 L24 32 L46 32 L46 140"></path>'
				. '<path d="M35 32 L35 10"></path>'
				. '<circle cx="35" cy="8" r="2.4" fill="' . $color . '" stroke="none"></circle>'
				. '<path d="M35 15 L92 64 L150 96 L200 128" opacity="0.5"></path>'
				. '<path d="M35 24 L92 74 L150 106" opacity="0.32"></path>'
				. '<path d="M60 140 L110 52 L160 140 Z"></path>'
				. '<path d="M84 140 L84 104 Q110 72 136 104 L136 140"></path>'
				. '<path d="M105 140 L105 116 L115 116 L115 140"></path>'
				. '<path d="M4 140 L216 140"></path>'
				. '</svg>';

		case 'soleil':
			$rays = '';
			for ( $i = 0; $i < 12; $i++ ) {
				$a     = deg2rad( $i * 30 );
				$rays .= sprintf(
					'<line x1="%.2f" y1="%.2f" x2="%.2f" y2="%.2f"></line>',
					65 + 33 * cos( $a ),
					65 + 33 * sin( $a ),
					65 + 46 * cos( $a ),
					65 + 46 * sin( $a )
				);
			}
			return '<svg viewBox="0 0 130 130" width="' . $width . '" height="' . $width . '" fill="none" stroke="' . $color . '" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">'
				. '<circle cx="65" cy="65" r="24"></circle>' . $rays . '</svg>';

		case 'lagune':
		default:
			$h = (int) round( $width * 60 / 220 );
			return '<svg viewBox="0 0 220 60" width="' . $width . '" height="' . $h . '" fill="none" stroke="' . $color . '" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">'
				. '<path d="M0 18 Q27 11 55 18 T110 18 T165 18 T220 18"></path>'
				. '<path d="M0 32 Q27 25 55 32 T110 32 T165 32 T220 32" opacity="0.7"></path>'
				. '<path d="M0 46 Q27 39 55 46 T110 46 T165 46 T220 46" opacity="0.42"></path>'
				. '</svg>';
	}
}

/* ------------------------------------------------------------------
 * Newsletter form (footer of the homepage)
 * ------------------------------------------------------------------ */

function vr_newsletter() {
	$shortcode = trim( (string) vr_opt( 'vr_newsletter_shortcode' ) );
	$action    = trim( (string) vr_opt( 'vr_newsletter_action' ) );

	if ( $shortcode ) {
		return '<div class="follow">' . do_shortcode( $shortcode ) . '</div>';
	}

	if ( $action ) {
		return '<form class="follow" action="' . esc_url( $action ) . '" method="post" target="_blank">'
			. '<input type="email" name="EMAIL" placeholder="vous@exemple.ci" aria-label="' . esc_attr__( 'Adresse e-mail', 'ville-resiliente' ) . '" required />'
			. '<button type="submit" class="pill solid">' . esc_html__( 'Suivre le carnet →', 'ville-resiliente' ) . '</button>'
			. '</form>';
	}

	if ( current_user_can( 'edit_theme_options' ) ) {
		return '<p class="mono follow-note">' . esc_html__( 'Infolettre non configurée : Apparence → Personnaliser → Ville Résiliente (visible uniquement par les admins).', 'ville-resiliente' ) . '</p>';
	}

	return '';
}
