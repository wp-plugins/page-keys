<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys\View;

use tf\PageKeys\Controller;
use tf\PageKeys\ListTable;
use tf\PageKeys\Model;

/**
 * Class SettingsPage
 *
 * @package tf\PageKeys\View
 */
class SettingsPage {

	/**
	 * @var Controller\Action
	 */
	private $controller;

	/**
	 * @var Model\SettingsPage
	 */
	private $model;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param Model\SettingsPage $model      Settings page model.
	 * @param Controller\Action  $controller Action controller.
	 */
	public function __construct( Model\SettingsPage $model, Controller\Action $controller ) {

		$this->model = $model;
		$this->controller = $controller;
	}

	/**
	 * Render the HTML.
	 *
	 * @see tf\PageKeys\Model\SettingsPage::add()
	 *
	 * @return void
	 */
	public function render() {

		$current_user_can_edit = $this->model->current_user_can( 'edit' );

		$this->controller->maybe_take_action();

		$title = $this->model->get_title();

		$option_name = Model\Option::get_name();

		$list_table = new ListTable( $this->model );
		$list_table->prepare_items();
		?>
		<div class="wrap">
			<h2>
				<?php esc_html_e( $title ); ?>
				<?php if ( $current_user_can_edit ) : ?>
					<a href="<?php echo $this->model->get_add_page_key_url(); ?>" class="add-new-h2">
						<?php esc_html_e( 'Add New' ); ?>
					</a>
				<?php endif; ?>
			</h2>
			<?php settings_errors(); ?>
			<form action="<?php echo admin_url( 'options.php' ); ?>" method="post" id="page-keys-form">
				<?php settings_fields( $option_name ); ?>
				<?php $list_table->display(); ?>

				<?php if ( $current_user_can_edit ) : ?>
					<?php submit_button(); ?>
					<div class="error inline hide-if-no-js">
						<p>
							<?php _e( '<strong>Warning:</strong> Duplicate page keys found!', 'page-keys' ); ?>
						</p>
					</div>
				<?php endif; ?>
			</form>
		</div>
	<?php
	}

}
