<?php # -*- coding: utf-8 -*-

namespace tf\PageKeys;

use tf\PageKeys\Model;

require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

/**
 * Class ListTable
 *
 * @package tf\PageKeys
 */
class ListTable extends \WP_List_Table {

	/**
	 * @var array
	 */
	public $columns;

	/**
	 * @var string
	 */
	private $current_row_id;

	/**
	 * @var string
	 */
	private $name_prefix;

	/**
	 * @var Model\SettingsPage
	 */
	private $settings_page;

	/**
	 * Constructor. Set up the properties.
	 *
	 * @param Model\SettingsPage $settings_page Settings page model.
	 */
	public function __construct( Model\SettingsPage $settings_page ) {

		$this->settings_page = $settings_page;

		$slug = $settings_page->get_slug();
		parent::__construct(
			array(
				'singular' => 'page-key',
				'plural'   => 'page-keys',
				'screen'   => 'pages_page_' . $slug,
			)
		);

		$this->columns = array(
			'page_key' => __( 'Page Key', 'page-keys' ),
			'page_id'  => __( 'Page', 'page-keys' ),
		);

		$this->name_prefix = Model\Option::get_name();
	}

	/**
	 * Prepare the items.
	 *
	 * @return void
	 */
	public function prepare_items() {

		$sortable_columns = $this->get_sortable_columns();

		$this->_column_headers = array(
			$this->get_columns(),
			$this->get_hidden_columns(),
			$sortable_columns,
		);

		$this->items = $this->get_items();
		$this->sort_items( $sortable_columns );
		$this->maybe_add_item();
	}

	/**
	 * Return all columns.
	 *
	 * @see prepare_items()
	 *
	 * @return array
	 */
	public function get_columns() {

		return $this->columns;
	}

	/**
	 * Return the hidden columns only.
	 *
	 * @see prepare_items()
	 *
	 * @return array
	 */
	public function get_hidden_columns() {

		return array();
	}

	/**
	 * Return the sortable columns only.
	 *
	 * @see prepare_items()
	 *
	 * @return array
	 */
	public function get_sortable_columns() {

		return array(
			'page_key' => array(
				'page_key',
				FALSE,
			),
		);
	}

	/**
	 * Return all items.
	 *
	 * @see prepare_items()
	 *
	 * @return \stdClass[]
	 */
	private function get_items() {

		$items = array();

		foreach ( Model\Option::get() as $page_key => $page ) {
			$page_id = isset( $page[ 'page_id' ] ) ? intval( $page[ 'page_id' ] ) : '';

			$items[ $page_key ] = (object) compact(
				'page_key',
				'page_id'
			);
		}

		return $items;
	}

	/**
	 * Sort the items according to the values given in the $_REQUEST superglobal.
	 *
	 * @see prepare_items()
	 *
	 * @param array $sortable_columns Sortable columns.
	 *
	 * @return void
	 */
	private function sort_items( $sortable_columns ) {

		if (
			empty( $_REQUEST[ 'orderby' ] )
			|| ! array_key_exists( $_REQUEST[ 'orderby' ], $sortable_columns )
		) {
			return;
		}

		if (
			isset( $_REQUEST[ 'order' ] )
			&& strtolower( $_REQUEST[ 'order' ] ) === 'desc'
		) {
			krsort( $this->items );
		} else {
			ksort( $this->items );
		}
	}

	/**
	 * Add an empty item if the according action is set in the $_GET superglobal.
	 *
	 * @see prepare_items()
	 *
	 * @return void
	 */
	private function maybe_add_item() {

		if (
			filter_input( INPUT_GET, 'action' ) === 'add'
			&& $this->settings_page->current_user_can( 'edit' )
		) {
			$this->items[ ] = $this->get_empty_item();
		}
	}

	/**
	 * Return an empty item.
	 *
	 * @see maybe_add_item()
	 * @see single_row()
	 *
	 * @return object
	 */
	private function get_empty_item() {

		return (object) array(
			'page_key' => '',
			'page_id'  => '',
		);
	}

