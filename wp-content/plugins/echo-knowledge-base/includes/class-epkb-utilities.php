<?php

/**
 * Various utility functions
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPKB_Utilities {

	static $wp_options_cache = array();
	static $postmeta = array();

	/**************************************************************************************************************************
	 *
	 *                     POST OPERATIONS
	 *
	 **************************************************************************************************************************/

	/**
	 * Retrieve a KB article with security checks
	 *
	 * @param $post_id
	 * @param bool $only_published
	 * @return null|WP_Post - return null if this is NOT KB post
	 */
	public static function get_kb_post_secure( $post_id, $only_published=true ) {

		if ( empty($post_id) ) {
			return null;
		}

		// ensure post_id is valid
		$post_id = self::sanitize_int( $post_id );
		if ( empty($post_id) ) {
			return null;
		}

		// retrieve the post and ensure it is one
		$post = get_post( $post_id );
		if ( empty($post) || ! is_object($post) || ! $post instanceof WP_Post ) {
			return null;
		}

		// verify it is a KB article
		if ( ! EPKB_KB_Handler::is_kb_post_type( $post->post_type ) ) {
			return null;
		}

		// allow only public or private articles
		if ( $only_published && ! in_array($post->post_status, array('publish', 'future', 'pending', 'draft')) ) {
			return null;
		} else  if ( ! in_array($post->post_status, array('publish', 'private', 'future', 'pending', 'draft')) ) {
			return null;
		}

		return $post;
	}

	public static function get_post_status_text( $post_status ) {

		$post_statuses = array( 'draft' => __( 'Draft', 'echo-knowledge-base' ), 'pending' => __( 'Pending', 'echo-knowledge-base' ),
		                        'publish' => __( 'Published', 'echo-knowledge-base' ), 'future' => __( 'Scheduled', 'echo-knowledge-base' ),
								'private' => __( 'Private', 'echo-knowledge-base' ),
								'trash'   => __( 'Trash', 'echo-knowledge-base' ));

		if ( empty($post_status) || ! in_array($post_status, array_keys($post_statuses)) ) {
			return $post_status;
		}

		return $post_statuses[$post_status];
	}


	/**************************************************************************************************************************
	 *
	 *                     STRING OPERATIONS
	 *
	 **************************************************************************************************************************/

	/**
	 * PHP substr() function returns FALSE if the input string is empty. This method
	 * returns empty string if input is empty or if error occurs.
	 *
	 * @param $string
	 * @param $start
	 * @param null $length
	 *
	 * @return string
	 */
	public static function substr( $string, $start, $length=null ) {
		$result = substr($string, $start, $length);
		return empty($result) ? '' : $result;
	}

	/**************************************************************************************************************************
	 *
	 *                     NUMBER OPERATIONS
	 *
	 **************************************************************************************************************************/

	/**
	 * Determine if value is positive integer ( > 0 )
	 * @param int $number is check
	 * @return bool
	 */
	public static function is_positive_int( $number ) {

		// no invalid format
		if ( empty($number) || ! is_numeric($number) ) {
			return false;
		}

		// no non-digit characters
		$numbers_only = preg_replace('/\D/', "", $number );
		if ( empty($numbers_only) || $numbers_only != $number ) {
			return false;
		}

		// only positive
		return $numbers_only > 0;
	}

	/**
	 * Determine if value is positive integer
	 * @param int $number is check
	 * @return bool
	 */
	public static function is_positive_or_zero_int( $number ) {

		if ( ! isset($number) || ! is_numeric($number) ) {
			return false;
		}

		if ( ( (int) $number) != ( (float) $number )) {
			return false;
		}

		$number = (int) $number;

		return is_int($number);
	}


	/**************************************************************************************************************************
	 *
	 *                     DATE OPERATIONS
	 *
	 **************************************************************************************************************************/

	/**
	 * Retrieve specific format from given date-time string e.g. '10-16-2003 10:20:01' becomes '10-16-2003'
	 *
	 * @param $datetime_str
	 * @param string $format e.g. 'Y-m-d H:i:s'  or  'M j, Y'
	 *
	 * @return string formatted date or the original string
	 */
	public static function get_formatted_datetime_string( $datetime_str, $format='M j, Y' ) {

		if ( empty($datetime_str) || empty($format) ) {
			return $datetime_str;
		}

		$time = strtotime($datetime_str);
		if ( empty($time) ) {
			return $datetime_str;
		}

		$date_time = date_i18n($format, $time);
		if ( $date_time == $format ) {
			$date_time = $datetime_str;
		}

		return empty($date_time) ? $datetime_str : $date_time;
	}

	/**
	 * Get nof hours passed between two dates.
	 *
	 * @param string $date1
	 * @param string $date2 OR if empty then use current date
	 *
	 * @return int - number of hours between dates [0-x] or null if error
	 */
	public static function get_hours_since( $date1, $date2='' ) {

		try {
			$date1_dt = new DateTime( $date1 );
			$date2_dt = new DateTime( $date2 );
		} catch(Exception $ex) {
			return null;
		}

		if ( empty($date1_dt) || empty($date2_dt) ) {
			return null;
		}

		$hours = date_diff($date1_dt, $date2_dt)->h;

		return $hours === false ? null : $hours;
	}

	/**
	 * Get nof days passed between two dates.
	 *
	 * @param string $date1
	 * @param string $date2 OR if empty then use current date
	 *
	 * @return int - number of days between dates [0-x] or null if error
	 */
	public static function get_days_since( $date1, $date2='' ) {

		try {
			$date1_dt = new DateTime( $date1 );
			$date2_dt = new DateTime( $date2 );
		} catch(Exception $ex) {
			return null;
		}

		if ( empty($date1_dt) || empty($date2_dt) ) {
			return null;
		}

		$days = (int)date_diff($date1_dt, $date2_dt)->format("%r%a");

		return $days === false ? null : $days;
	}


	/**
	 * How long ago pass date occurred.
	 *
	 * @param string $date1
	 *
	 * @return string x year|month|week|day|hour|minute|second(s) or '[unknown]' on error
	 */
	public static function time_since_today( $date1 ) {
		return self::how_long_ago( $date1 );
	}

	/**
	 * How long ago since now.
	 *
	 * @param string $date1
	 * @param string $date2 or if empty use current time
	 *
	 * @return string x year|month|week|day|hour|minute|second(s) or '[unknown]' on error
	 */
	public static function how_long_ago( $date1, $date2='' ) {

		$time1 = strtotime($date1);
		$time2 = empty($date2) ? time() : strtotime($date2);
		if ( empty($time1) || empty($time2) ) {
			return '[???]';
		}

		$time = abs($time2 - $time1);
		$time = ( $time < 1 )? 1 : $time;
		$tokens = array (
			31536000 => __( 'year', 'echo-knowledge-base' ),
			2592000 => __( 'month', 'echo-knowledge-base' ),
			604800 => __( 'week', 'echo-knowledge-base' ),
			86400 => __( 'day', 'echo-knowledge-base' ),
			3600 => __( 'hour', 'echo-knowledge-base' ),
			60 => __( 'min', 'echo-knowledge-base' ),
			1 => __( 'sec', 'echo-knowledge-base' )
		);

		$output = '';
		foreach ($tokens as $unit => $text) {
			if ($time >= $unit) {
				$numberOfUnits = floor($time / $unit);
				$output =  $numberOfUnits . ' ' . $text . ( $numberOfUnits >1 ? 's' : '');
				break;
			}
		}

		return $output;
	}


	/**************************************************************************************************************************
	 *
	 *                     NOTICES
	 *
	 *************************************************************************************************************************/

	/**
	 * Display content (not message).
	 *
	 * @param $message
	 */
	public static function ajax_show_content( $message ) {
		wp_die( json_encode( array( 'message' => $message ) ) );
	}

	/**
	 * AJAX: Used on response back to JS. will call wp_die()
	 *
	 * @param string $message
	 * @param string $title
	 * @param string $type
	 */
	public static function ajax_show_info_die( $message, $title='', $type='success' ) {
		wp_die( json_encode( array( 'message' => self::get_bottom_notice_message_box( $message, $title, $type) ) ) );
	}

	/**
	 * AJAX: Used on response back to JS. will call wp_die()
	 *
	 * @param $message
	 * @param string $title
	 */
	public static function ajax_show_error_die( $message, $title='' ) {
		wp_die( json_encode( array( 'error' => true, 'message' => self::get_bottom_notice_message_box( $message, $title, 'error') ) ) );
	}

	/**
	 * Show info or error message to the user
	 *
	 * @param $message
	 * @param string $title
	 * @param string $type
	 *
	 * @return string
	 */
	public static function get_bottom_notice_message_box($message, $title='', $type='success' ) {
		/* array $EZSQL_ERROR */
		global $EZSQL_ERROR;

		if ( ! empty($EZSQL_ERROR) && is_array($EZSQL_ERROR) ) {
			foreach ( $EZSQL_ERROR as $error ){
				$amgr_tables = array("amgr_access_kb_categories", "amgr_access_read_articles", "amgr_access_read_categories", "amgr_kb_group_users", "amgr_kb_groups", "amgr_kb_public_groups");
				foreach ( $amgr_tables as $table_name ) {
					if ( !empty($error['error_str']) && strpos($error['error_str'], $table_name) !== false ) {
						//LOG Only Acess Manager Error
						EPKB_Logging::add_log( 'Database error', $EZSQL_ERROR );
						$message .= __( '. Database Error.', 'echo-knowledge-base' );
					}
				}

			}
		}

		$title = empty($title) ? '' : '<h4>' . $title . '</h4>';
		$message = empty($message) ? '' : $message;
		return
			"<div class='eckb-bottom-notice-message'>
				<div class='contents'>
					<span class='$type'>
						$title
						<p> " . wp_kses_post($message) . "</p>
					</span>
				</div>
				<div class='epkb-close-notice epkbfa epkbfa-window-close'></div>
			</div>";
	}

	public static function user_not_logged_in() {
		self::ajax_show_error_die( '<p>' . __( 'You are not logged in. Refresh your page and log in.', 'echo-knowledge-base' ) . '</p>', __( 'Cannot save your changes', 'echo-knowledge-base' ) );
	}

	/**
	 * Show on the page error message on page load.
	 *
	 * @param string $error_msg
	 */
	public static function output_inline_error_notice( $error_msg ) {
		echo '<div class="eckb-inline-error-notice">
                <div class="eckb-inline-error-notice-contents">
                ' . $error_msg . '
				</div>
			  </div>';
	}

	/**
	 * DIALOG BOX - User has to do something with a form values
	 *	$values ['id']                  CSS ID, used for JS targeting, no CSS styling.
	 *	$values ['title']               Top Title of Dialog Box.
	 *	$values ['body']                Text description.
	 *	$values ['form_inputs']         Form Inputs
	 *	$values ['accept_label']        Text for Accept button.
	 *	$values ['accept_type']         Text for Accept button. ( success, default, primary, error , warning )
	 *
	 * @param $values
	 */
	public static function dialog_box_form( $values ) { ?>

		<div id="<?php echo $values[ 'id' ]; ?>" class="epkb-dialog-box-form">

			<!---- Header ---->
			<div class="epkb-dbf__header">
				<h4><?php echo $values['title']; ?></h4>
			</div>

			<!---- Body ---->
			<div class="epkb-dbf__body">				<?php 
				echo empty( $values['body']) ? '' : $values['body']; ?>
			</div>

			<!---- Form ---->
			<form class="epkb-dbf__form">				<?php
				if ( isset($values['form_inputs']) ) {
					foreach ( $values['form_inputs'] as $input ) {
						echo '<div class="epkb-dbf__form__input">' . $input . '</div>';
					}
				}; ?>
			</form>

			<!---- Footer ---->
			<div class="epkb-dbf__footer">

				<div class="epkb-dbf__footer__accept <?php echo isset($values['accept_type']) ? 'epkb-dbf__footer__accept--'.$values['accept_type'] : 'epkb-dbf__footer__accept--success'; ?>">
					<span id="epkb-accept-button" class="epkb-dbf__footer__accept__btn">
						<?php echo $values['accept_label'] ? $values['accept_label'] : __( 'Accept', 'echo-knowledge-base' ); ?>
					</span>
				</div>

				<div class="epkb-dbf__footer__cancel">
					<span class="epkb-dbf__footer__cancel__btn"><?php _e( 'Cancel', 'echo-knowledge-base' ); ?></span>
				</div>

			</div>

			<div class="epkb-dbf__close epkbfa epkbfa-times"></div>

		</div>
		<div class="epkb-dialog-box-form-black-background"></div>		<?php
	}

	/**
	 * DIALOG BOX
	 *	$values ['id']                  CSS ID, used for JS targeting, no CSS styling.
	 *	$values ['title']               Top Title of Dialog Box.
	 *	$values ['body']                Text description.
	 * @param $values
	 */
	public static function dialog_box( $values ) { ?>

		<div id="<?php echo $values[ 'id' ]; ?>" class="epkb-dialog-box-form">

			<!---- Header ---->
			<div class="epkb-dbf__header">
				<h4><?php echo $values['title']; ?></h4>
			</div>

			<!---- Body ---->
			<div class="epkb-dbf__body">				<?php
				echo empty( $values['body']) ? '' : $values['body']; ?>
			</div>

			<div class="epkb-dbf__close epkbfa epkbfa-times"></div>

		</div>
		<div class="epkb-dialog-box-form-black-background"></div>		<?php
	}


	/**************************************************************************************************************************
	 *
	 *                     SECURITY
	 *
	 *************************************************************************************************************************/

	/**
	 * Return digits only.
	 *
	 * @param $number
	 * @param int $default
	 * @return int <default>
	 */
	public static function sanitize_int( $number, $default=0 ) {

		if ( $number === null || ! is_numeric($number) ) {
			return $default;
		}

		$number = preg_replace('/\D/', '', $number);

		return empty($number) ? $default : (int) $number;
	}

	/**
	 * Return text, space, "-" and "_" only.
	 *
	 * @param $text
	 * @param String $default
	 * @return String|<default>
	 */
	public static function sanitize_english_text( $text, $default='' ) {

		if ( empty($text) || ! is_string($text) ) {
			return $default;
		}

		$text = preg_replace('/[^A-Za-z0-9 \-_]/', '', $text);

		return empty($text) ? $default : $text;
	}

	/**
	 * Retrieve ID or return error. Used for KB ID and other IDs.
	 *
	 * @param mixed $id is either $id number or array with 'id' index
	 *
	 * @return int|WP_Error
	 */
	public static function sanitize_get_id( $id ) {

		if ( empty( $id) || is_wp_error($id) ) {
			EPKB_Logging::add_log( 'Error occurred (01)' );
			return new WP_Error('E001', __( 'invalid ID', 'echo-knowledge-base' ) );
		}

		if ( is_array( $id) ) {
			if ( ! isset( $id['id']) ) {
				EPKB_Logging::add_log( 'Error occurred (02)' );
				return new WP_Error('E002', __( 'invalid ID', 'echo-knowledge-base' ) );
			}

			$id_value = $id['id'];
			if ( ! self::is_positive_int( $id_value ) ) {
				EPKB_Logging::add_log( 'Error occurred (03)', $id_value );
				return new WP_Error('E003', __( 'invalid ID', 'echo-knowledge-base' ) . self::get_variable_string($id_value));
			}

			return (int) $id_value;
		}

		if ( ! self::is_positive_int( $id ) ) {
			EPKB_Logging::add_log( 'Error occurred (04)', $id );
			return new WP_Error('E004', __( 'invalid ID', 'echo-knowledge-base' ) . $id);
		}

		return (int) $id;
	}

    /**
     * Sanitize array full of ints.
     *
     * @param $array_values
     * @param string $default
     * @return array|string
     */
	public static function sanitize_int_array( $array_values, $default='' ) {
	    if ( ! is_array($array_values) ) {
	        return $default;
        }

        $sanitized_array = array();
        foreach( $array_values as $value ) {
	        $sanitized_array[] = self::sanitize_int( $value );
        }

        return $sanitized_array;
    }

	/**
	 * Decode and sanitize form fields.
	 *
	 * @param $form
	 * @param $all_fields_specs
	 * @return array
	 */
	public static function retrieve_and_sanitize_form( $form, $all_fields_specs ) {
		if ( empty($form) ) {
			return array();
		}

		// first urldecode()
		if (is_string($form)) {
			parse_str($form, $submitted_fields);
		} else {
			$submitted_fields = $form;
		}

		// now sanitize each field
		$sanitized_fields = array();
		foreach( $submitted_fields as $submitted_key => $submitted_value ) {

			if ( ! empty($all_fields_specs[$submitted_key]['type']) && $all_fields_specs[$submitted_key]['type'] == EPKB_Input_Filter::WP_EDITOR ) {
				$sanitized_fields[$submitted_key] = wp_kses_post( $submitted_value );
			} elseif ( ! empty( $all_fields_specs[$submitted_key]['type'] ) && ! empty( $all_fields_specs[$submitted_key]['allowed_tags'] ) && $all_fields_specs[$submitted_key]['type'] == EPKB_Input_Filter::TEXT ) {
				// text input with allowed tags 
				$sanitized_fields[$submitted_key] = wp_kses( $submitted_value, $all_fields_specs[$submitted_key]['allowed_tags'] );
			} else {
				$sanitized_fields[$submitted_key] = sanitize_text_field( $submitted_value );
			}

		}

		return $sanitized_fields;
	}

	/**
	 * Return ints and comma only.
	 *
	 * @param $text
	 * @param String $default
	 * @return String|<default>
	 */
	public static function sanitize_comma_separated_ints( $text, $default='' ) {

		if ( empty($text) || ! is_string($text) ) {
			return $default;
		}

		$text = preg_replace('/[^0-9 \,_]/', '', $text);

		return empty($text) ? $default : $text;
	}

	/**
	 * Retrieve value from POST or GET
	 *
	 * @param $key
	 * @param string $default
	 * @param bool $sanitize
	 *
	 * @return array|string - empty if not found
	 */
	public static function post( $key, $default='', $sanitize=true ) {

		if ( ! isset($_POST[$key]) && ! isset($_GET[$key]) ) {
			return $default;
		}

		$value = isset($_POST[$key]) ? $_POST[$key] : $_GET[$key];

		return $value === null ? $default : ( $sanitize && is_scalar($value) ? sanitize_text_field( $value ) : $value );
	}

	/**
	 * Retrieve value from GET or POST
	 *
	 * @param $key
	 * @param string $default
	 * @param bool $sanitize
	 *
	 * @return string - empty if not found
	 */
	public static function get( $key,  $default='', $sanitize=true ) {

		if ( ! isset($_GET[$key]) && ! isset($_POST[$key]) ) {
			return $default;
		}

		$value = isset($_GET[$key]) ? $_GET[$key] : $_POST[$key];

		return $value === null ? $default : ( $sanitize && is_scalar($value) ? sanitize_text_field( $value ) : $value );
	}

	/**
	 * Check if Aaccess Manager is considered active.
	 *
	 * @param bool $is_active_check_only
	 * @return bool
	 */
	public static function is_amag_on( $is_active_check_only=false ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		if ( defined( 'AMAG_PLUGIN_NAME' ) ) {
			return true;
		}

		if ( $is_active_check_only ) {
			return false;
		}

		$table = $wpdb->prefix . 'am'.'gr_kb_groups';
		$result = $wpdb->get_var( "SHOW TABLES LIKE '" . $table ."'" );

		return ( ! empty($result) && ( $table == $result ) );
	}


	/**************************************************************************************************************************
	 *
	 *                     GET/SAVE/UPDATE AN OPTION
	 *
	 *************************************************************************************************************************/

	/**
	 * Get KB-SPECIFIC option. Function adds KB ID suffix. Prefix represent core or ADD-ON prefix.
	 *
	 * WARN: Use ep.kb_get_instance()->kb_config_obj->get_kb_configs() to get KB specific configuration.
	 *
	 * @param $kb_id - assuming it is a valid ID
	 * @param $option_name - without kb suffix
	 * @param $default - use if KB option not found
	 * @param bool $is_array - ensure returned value is an array, otherwise return default
	 * @return string|array|null or default
	 */
	public static function get_kb_option( $kb_id, $option_name, $default, $is_array=false ) {
		$full_option_name = $option_name . '_' . $kb_id;
		return self::get_wp_option( $full_option_name, $default, $is_array );
	}

	/**
	 * Use to get:
	 *  a) PLUGIN-WIDE option not specific to any KB with e p k b prefix.
	 *  b) ADD-ON-SPECIFIC option with ADD-ON prefix.
	 *  b) KB-SPECIFIC configuration with e p k b prefix and KB ID suffix.
	 *
	 * @param $option_name
	 * @param $default
	 * @param bool|false $is_array
	 * @param bool $return_error
	 *
	 * @return array|string|WP_Error or default or error if $return_error is true
	 */
	public static function get_wp_option( $option_name, $default, $is_array=false, $return_error=false ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		if ( isset(self::$wp_options_cache[$option_name]) ) {
			return self::$wp_options_cache[$option_name];
		}

		// retrieve specific KB option
		$option = $wpdb->get_var( $wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name = %s", $option_name ) );
		if ( $option !== null ) {
			$option = maybe_unserialize( $option );
		}

		if ( $return_error && $option === null && ! empty($wpdb->last_error) ) {
			EPKB_Logging::add_log( "DB failure: " . $wpdb->last_error, 'Option Name: ' . $option_name );
			return new WP_Error(__( 'Database failure', 'echo-knowledge-base' ), $wpdb->last_error);
		}

		// if KB option is missing then return defaults
		if ( $option === null || ( $is_array && ! is_array($option) ) ) {
			return $default;
		}

		self::$wp_options_cache[$option_name] = $option;

		return $option;
	}

	/**
	 * Save KB-SPECIFIC option. Function adds KB ID suffix. Prefix represent core or ADD-ON prefix.
	 *
	 * @param $kb_id - assuming it is a valid ID
	 * @param $option_name - without kb suffix
	 * @param array $option_value
	 * @param $sanitized - ensures input is sanitized
	 *
	 * @return array|WP_Error if option cannot be serialized or db insert failed
	 */
	public static function save_kb_option( $kb_id, $option_name, array $option_value, $sanitized ) {
		$full_option_name = $option_name . '_' . $kb_id;
		return self::save_wp_option( $full_option_name, $option_value, $sanitized );
	}

	/**
	 * Use to save:
	 *  a) PLUGIN-WIDE option not specific to any KB with e p k b prefix.
	 *  b) ADD-ON-SPECIFIC option with ADD-ON prefix.
	 *  b) KB-SPECIFIC configuration with e p k b prefix and KB ID suffix.
	 *
	 * @param $option_name
	 * @param $option_value
	 * @param $sanitized
	 * @return mixed|WP_Error
	 */
	public static function save_wp_option( $option_name, $option_value, $sanitized ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		if ( $sanitized !== true ) {
			return new WP_Error( '433', __( 'Option value was not sanitized for option: ', 'echo-knowledge-base' ) . $option_name );
		}

		// do not store null
		if ( $option_value === null ) {
            $option_value = '';
        }

		// add or update the option
		$serialized_value = $option_value;
		if ( is_array( $option_value ) || is_object( $option_value ) ) {
			$serialized_value = maybe_serialize($option_value);
			if ( empty($serialized_value) ) {
				return new WP_Error( '434', __( 'Failed to serialize value for option: ', 'echo-knowledge-base' ) . $option_name );
			}
		}

		$result = $wpdb->query( $wpdb->prepare( "INSERT INTO $wpdb->options (`option_name`, `option_value`, `autoload`) VALUES (%s, %s, %s)
 												 ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`)",
												$option_name, $serialized_value, 'no' ) );
		if ( $result === false ) {
			EPKB_Logging::add_log( 'Failed to update option', $option_name );
			return new WP_Error( '435', 'Failed to update option ' . $option_name );
		}

		self::$wp_options_cache[$option_name] = $option_value;

		return $option_value;
	}


    /**************************************************************************************************************************
     *
     *                     DATABASE
     *
     *************************************************************************************************************************/

	/**
	 * Get given Post Metadata
	 *
	 * @param $post_id
	 * @param $meta_key
	 * @param $default
	 * @param bool|false $is_array
	 * @param bool $return_error
	 *
	 * @return array|string or default or error if $return_error is true
	 */
	public static function get_postmeta( $post_id, $meta_key, $default, $is_array=false, $return_error=false ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		if ( isset(self::$postmeta[$post_id][$meta_key]) ) {
			return self::$postmeta[$post_id][$meta_key];
		}

		if ( ! self::is_positive_int( $post_id) ) {
			return $return_error ? new WP_Error( __( 'Invalid Post ID', 'echo-knowledge-base' ), self::get_variable_string( $post_id ) ) : $default;
		}

		// retrieve specific KB option
		$option = $wpdb->get_var( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = %d and meta_key = '%s'", $post_id, $meta_key ) );
		if ($option !== null ) {
			$option = maybe_unserialize( $option );
		}

		if ( $return_error && $option === null && ! empty($wpdb->last_error) ) {
			EPKB_Logging::add_log( "DB failure: " . $wpdb->last_error, 'Meta Key: ' . $meta_key );
			return new WP_Error(__( 'Database failure', 'echo-knowledge-base' ), $wpdb->last_error);
		}

		// if KB option is missing then return defaults
		if ( $option === null || ( $is_array && ! is_array($option) ) ) {
			return $default;
		}

		self::$postmeta[$post_id][$meta_key] = $option;

		return $option;
	}

	/**
	 * Save or Insert Post Metadata
	 *
	 * @param $post_id
	 * @param $meta_key
	 * @param $meta_value
	 * @param $sanitized
	 *
	 * @return mixed|WP_Error
	 */
	public static function save_postmeta( $post_id, $meta_key, $meta_value, $sanitized ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		if ( ! self::is_positive_int( $post_id) ) {
			return new WP_Error( __( 'Invalid Post ID', 'echo-knowledge-base' ), self::get_variable_string( $post_id ) );
		}

		if ( $sanitized !== true ) {
			return new WP_Error( '433', __( 'Option value was not sanitized for meta key: ', 'echo-knowledge-base' ) . $meta_key );
		}

		// do not store null
		if ( $meta_value === null ) {
			$meta_value = '';
		}

		// add or update the option
		$serialized_value = $meta_value;
		if ( is_array( $meta_value ) || is_object( $meta_value ) ) {
			$serialized_value = maybe_serialize($meta_value);
			if ( empty($serialized_value) ) {
				return new WP_Error( '434', __( 'Failed to serialize value for meta key: ', 'echo-knowledge-base' ) . $meta_key );
			}
		}

		// check if the meta field already exists before doing 'upsert'
		$result = $wpdb->get_row( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '%s' AND post_id = %d", $meta_key, $post_id ) );
		if ( $result === null && ! empty($wpdb->last_error) ) {
			EPKB_Logging::add_log( "DB failure: " . $wpdb->last_error );
			return new WP_Error(__( 'Database failure', 'echo-knowledge-base' ), $wpdb->last_error);
		}

		// INSERT or UPDATE the meta field
		if ( empty($result) ) {
			if ( false === $wpdb->query( $wpdb->prepare( "INSERT INTO $wpdb->postmeta (`meta_key`, `meta_value`, `post_id`) VALUES (%s, %s, %d)", $meta_key, $serialized_value, $post_id ) ) ) {
				EPKB_Logging::add_log("Failed to insert meta data. ", $meta_key);
				return new WP_Error( '33', __( 'Failed to insert meta data', 'echo-knowledge-base' ) );
			}
		} else {
			if ( false === $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_value = %s WHERE meta_key = '%s' AND post_id = %d", $serialized_value, $meta_key, $post_id ) ) ) {
				EPKB_Logging::add_log("Failed to update meta data. ", $meta_key);
				return new WP_Error( '33', __( 'Failed to update meta data', 'echo-knowledge-base' ) );
			}
		}

		if ( $result === false ) {
			EPKB_Logging::add_log( 'Failed to update meta key', $meta_key );
			return new WP_Error( '435', __( 'Failed to update meta key ', 'echo-knowledge-base' ) . $meta_key );
		}

		self::$postmeta[$post_id][$meta_key] = $meta_value;

		return $meta_value;
	}

	/**
	 * Delete given Post Metadata
	 *
	 * @param $post_id
	 * @param $meta_key
	 *
	 * @return bool
	 */
	public static function delete_postmeta( $post_id, $meta_key ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		if ( ! self::is_positive_int( $post_id) ) {
			return false;
		}

		// delete specific KB option
		if ( false === $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE post_id = %d and meta_key = '%s'", $post_id, $meta_key ) ) ) {
			EPKB_Logging::add_log( "Could not delete post '" . self::get_variable_string($meta_key) . "'' metadata: ", $post_id);
			return false;
		}

		return true;
	}


	/**************************************************************************************************************************
	 *
	 *                     CATEGORIES
	 *
	 *************************************************************************************************************************/

    /**
     *
     * USED TO HANDLE ALL CATEGORIES REGARDLESS OF USER PERMISSIONS.
     *
     * Get all existing KB categories.
     *
     * @param $kb_id
     * @param string $order_by
     * @return array|null - return array of KB categories (empty if not found) or null on error
     */
    public static function get_kb_categories_unfiltered( $kb_id, $order_by='name' ) {
        /** @var wpdb $wpdb */
        global $wpdb;

        $order = $order_by == 'name' ? 'ASC' : 'DESC';
        $order_by = $order_by == 'date' ? 'term_id' : $order_by;   // terms don't have date so use id
        $kb_category_taxonomy_name = EPKB_KB_Handler::get_category_taxonomy_name( $kb_id );
        $result = $wpdb->get_results( $wpdb->prepare("SELECT t.*, tt.*
												   FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
												   WHERE tt.taxonomy IN (%s) ORDER BY " . esc_sql('t.' . $order_by) . ' ' . $order . ' ', $kb_category_taxonomy_name ) );
        return isset($result) && is_array($result) ? $result : null;
    }

	/**
	 * USED TO HANDLE ALL CATEGORIES REGARDLESS OF USER PERMISSIONS.
	 *
	 * Get KB Article categories.
	 *
	 * @param $kb_id
	 * @param $article_id
	 * @return array|null - categories belonging to the given KB Article or null on error
	 */
	public static function get_article_categories_unfiltered( $kb_id, $article_id ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		if ( empty($article_id) ) {
			return null;
		}

		// get article categories
		$post_taxonomy_objs = $wpdb->get_results( $wpdb->prepare(
											"SELECT * FROM $wpdb->term_taxonomy
																	 WHERE taxonomy = '%s' and term_taxonomy_id in
																	(SELECT term_taxonomy_id FROM $wpdb->term_relationships WHERE object_id = %d) ",
											EPKB_KB_Handler::get_category_taxonomy_name( $kb_id ), $article_id ) );
		if ( ! empty($wpdb->last_error) ) {
			return null;
		}

		return $post_taxonomy_objs === null || ! is_array($post_taxonomy_objs) ? array() : $post_taxonomy_objs;
	}

	/**
	 * USED TO HANDLE ALL CATEGORIES REGARDLESS OF USER PERMISSIONS.
	 *
	 * Retrieve KB Category.
	 *
	 * @param $kb_id
	 * @param $kb_category_id
	 * @return WP_Term|false
	 */
    public static function get_kb_category_unfiltered( $kb_id, $kb_category_id ) {
	    $term = get_term_by('id', $kb_category_id, EPKB_KB_Handler::get_category_taxonomy_name( $kb_id ) );
	    if ( empty($term) || ! $term instanceof WP_Term ) {
		    EPKB_Logging::add_log( "Category is not KB Category: " . $kb_category_id . "(35)", $kb_id);
		    return false;
	    }

	    return $term;
    }

	/**
	 * USED TO HANDLE ALL CATEGORIES REGARDLESS OF USER PERMISSIONS.
	 *
	 * Retrieve KB Category by its slug.
	 *
	 * @param $kb_id
	 * @param $kb_category_slug
	 * @return WP_Term|false
	 */
	public static function get_kb_category_by_slug_unfiltered( $kb_id, $kb_category_slug ) {
		$term = get_term_by('slug', $kb_category_slug, EPKB_KB_Handler::get_category_taxonomy_name( $kb_id ) );
		if ( empty($term) || ! $term instanceof WP_Term ) {
			EPKB_Logging::add_log( "Category is not KB Category: " . $kb_category_slug . "(34)", $kb_id);
			return false;
		}

		return $term;
	}

	/**
	 * Insert KB Category
	 *
	 * @param $kb_id
	 * @param $category_name
	 * @param $args
	 * @return array|null - new term or null on error
	 */
    public static function insert_kb_category( $kb_id, $category_name, $args=array() ) {

	    $kb_category_taxonomy_name = EPKB_KB_Handler::get_category_taxonomy_name( $kb_id );

	    $new_term = wp_insert_term( $category_name, $kb_category_taxonomy_name, $args );
	    if ( is_wp_error($new_term) ) {
		    EPKB_Logging::add_log( 'Failed to insert category. cat name: ' . $category_name . ', taxonomy: ' . $kb_category_taxonomy_name . ' kb id: ' . $kb_id, $args, $new_term );
		    return null;
	    }
	    if ( ! isset($new_term['term_id']) ) {
		    EPKB_Logging::add_log( 'Failed to insert category. cat name: ' . $category_name . ', taxonomy: ' . $kb_category_taxonomy_name . ' kb id: ' . $kb_id, $args );
		    return null;
	    }

	    return $new_term;
    }

	/**
	 *
	 *  USED TO HANDLE ALL CATEGORIES REGARDLESS OF USER PERMISSIONS.
	 *
	 * Get KB Article categories.
	 *
	 * @param $kb_id
	 * @param $article_id
	 * @return array|null - categories belonging to the given KB Article or null on error
	 */
	public static function get_article_category_ids_unfiltered( $kb_id, $article_id ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		if ( empty($article_id) ) {
			return null;
		}

		// get article categories
		$post_taxonomy_objs = $wpdb->get_results( $wpdb->prepare(
										"SELECT term_id FROM $wpdb->term_taxonomy
										 WHERE taxonomy = '%s' and term_taxonomy_id in 
										(SELECT term_taxonomy_id FROM $wpdb->term_relationships WHERE object_id = %d) ",
										EPKB_KB_Handler::get_category_taxonomy_name( $kb_id ), $article_id ) );
		if ( $post_taxonomy_objs === null || ! is_array( $post_taxonomy_objs ) ) {
			return null;
		}

		$article_category_ids = array();
		foreach( $post_taxonomy_objs as $post_taxonomy_obj ) {
			if ( isset($post_taxonomy_obj->term_id) ) {
				$article_category_ids[] = $post_taxonomy_obj->term_id;
			}
		}
		return $article_category_ids;
	}

	/**************************************************************************************************************************
	 *
	 *                     OTHER
	 *
	 *************************************************************************************************************************/

	/**
	 * Retrieve KB ID.
	 *
	 * @param WP_Post $post
	 * @return int or null on ERROR
	 */
	public static function get_kb_id( $post=null ) {
		global $eckb_kb_id;

		$post = $post === null ? get_post() : $post;
		if ( ! empty($post) && $post instanceof WP_Post ) {
			$kb_id = EPKB_KB_Handler::get_kb_id_from_post_type( $post->post_type );
		}

		$kb_id = empty($kb_id) || is_wp_error($kb_id) ? ( empty($eckb_kb_id) ? '' : $eckb_kb_id ) : $kb_id;
		if ( empty($kb_id) ) {
			EPKB_Logging::add_log("KB ID not found", $kb_id);
			return null;
		}

		return $kb_id;
	}

	/**
	 * Return string representation of given variable for logging purposes
	 *
	 * @param $var
	 *
	 * @return string
	 */
	public static function get_variable_string( $var ) {

		if ( ! is_array($var) ) {
			return self::get_variable_not_array( $var );
		}

		if ( empty($var) ) {
			return '['. __( 'empty', 'echo-knowledge-base' ) . ']';
		}

		$output = 'array';
		$ix = 0;
		foreach ($var as $key => $value) {

            if ( $ix++ > 10 ) {
                $output .= '[.....]';
                break;
            }

			$output .= "[" . $key . " => ";
			if ( ! is_array($value) ) {
				$output .= self::get_variable_not_array( $value ) . "]";
				continue;
			}

			$ix2 = 0;
			$output .= "[";
			$first = true;
			foreach($value as $key2 => $value2) {
                if ( $ix2++ > 10 ) {
                    $output .= '[.....]';
                    break;
                }

				if ( is_array($value2) ) {
                    $output .= print_r($value2, true);
                } else {
					$output .= ( $first ? '' : ', ' ) . $key2 . " => " . self::get_variable_not_array( $value2 );
					$first = false;
					continue;
				}
            }
			$output .= "]]";
		}

		return $output;
	}

	private static function get_variable_not_array( $var ) {

		if ( $var === null ) {
			return '<' . __( 'null', 'echo-knowledge-base' ) . '>';
		}

		if ( ! isset($var) ) {
            /** @noinspection HtmlUnknownAttribute */
            return '<' . __( 'not set', 'echo-knowledge-base' ) . '>';
		}

		if ( is_array($var) ) {
			return empty($var) ? '[]' : '[...]';
		}

		if ( is_object( $var ) ) {
			return '<' . get_class($var) . '>';
		}

		if ( is_bool( $var ) ) {
			return $var ? 'TRUE' : 'FALSE';
		}

		if ( is_string($var) || is_numeric($var) ) {
			return $var;
		}

		return '<' . __( 'unknown', 'echo-knowledge-base' ) . '>';
	}

	/**
	 * Array1 VALUES NOT IN array2
	 *
	 * @param array $array1
	 * @param array $array2
	 *
	 * @return array of values in array1 NOT in array2
	 */
	public static function diff_two_dimentional_arrays( array $array1, array $array2 ) {

		if ( empty($array1) ) {
			return array();
		}

		if ( empty($array2) ) {
			return $array1;
		}

		// flatten first array
		foreach( $array1 as $key => $value ) {
			if ( is_array($value) ) {
				$tmp_value = '';
				foreach( $value as $tmp ) {
					$tmp_value .= ( empty($tmp_value) ? '' : ',' ) . ( empty($tmp) ? '' : $tmp );
				}
				$array1[$key] = $tmp_value;
			}
		}

		// flatten second array
		foreach( $array2 as $key => $value ) {
			if ( is_array($value) ) {
				$tmp_value = '';
				foreach( $value as $tmp ) {
					$tmp_value .= ( empty($tmp_value) ? '' : ',' ) . ( empty($tmp) ? '' : $tmp );
				}
				$array2[$key] = $tmp_value;
			}
		}

		return array_diff_assoc($array1, $array2);
	}

	/**
	 * Get current user.
	 *
	 * @return null|WP_User
	 */
	public static function get_current_user() {

		$user = null;
		if ( function_exists('wp_get_current_user') ) {
			$user = wp_get_current_user();
		}

		// is user not logged in? user ID is 0 if not logged
		if ( empty($user) || ! $user instanceof WP_User || empty($user->ID) ) {
			$user = null;
		}

		return $user;
	}

	/**
	 * Output inline CSS style based on configuration.
	 *
	 * @param string $styles A list of Configuration Setting styles
	 * @param $kb_config
	 * @return string
	 */
	public static function get_inline_style( $styles, $kb_config ) {

		if ( empty($styles) || ! is_string($styles) ) {
			return '';
		}

		$style_array = explode(',', $styles);
		if ( empty($style_array) ) {
			return '';
		}

		$output = 'style="';
		foreach( $style_array as $style ) {

			$key_value = array_map( 'trim', explode(':', $style) );
			if ( empty($key_value[0]) ) {
				continue;
			}

			$output .= $key_value[0] . ': ';

			// true if using KB config value
			if ( count($key_value) == 2 && isset($key_value[1]) ) {
				$output .= $key_value[1];
			} else if ( isset($key_value[2]) && isset($kb_config[$key_value[2]]) ) {
				$output .= $kb_config[ $key_value[2] ];

				switch ( $key_value[0] ) {
					case 'border-radius':
					case 'border-width':
					case 'border-bottom-width':
					case 'border-top-left-radius':
					case 'border-top-right-radius':
					case 'border-bottom-left-radius':
					case 'border-bottom-right-radius':
					case 'min-height':
					case 'max-height':
					case 'height':
					case 'padding-left':
					case 'padding-right':
					case 'padding-top':
					case 'padding-bottom':
					case 'margin':
					case 'margin-top':
					case 'margin-right':
					case 'margin-bottom':
					case 'margin-left':
					case 'font-size':
						$output .= 'px';
						break;
				}
			}

			$output .= '; ';
		}

		return trim($output) . '"';
	}

	/**
	 * Output CSS classes based on configuration.
	 *
	 * @param $classes
	 * @param $kb_config
	 * @return string
	 */
	public static function get_css_class( $classes, $kb_config ) {

		if ( empty($classes) || ! is_string($classes) ) {
			return '';
		}

		$output = ' class="';
		foreach( array_map( 'trim', explode(',', $classes) ) as $class ) {
			$class_name = trim(str_replace(':', '', $class));
			$is_kb_config = $class != $class_name;

			if ( $is_kb_config && empty($kb_config[$class_name]) ) {
				continue;
			}

			$output .= ( $is_kb_config ? $kb_config[$class_name] : $class ) . ' ';
		}
		return trim($output) . '"';
	}

	/**
	 * Check if KB is ARCHIVED.
	 *
	 * @param $kb_status
	 * @return bool
	 */
	public static function is_kb_archived( $kb_status ) {
		return $kb_status === 'archived';
	}

	/**
	 * Check if given articles belong to the currently selected langauge. Return ones that are.
	 * @param $articles
	 * @param bool $are_posts
	 * @return array
	 */
	public static function is_wpml_article_active( $articles, $are_posts=false ) {

		$article_ids = $articles;
		if ( $are_posts ) {
			$article_ids = array();
			foreach( $articles as $article ) {
				$article_ids[] = empty($article->ID) ? 0 : $article->ID;
			}
		}

		$current_lang = apply_filters( 'wpml_current_language', NULL );
		$current_article_ids = array();
		foreach( $article_ids as $article_id ) {
			$args = array( 'element_id' => $article_id, 'element_type' => 'post' );
			$article_lang = apply_filters( 'wpml_element_language_code', null, $args );
			if ( $article_lang == $current_lang ) {
				$current_article_ids[] = $article_id;
			}
		}

		return $current_article_ids;
	}

	/**
	 * Is WPML enabled? Only for KB CORE. ADD-ONs to call this function in core
	 * @param array $kb_config
	 * @return bool
	 */
	public static function is_wpml_enabled( $kb_config=array() ) {
		return ! empty($kb_config['wpml_is_enabled']) && $kb_config['wpml_is_enabled'] === 'on' && ! defined( 'AMAG_PLUGIN_NAME' );
	}

	public static function is_advanced_search_enabled( $kb_config ) {
		return defined('AS'.'EA_PLUGIN_NAME') &&
		       $kb_config['kb_articles_common_path'] != 'demo-1-knowledge-base-basic-layout' &&
		       $kb_config['kb_articles_common_path'] != 'demo-2-knowledge-base-basic-layout' &&
		       $kb_config['kb_articles_common_path'] != 'demo-3-knowledge-base-tabs-layout' &&
		       $kb_config['kb_articles_common_path'] != 'demo-4-knowledge-base-tabs-layout' &&
		       $kb_config['kb_articles_common_path'] != 'demo-12-knowledge-base-image-layout';
	}

	public static function is_article_rating_enabled() {
	   return defined( 'EP' . 'RF_PLUGIN_NAME' );
	}

	public static function is_elegant_layouts_enabled() {
		return defined('E'.'LAY_PLUGIN_NAME');
	}

	public static function is_multiple_kbs_enabled() {
		return defined('E'.'MKB_PLUGIN_NAME');
	}
	
	public static function is_export_import_enabled() {
		return defined('E'.'PIE_PLUGIN_NAME');
	}
	
	public static function is_creative_addons_widgets_enabled() {
		return defined( 'CREATIVE_ADDONS_VERSION' ) && defined( 'ELEMENTOR_VERSION' );
	}
	
	public static function is_elementor_enabled() {
		return defined( 'ELEMENTOR_VERSION' );
	}

	public static function is_kb_main_page() {
		global $eckb_is_kb_main_page;
		$ix = (isset($eckb_is_kb_main_page) && $eckb_is_kb_main_page) || EPKB_Utilities::get('is_kb_main_page') == 1 ? 'mp' : 'ap';
		return $ix == 'mp';
	}

	/**
	 * Show error message at the top of WordPress page
	 * @param $message
	 * @param null $button_text
	 * @param null $button_url
	 */
	public static function output_top_error_message( $message, $button_text=null, $button_url=null ) {  ?>
		<div class="wrap">
			<h1></h1>
		</div>
		<div class="notice notice-error">
			<p>				<?php
				_e( $message, 'echo-knowledge-base' );
				if ( ! empty($button_text) && ! empty($button_url) ) {
					echo ' <a class="button button-primary" href="' . esc_url( $button_url ) . '">' . __( $button_text, 'echo-knowledge-base' ) . '</a>';
				}   ?>
			</p>
		</div>		<?php
	}

	/**
	 * Common way to show support link
	 * @return string
	 */
	public static function contact_us_for_support() {

		$label = ' ' .  _x('Please contact us for support:', 'echo-knowledge-base') . ' ';
		$click_text =  _x('click here', 'echo-knowledge-base');

		return $label . '<a href="https://www.echoknowledgebase.com/technical-support/" target="_blank" rel="noopener noreferrer">' . $click_text . '</a>';
	}

	/**
	 * For given Main Page, retrieve its slug.
	 *
	 * @param $kb_main_page_id
	 *
	 * @return string
	 */
	public static function get_main_page_slug( $kb_main_page_id ) {

		$kb_page = get_post( $kb_main_page_id );
		if ( empty($kb_page) ) {
			return '';
		}

		$slug      = urldecode(sanitize_title_with_dashes( $kb_page->post_name, '', 'save' ));
		$ancestors = get_post_ancestors( $kb_page );
		foreach ( $ancestors as $ancestor_id ) {
			$post_ancestor = get_post( $ancestor_id );
			if ( empty($post_ancestor) ) {
				continue;
			}
			$slug = urldecode(sanitize_title_with_dashes( $post_ancestor->post_name, '', 'save' )) . '/' . $slug;
			if ( $kb_main_page_id == $ancestor_id ) {
				break;
			}
		}

		return $slug;
	}

	/**
	 * Show KB page missing message
	 * @param $kb_config
	 */
	public static function kb_page_with_shortcode_missing_msg( $kb_config ) {
		$message_shortcode = "[epkb-knowledge-base id=" . $kb_config['id'] ."]";
		$message = sprintf( __( 'We did not detect any page with KB shortcode for your knowledge base "%s". If you do have ' .
		                        'such a page please re-save it and come back. Otherwise create a page and insert KB ' .
		                        'shortcode in the format of %s', 'echo-knowledge-base' ), $kb_config['kb_name'], $message_shortcode);		?>
		<div class="epkb-kb-page-missing-alert">
			<h4 class='epkb-wizard-error-note'><?php echo $message; ?></h4>
		</div>  <?php
	}
	
	/**
	 * Check if Classic Editor plugin is active.
	 * By KAGG Design
	 * @return bool
	 */
	public static function is_classic_editor_plugin_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return is_plugin_active( 'classic-editor/classic-editor.php' );
	}

	/*************************************************************************************************
	 *
	 *   KB CORE
	 *
	 ************************************************************************************************/

	/**
	 * @param $kb_config
	 * @return array
	 */
	public static function get_editor_urls( $kb_config ) {

		$main_url = EPKB_KB_Handler::get_first_kb_main_page_url( $kb_config );
		$main_url_templates = empty($main_url) ? '' : add_query_arg( array('preopen' => 'templates'), $main_url );
		$main_url = empty($main_url) ? '' : add_query_arg( array('preopen' => 'settings'), $main_url );

		$article_url = EPKB_KB_Handler::get_first_kb_article_url( $kb_config );
		$article_url = empty($article_url) ? '' : add_query_arg( array('preopen' => 'settings'), $article_url );

		$archive_url = EPKB_KB_Handler::get_first_kb_category_url( $kb_config );
		$archive_url = empty($archive_url) ? '' : add_query_arg( array('preopen' => 'settings'), $archive_url );

		return [ 
			'main_page_url' => $main_url,
			'main_page_url_templates' => $main_url_templates,
			'article_page_url' => $article_url,
			'archive_url' => $archive_url ];
	}
}
