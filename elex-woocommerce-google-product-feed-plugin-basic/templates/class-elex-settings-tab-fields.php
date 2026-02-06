<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Elex_Settings_Tab_Fields {

	public function __construct() {
		$this->elex_gpf_load_script_and_styles();
		$this->elex_gpf_add_setting_fields();
	}

	public function elex_gpf_load_script_and_styles() {
		wp_nonce_field( 'ajax-elex-gpf-nonce', '_ajax_elex_gpf_nonce' );
		global $woocommerce;
		$woocommerce_version = function_exists( 'WC' ) ? WC()->version : $woocommerce->version;
		wp_enqueue_style( 'woocommerce_admin_styles', $woocommerce->plugin_url() . '/assets/css/admin.css', array(), $woocommerce_version );
		wp_register_style( 'elex-gpf-plugin-bootstrap', plugins_url( '/assets/css/bootstrap.css', dirname( __FILE__ ) ), array(), $woocommerce_version );
		wp_enqueue_style( 'elex-gpf-plugin-bootstrap' );
		wp_register_script( 'elex-gpf-tooltip-jquery', plugins_url( '/assets/js/tooltip.js', dirname( __FILE__ ) ), array(), $woocommerce_version );
		wp_enqueue_script( 'elex-gpf-tooltip-jquery' );
		wp_register_script( 'elex-gpf-settings-tab', plugins_url( '/assets/js/elex-settings-tab-script.js', dirname( __FILE__ ) ), array(), $woocommerce_version );
		wp_enqueue_script( 'elex-gpf-settings-tab' );
		wp_register_style( 'elex-setting-style', ELEX_PRODUCT_FEED_MAIN_URL_PATH . '/assets/css/elex-setting-styles.css', array(), $woocommerce_version );
		wp_enqueue_style( 'elex-setting-style' );
	}

	public function elex_gpf_add_setting_fields() {
		$saved_data = get_option( 'elex_settings_tab_fields_data' );
		$meta_keys  = '';
		$file_path  = '';
		$upload_dir = wp_upload_dir();
		$base       = $upload_dir['basedir'];
		$path       = realpath( $base . '/elex-product-feed/' );
		if ( isset( $saved_data['custom_meta'] ) ) {
			$meta_keys = implode( ',', $saved_data['custom_meta'] );
		}
		
		$category_languages = array(
			'en' => __( 'English', 'elex-product-feed' ),
			'ru' => __( 'Russian', 'elex-product-feed' ),
			'es' => __( 'Spanish', 'elex-product-feed' ),
			'de' => __( 'German', 'elex-product-feed' ),
			'fr' => __( 'French', 'elex-product-feed' ),
		);
		$wpml_languages     = array(
			''   => '-----',
			'en' => __( 'English', 'elex-product-feed' ),
			'ru' => __( 'Russian', 'elex-product-feed' ),
			'es' => __( 'Spanish', 'elex-product-feed' ),
			'de' => __( 'German', 'elex-product-feed' ),
			'fr' => __( 'French', 'elex-product-feed' ),
			'nl' => __( 'Dutch', 'elex-product-feed' ),
			'el' => __( 'Greek', 'elex-product-feed' ),
			'pl' => __( 'Polish', 'elex-product-feed' ),
			'it' => __( 'Italian', 'elex-product-feed' ),
		);
		?>
			<div class="elex-gpf-loader"></div>
			<div class="postbox elex-gpf-table-box elex-gpf-table-box-main ">
			<h1>
				<?php esc_html_e( 'Settings', 'elex-product-feed' ); ?>
			</h1>
			<table class="elex-gpf-settings-table">
				<tr>
					<td class="elex-gpf-settings-table-left">
						<?php esc_html_e( 'Google Product Category Language', 'elex-product-feed' ); ?>
					</td>
					<td class='elex-gpf-settings-table-middle'>
						<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Change the language used for google product category taxonomy. The default will be set as English regardless of any country chosen while creating the feed.', 'elex-product-feed' ); ?>'></span>
					</td>
					<td class="elex-gpf-settings-table-right">
						<select id="elex_google_cat_language_selector">
							<?php
								$selected_value = isset( $saved_data['cat_language'] ) ? $saved_data['cat_language'] : 'en';
							foreach ( $category_languages as $key => $value ) {
								if ( $key == $selected_value ) {
									echo '<option value="' . esc_html( $key ) . '" selected="true">' . esc_html( $value ) . '</option>';
								} else {
									echo '<option value="' . esc_html( $key ) . '">' . esc_html( $value ) . '</option>';
								}
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="elex-gpf-settings-table-left">
						<?php esc_html_e( 'WPML Language', 'elex-product-feed' ); ?>
					</td>
					<td class='elex-gpf-settings-table-middle'>
						<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Select the language for the Product data to be included in the feed. Please note, WPML plugin must be activated and there should be a translation available for the selected language.', 'elex-product-feed' ); ?>'></span>
					</td>
					<td class="elex-gpf-settings-table-right">
						<select id="elex_google_wpml_language_selector">
							<?php
								$selected_value = isset( $saved_data['wpml_language'] ) ? $saved_data['wpml_language'] : '';
							foreach ( $wpml_languages as $key => $value ) {
								if ( $key == $selected_value ) {
									echo '<option value="' . esc_html( $key ) . '" selected="true">' . esc_html( $value ) . '</option>';
								} else {
									echo '<option value="' . esc_html( $key ) . '">' . esc_html( $value ) . '</option>';
								}
							}
							?>
						</select>
					</td>
				</tr>
				<table id="elex_google_sync_details_table">
						<tr>
							<td class="elex-gpf-settings-table-left" style="white-space: nowrap; min-width: 200px;">
								<h4 style="margin: 0; color: #000;"><?php esc_html_e( 'Connect to Google', 'elex-product-feed' ); ?><span style="vertical-align: super;color:green;font-size:12px">Premium</span></h4>
							</td>
						</tr>
						<tr>
							<td class="elex-gpf-settings-table-left">
								<?php esc_html_e( 'Client ID', 'elex-product-feed' ); ?>
							</td>
							<td class='elex-gpf-settings-table-middle'>
								<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Enter your Google API Client ID.', 'elex-product-feed' ); ?>'></span>
							</td>
							<td class="elex-gpf-settings-table-right">
								<input type="text" id="google_client_id" name="google_client_id" value="" class="regular-text" disabled />
							</td>
						</tr>
						<tr>
							<td class="elex-gpf-settings-table-left">
								<label for="google_client_secret"><?php esc_html_e( 'Client Secret', 'elex-product-feed' ); ?></label>
							</td>
							<td class='elex-gpf-settings-table-middle'>
								<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Enter your Google API Client Secret.', 'elex-product-feed' ); ?>'></span>
							</td>
							<td class="elex-gpf-settings-table-right">
								<input type="text" id="google_client_secret" name="google_client_secret" value="" class="regular-text" disabled />
							</td>
						</tr>
						<tr>
							<td class="elex-gpf-settings-table-left">
								<label for="google_merchant_id"><?php esc_html_e( 'Merchant Id', 'elex-product-feed' ); ?></label>
							</td>
							<td class='elex-gpf-settings-table-middle'>
								<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Enter your Google Merchant Center ID.', 'elex-product-feed' ); ?>'></span>
							</td>
							<td class="elex-gpf-settings-table-right">
								<input type="text" id="google_merchant_id" name="google_merchant_id" value="" class="regular-text" disabled/>
							</td>
						</tr>
						<tr>
							<td colspan="3">
								<button class="elex-connect-button" disabled> <?php esc_html_e( 'Connect', 'elex-product-feed' ); ?> </button>
								<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Once the Client credentials are saved successfully click on Connect to get connected to Google Merchant Center.', 'elex-product-feed' ); ?>'></span>
							</td>
						</tr>
					</table>

			</table>
			<div style="margin-bottom: 4%;">
			<button class="botton button-large button-primary" id="elex_save_settings_tab_data" style="float: right; width: 10%;">Save</button>
			</div>
			</div>

		<?php
	}
}

new Elex_Settings_Tab_Fields();