	/**
	 * Render the list table.
	 *
	 * @return void
	 */
	public function display() {

		?>
		<p>
			<?php _e( 'For each page key, please select a page.', 'page-keys' ); ?>
		</p>
		<?php
		parent::display();
	}

	/**
	 * Render a single row.
	 *
	 * @param \stdClass $item Current item.
	 *
	 * @return void
	 */
	public function single_row( $item ) {

		$id = time() . mt_rand();
		$id = md5( $id );
		$this->current_row_id = substr( $id, 0, 15 );

		parent::single_row( $item );
	}

	/**
	 * Return a single row.
	 *
	 * @see tf\PageKeys\Controller\Action::add_page_key_ajax()
	 *
	 * @return string
	 */
	public function get_single_row() {

		$item = $this->get_empty_item();

		ob_start();
		$this->single_row( $item );

		return ob_get_clean();
	}

	/**
	 * Individual callback for the 'page key' column.
	 *
	 * @param \stdClass $item Current item.
	 *
	 * @return string
	 */
	public function column_page_key( \stdClass $item ) {

		$page_key = '';
		if (
			! empty( $item->page_key )
			&& is_string( $item->page_key )
		) {
			$page_key = $item->page_key;
		}

		$html = sprintf(
			'<input type="text" name="%1$s[%2$s][page_key]" value="%3$s" class="page-key regular-text" data-id="%2$s">',
			$this->name_prefix,
			$this->current_row_id,
			$page_key
		);

		$actions = array();
		if ( $this->settings_page->current_user_can( 'edit' ) ) {
			$text = __( 'Edit' );
			$url = get_permalink();
			$title = esc_attr__( 'Edit this item' );
			$actions[ 'edit hide-if-no-js' ] = sprintf(
				'<a class="edit" title="%3$s" href="%2$s">%1$s</a>',
				$text,
				$url,
				$title
			);

			$text = __( 'Delete Permanently' );
			$url = $this->settings_page->get_delete_page_key_url( $page_key );
			$title = esc_attr__( 'Delete this item permanently' );
			$actions[ 'delete' ] = sprintf(
				'<a class="submitdelete submitdelete-%4$s" title="%3$s" href="%2$s" data-id="%4$s">%1$s</a>',
				$text,
				$url,
				$title,
				$this->current_row_id
			);
		}

		if ( $actions ) {
			$html .= $this->row_actions( $actions );
		}

		return $html;
	}

	/**
	 * Individual callback for the 'page id' column.
	 *
	 * @param \stdClass $item Current item.
	 *
	 * @return string
	 */
	public function column_page_id( \stdClass $item ) {

		$selected = '';
		if ( isset( $item->page_id ) ) {
			$selected = intval( $item->page_id );
		}

		return wp_dropdown_pages(
			array(
				'name'             => $this->name_prefix . '[' . $this->current_row_id . '][page_id]',
				'id'               => 'page-id-' . $this->current_row_id,
				'show_option_none' => ' ',
				'option_non_value' => '',
				'selected'         => $selected,
				'echo'             => FALSE,
			)
		);
	}

	/**
	 * Fallback for all valid columns without an individual callback.
	 *
	 * @param  \stdClass $item        Current item.
	 * @param  string    $column_name Current column name.
	 *
	 * @return string
	 */
	public function column_default( \stdClass $item, $column_name ) {

		if (
			array_key_exists( $column_name, $this->columns )
			&& ! empty( $item->{$column_name} )
		) {
			return $item->{$column_name};
		}

		return '';
	}

	/**
	 * Don't show the navigation, bulk actions or filters.
	 *
	 * @param string $unused Unused.
	 *
	 * @return void
	 */
	public function display_tablenav( $unused ) {
	}

	/**
	 * In case there are no items, render an appropriate message.
	 *
	 * @return void
	 */
	public function no_items() {

		_e( 'No page keys found.', 'page-keys' );
	}

}
