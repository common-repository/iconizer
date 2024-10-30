<?php
/*
 * Plugin Name: WP Iconizer
 * Plugin URI: https://wordpress.org/plugins/iconizer/
 * Description: Replace the well-known stale icon of the TopLeft into a firey WordPress Icon, or into your OWN!
 * Author: IQubex Technologies
 * Author URI: https://www.iqubex.com/
 * Text Domain: iconizer
 * Version: 1.2
 */

// No thank you
if ( ! defined( 'ABSPATH' ) ) die();

/*
 * Developed by @kumar_abhirup
 * For all the loved ones who dreamt to make WordPress Dashboard Great Again!
*/

// Styles Enqueuer
function iq_iconizer_add_styles(){
	wp_enqueue_style( 'iq_iconizer_settings_page_styles', plugins_url( 'assets/css/settings.css', __FILE__ ), $deps = null, $ver = false, $media = 'all' );
}
add_action( 'admin_init', 'iq_iconizer_add_styles' );

// Code to Add Menu
function iq_add_iconizer_settings_menu(){
	$page_title = "Iconizer Settings";
	$menu_title = "Iconizer";
	$capability = "manage_options";
	$menu_slug 	= "iconizer";
	$function	  = "iq_iconizer_settings_menu_callback";
	$icon_url 	= "dashicons-iconizer";
	$position	  = "75";
	add_menu_page(
			$page_title,
			$menu_title,
			$capability,
			$menu_slug,
			$function,
			$icon_url,
			$position
		);
}

function iq_iconizer_settings_menu_callback(){
	?>
		<div class="ultimate-container">
			<div class="master-container">
				<h1 class="master-title"><span>Iconizer!</span> Make your Dashboard Great Again <span>&#9733;&#9733;</span></h1>
				<div class="master-content">
					If you wonder what this plugin does, stop here itself and read it! Here you can change the very Icon on Dashboard that has been in the Top-Left corner for years. We get into the CSS code, and make changes for you.... Why? to <strong>Make Your Dashboard Great Again</strong>!
				</div>
			</div>
			<div class="master-container" id="WPIcon">
				<h1 class="master-title"><span>WordPress Top Left Icon</span></h1>
				<div class="master-content">
					<div class="flex">
						<div class="para">
							Change <strong>the icon situated at the most Top-Left</strong> of your Dashboard. It makes your dashboard look very professional and the CMS looks to be made by you! Be sure you use a perfect square image with a transparent background in .PNG mode.
						</div>
						<div class="right">
							<img src="<?php echo plugin_dir_url( __FILE__ ).'assets/images/manual/WordPress_Icon.jpg'; ?>" /> <!-- https://iqubex.com/wp-content/uploads/2017/10/WordPress_Icon.jpg -->
						</div>
					</div>
					<div class="clear"></div>
					<div class="settings">
						<div class="attribute">
							<span class="need">Upload 16x16 (.png) Image:</span>
							<span class="input">
								<?php

								// Update Table Data
								function iq_iconizer_insert_into_db_wpicon_img() {
										?>

											<form style="display:inline;" action="#WPIcon" method="post" enctype="multipart/form-data">
												<input type="file" name="Wordcon_img" id="Wordcon" class="input-button" value="Upload PNG Image" />
												<input type="submit" name="submit-btn-img" value="Upload!" />
												<!--<?php wp_nonce_field( 'iq_iconizer_action', '_iqIconizerBlock' ); ?>-->
											</form>
											<div class="clear"></div>

										<?php

											$html = ob_get_clean();
											/* $IQIconizer_nonce = $_REQUEST['_iqIconizerBlock']; */

											// does the inserting, in case the form is filled and submitted
											if ( isset( $_POST["submit-btn-img"] ) && $_FILES["Wordcon_img"] != "" ){
												/* && wp_verify_nonce($IQIconizer_nonce,'iq_iconizer_action') */

												// Handles Upload
												if ( ! function_exists( 'wp_handle_upload' ) ) {
														require_once( ABSPATH . 'wp-admin/includes/file.php' );
												}

												$uploadedfile = $_FILES['Wordcon_img'];

												$upload_overrides = array( 'test_form' => false );

												$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

												if ( $movefile && ! isset( $movefile['error'] ) ) {
													update_option( 'iq_iconizer_admin_icon', $movefile['url'], 'yes' );
														echo 'File is valid, and was successfully uploaded. <a href="" style="text-decoration:none;"><b style="color:green;">Refresh the page to see the change!</b></a>';
												} else {
													echo "There was an error uploading the file. Please try again later.";
												}
											}
									}
									iq_iconizer_insert_into_db_wpicon_img();
								?>
							</span>
						</div>
					</div>
					<div class="para">
						<hr />
						<b>Wait here itself!</b> Read these instructions before you make haste and spoil your WordPress Dashboard. In this version of Iconizer, you can only showcase an image in the TopLeft corner of your WordPress Dashboard. <i>We're working out on more features. For eg. you will be able to write desired text replacing the TopLeft Icon of Dashboard. We're currently also working upon the feature of letting you enable and disable the dropdown you find there.</i>
						<ul>
							<li><b>1)</b> When uploading an image, Use only a 16x16(px) png/jpg image or else it may cost you the dashboard.</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	<?php
}
add_action('admin_menu','iq_add_iconizer_settings_menu');

// Execution
	function iq_iconizer_load_dashicons_front_end() {
	wp_enqueue_style( 'dashicons' );
	}
	add_action( 'wp_enqueue_scripts', 'iq_iconizer_load_dashicons_front_end' );

	function iq_iconizer_custom_favicon() {
		echo '
			<style>
			.dashicons-wpcon {
					background-image: url("'.get_option('iq_iconizer_admin_icon').'");
					background-repeat: no-repeat;
					background-position: center;
			}
			.dashicons-iconizer {
					background-image: url("'.plugin_dir_url( __FILE__ ).'assets/images/wordpress-icon.png");
					background-repeat: no-repeat;
					background-position: center;
			}
			</style>
		';
	}
	add_action('admin_head', 'iq_iconizer_custom_favicon');

	function iq_iconizer_mastercuter(){
		$option = "iq_iconizer_admin_icon";
		?>
			<style type="text/css">
					#wp-admin-bar-wp-logo{}
					#wp-admin-bar-wp-logo:hover{}
					#wp-admin-bar-wp-logo .ab-icon::before{
						content:url(<?php if(!empty(get_option($option))){echo get_option($option);} else{echo plugin_dir_url( __FILE__ ).'assets/images/wordpress-icon.png';} ?>) !important;
						font:20px Arial !important;
						line-height:20px !important;
						text-align:center;
						color:#fff !important;
						overlay:hidden !important;
					}
					#wp-admin-bar-wp-logo .ab-sub-wrapper{
						display:none !important;
					}
			</style>
		<?php
	}
	add_action('admin_head', 'iq_iconizer_mastercuter');
