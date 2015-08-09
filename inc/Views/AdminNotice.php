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

		$error_message = esc_html_x(
			'%sImportant:%s Not all registered page keys have a page assigned.', '%s = <strong> and </strong>',
			'page-keys'
		);

		$link_url = admin_url( 'edit.php?post_type=page&page=' . $settings_page_slug );
		?>
		<div class="error">
			<p>
				<?php printf( $error_message, '<strong>', '</strong>' ); ?>
				<a href="<?php echo esc_url( $link_url ); ?>">
					<?php echo esc_html_x( 'Assign pages now.', 'Link text in admin notice', 'page-keys' ); ?>
				</a>
			</p>
		</div>
	<?php
	}

}
