<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\Views;

use tf\PageKeys\Models;

/**
 * Class AdminNotice
 *
 * @package tf\PageKeys\Views
 */
class AdminNotice {

	/**
	 * @var Models\SettingsPage
	 */
	private $settings_page;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param Models\SettingsPage $settings_page Settings page model.
	 */
	public function __construct( Models\SettingsPage $settings_page ) {

		$this->settings_page = $settings_page;
	}

	/**
	 * Render the HTML.
	 *
	 * @wp-hook admin_notices
	 *
	 * @return void
	 */
	public function render() {

		global $hook_suffix;

		$settings_page_slug = $this->settings_page->get_slug();

		if (
			$hook_suffix === 'pages_page_' . $settings_page_slug
			|| ! $this->settings_page->current_user_can( 'edit' )
		) {
			return;
		}

		$missing_pages = FALSE;

		foreach ( Models\Option::get() as $page ) {
			if ( empty( $page[ 'page_id' ] ) ) {
				$missing_pages = TRUE;
				break;
			}
		}

		if ( ! $missing_pages ) {
			return;
		}
		?>
		<div class="error">
			<p>
				<?php
				$text = _x(
					'<strong>Important:</strong> Not all registered page keys have a page assigned. %s.',
					'Admin notice, %s=link', 'page-keys'
				);

				$url = admin_url( 'edit.php?post_type=page&page=' . $settings_page_slug );
				$link_text = _x( 'Assign pages now', 'Link text in admin notice', 'page-keys' );
				$link = sprintf(
					'<a href="%s">%s</a>',
					$url,
					$link_text
				);

				printf( $text, $link );
				?>
			</p>
		</div>
	<?php
	}

}
