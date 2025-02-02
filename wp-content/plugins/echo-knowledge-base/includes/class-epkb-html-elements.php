<?php

/**
 * Elements of form UI and others
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPKB_HTML_Elements {

    // Form Elements------------------------------------------------------------------------------------------/
	private function add_defaults( array $input_array, array $custom_defaults=array() ) {

		$defaults = array(
			'id'                => '',
			'name'              => 'text',
			'value'             => '',
			'label'             => '',
			'title'             => '',
			'class'             => '',
			'main_label_class'  => '',
			'label_class'       => '',
			'input_class'       => '',
			'input_group_class' => '',
			'action_class'      => '',
			'desc'              => '',
			'info'              => '',
			'placeholder'       => '',
			'readonly'          => false,  // will not be submitted
			'required'          => '',
			'autocomplete'      => false,
			'data'              => false,
			'disabled'          => false,
			'size'              => 3,
			'max'               => 50,
			'current'           => null,
			'options'           => array(),
            'label_wrapper'     => '',
            'input_wrapper'     => '',
            'return_html'       => false,
            'unique'            => true,
            'radio_class'       => ''
		);
		$defaults = array_merge( $defaults, $custom_defaults );
		return array_merge( $defaults, $input_array );
	}

	private function add_common_defaults( array $input_array, array $custom_defaults=array() ) {
		$defaults = array(
			'id'                => '',
			'name'              => 'text',
			'value'             => '',
			'label'             => '',
			'title'             => '',
			'class'             => '',
			'main_label_class'  => '',
			'label_class'       => '',
			'input_class'       => '',
			'input_group_class' => '',
			'desc'              => '',
			'info'              => '',
			'placeholder'       => '',
			'readonly'          => false,  // will not be submitted
			'required'          => '',
			'autocomplete'      => false,
			'data'              => false,
			'disabled'          => false,
			'size'              => 3,
			'max'               => 50,
			'current'           => null,
			'options'           => array()
		);
		$defaults = array_merge( $defaults, $custom_defaults );
		return array_merge( $defaults, $input_array );
	}

	/**
	 * Renders an HTML Text field
	 *
	 * @param array $args Arguments for the text field
	 * @param bool $return_html
	 * @return false|string
	 */
	public function text( $args = array(), $return_html=false ) {

		if ( $return_html ) {
			ob_start();
		}
		
		$args = $this->add_defaults( $args );

		$id             =  esc_attr( $args['name'] );
		$autocomplete   = ( $args['autocomplete'] ? 'on' : 'off' );
		$readonly       = $args['readonly'] ? ' readonly' : '';

		$data = '';
		if ( ! empty( $args['data'] ) ) {
			foreach ( $args['data'] as $key => $value ) {
				$data .= 'data-' . $key . '="' . $value . '" ';
			}
		}
		$main_tag = '';
		if ( empty( $args['main_tag'] ) ) {
			$main_tag = 'li';
		} else {
			$main_tag = $args['main_tag'];
		}


		?>
		
		<<?php echo $main_tag; ?> class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group" >

			<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id ?>">
				<?php echo esc_html( $args['label'] )?>
			</label>

			<div class="input_container <?php echo esc_html( $args['input_class'] ); ?>" id="">
				<input type="text"
				       name="<?php echo $id ?>"
				       id="<?php echo $id ?>"
				       autocomplete="<?php echo $autocomplete; ?>"
				       value="<?php echo esc_attr( $args['value'] ); ?>"
				       placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
						<?php
						echo $data . $readonly
						?>
                       maxlength="<?php echo $args['max']; ?>"
				/>
			</div>

		</<?php echo $main_tag; ?>>		<?php

		if ( $return_html ) {
			return ob_get_clean();
		}
		
		return '';
	}

	/**
	 * Renders an HTML textarea
	 *
	 * @param array $args Arguments for the textarea
	 */
	public function textarea( $args = array() ) {

		$defaults = array(
			'name'        => 'textarea',
			'class'       => 'large-text',
			'rows'        => 4
		);
		$args = $this->add_defaults( $args, $defaults );

		$disabled = '';
		if( $args['disabled'] ) {
			$disabled = ' disabled="disabled"';
		}

		$id =  esc_attr( $args['name'] );		?>

		<li class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group" >

		<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id ?>">
			<?php echo esc_html( $args['label'] )?>
		</label>
			<div class="input_container <?php echo esc_html( $args['input_class'] )?>" id="">
				<textarea
					   rows="<?php echo esc_attr( $args['rows'] ); ?>"
				       name="<?php echo esc_attr( $args['name'] ); ?>"
				       id="<?php echo $id ?>"
				       value="<?php echo esc_attr( $args['value'] ); ?>"
				       placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
					<?php echo $disabled; ?> >
				</textarea>
			</div>

		</li>		<?php

		if ( ! empty( $args['info'] ) ) { ?>
			<span class="info-icon"><p class="hidden"><?php echo $args['info']; ?></p></span>		<?php 
		}

	}

	/**
	 * Renders an HTML Checkbox
	 *
	 * @param array $args
	 * @return string
	 */
	public function checkbox( $args = array(), $return_html=false ) {
	
		if ( $return_html ) {
			ob_start();
		}
		
		$defaults = array(
			'name'         => 'checkbox',
			'class'        => '',
		);
		$args = $this->add_defaults( $args, $defaults );
		$id             =  esc_attr( $args['name'] );
		$checked = checked( "on", $args['value'], false );		?>

		<div class="config-input-group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id; ?>_group">

			<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id; ?>">
				<?php echo esc_html( $args['label'] ); ?>
			</label>

			<div class="input_container <?php echo esc_html( $args['input_class'] ); ?>" id="">
				<input type="checkbox"
				       name="<?php echo $id ?>"
				       id="<?php echo $id ?>"
				       value="on"
					<?php echo $checked; ?> />
			</div>
		</div>			<?php
		
		if ( $return_html ) {
			return ob_get_clean();
		}
		
		return '';
	}

	/**
	 * Renders an HTML radio button
	 *
	 * @param array $args
	 */
	public function radio_button( $args = array() ) {
		
		$defaults = array(
			'name'         => 'radio-buttons',
			'class'        => '',
		);
		$args = $this->add_defaults( $args, $defaults );
		$id =  esc_attr( $args['name'] );
		$checked = checked( 1, $args['value'], false );		?>

		<li class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group">

			<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id ?>">
				<?php echo esc_html( $args['label'] )?>
			</label>

			<div class="input_container <?php echo esc_html( $args['input_class'] )?>" id="">
				<input type="radio"
				       name="<?php echo $id ?>"
				       id="<?php echo $id ?>"
				       value="<?php echo esc_attr( $args['value'] ); ?>"
					<?php echo $checked; ?> />
			</div>			<?php
			
			if ( ! empty( $args['info'] ) ) { ?>
				<span class='info-icon'><p class='hidden'><?php echo $args['info']; ?></p></span>";			<?php 
			} ?>

		</li>		<?php
	}

	/**
	 * Renders an HTML drop-down box
	 *
	 * @param array $args
	 */
	public function dropdown( $args = array() ) {

		$defaults = array(
			'name'         => 'select',
		);
		$args = $this->add_defaults( $args, $defaults );

		$id =  esc_attr( $args['name'] );		?>
		
		<li class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group">
			<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id ?>">
				<?php echo esc_html( $args['label'] )?>
			</label>

			<div class="input_container <?php echo esc_html( $args['input_class'] )?>" id="">

				<select name="<?php echo $id ?>" id="<?php echo $id ?>">     <?php
					foreach( $args['options'] as $key => $label ) {
						$selected = selected( $key, $args['current'], false );
						echo '<option value="' . esc_attr($key) . '"' . $selected . '>' . esc_html($label) . '</option>';
					}  ?>
				</select>
			</div>		<?php
			
			if ( ! empty( $args['info'] ) ) { ?>
				<span class='info-icon'><p class='hidden'><?php echo $args['info']; ?></p></span>			<?php 
			}	?>
			
		</li>		<?php 
	}

	/**
	 * Renders several HTML radio buttons in a row
	 *
	 * @param array $args
	 */
	public function radio_buttons_horizontal( $args = array() ) {

		$defaults = array(
			'id'                => 'radio',
			'name'              => 'radio-buttons',
			'main_label_class'  => '',
			'radio_class'       => '',
		);
		$args = $this->add_defaults( $args, $defaults );
		$id =  esc_attr( $args['name'] );
		$ix = 0;   		?>

		<li class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group" >

			<span class="main_label <?php echo esc_html( $args['main_label_class'] )?>"><?php echo esc_html( $args['label'] ); ?></span>

			<div class="radio-buttons-horizontal <?php echo esc_html( $args['input_class'] )?>" id="<?php echo $id ?>">
				<ul>					<?php
				
					foreach( $args['options'] as $key => $label ) {
						$checked = checked( $key, $args['current'], false );						?>

						<li class="<?php echo esc_html( $args['radio_class'] )?>">
							<div class="input_container">
								<input type="radio"
								       name="<?php echo esc_attr( $args['name'] ); ?>"
								       id="<?php echo $id.$ix; ?>"
								       value="<?php echo esc_attr( $key ); ?>"									<?php
									echo $checked				?> 
								/>
							</div>

							<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id.$ix ?>">
								<?php echo esc_html( $label )?>
							</label>
						</li>						<?php

						$ix++;
					} //foreach    	?>

				</ul>  <?php

				if ( ! empty( $args['info'] ) ) { ?>
					<span class="info-icon"><p class="hidden"><?php echo ( $args['info'] ); ?></p></span>
				<?php } ?>
			</div>

		</li>		<?php
	}

	/**
	 * Renders several HTML radio buttons in a column
	 *
	 * @param array $args
	 */
	public function radio_buttons_vertical( $args = array() ) {

		$defaults = array(
			'id'           => 'radio',
			'name'         => 'radio-buttons',
			'class'        => '',
		);
		$args = $this->add_defaults( $args, $defaults );

		$ix = 0;
		$id =  esc_attr( $args['name'] );	?>
		
		<li class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group" >

			<span class="main_label <?php echo esc_html( $args['main_label_class'] )?>"><?php echo esc_html( $args['label'] ); ?></span>

			<div class="radio-buttons-vertical <?php echo esc_html( $args['input_class'] )?>" id="<?php echo $id ?>">
				<ul>					<?php

					foreach( $args['options'] as $key => $label ) {
						$id = empty($args['name']) ? '' :  esc_attr($args['name'] ) . '_choice_' . $ix;
						$checked = checked( $key, $args['current'], false );
						$checked_list   = '';

						if( $args['current'] == $key ) {
						    $checked_list = 'epkb-radio-checked';
						}						?>

						<li class="<?php echo esc_html( $args['radio_class'] ).' '.$checked_list; ?>">
							<div class="input_container">
								<input type="radio"
								       name="<?php echo esc_attr( $args['name'] ); ?>"
								       id="<?php echo $id; ?>"
								       value="<?php echo esc_attr( $key ); ?>"									<?php
									echo $checked;	?> 
								/>
							</div>
							<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id; ?>">
								<?php echo esc_html( $label )?>
							</label>
						</li>						<?php

						$ix++;
					}//foreach					?>
					
				</ul>
			</div>

		</li>		<?php
	}

	/**
	 * Single Inputs for text_fields_horizontal function
	 * @param array $args
	 */
	public function horizontal_text_input( $args = array() ){

		$args = $this->add_defaults( $args );

		//Set Values
		$id             =  esc_attr( $args[ 'name' ] );
		$autocomplete   = ( $args[ 'autocomplete' ] ? 'on' : 'off' );
		$disabled       = $args[ 'disabled' ] ? ' disabled="disabled"' : '';

		$data = '';
		if ( ! empty( $args['data'] ) ) {
			foreach ( $args['data'] as $key => $value ) {
				$data .= 'data-' . $key . '="' . $value . '" ';
			}
		}		?>

		<li class="<?php echo esc_html( $args['text_class'] )?>">

			<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id; ?>">
				<?php echo esc_html( $args['label'] )?>
			</label>
			<div class="input_container">
				<input type="text"
				       name="<?php echo $id; ?>"
				       id="<?php echo $id; ?>"
				       autocomplete='<?php echo $autocomplete; ?>'
				       value="<?php echo esc_attr( $args['value'] ); ?>"
				       placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
				       maxlength="<?php echo $args['max']; ?>"					<?php
						echo $data . $disabled;	?>	/>
			</div>

		</li>	<?php 
	}

	/**
	 * Renders two text fields. The second text field depends in some way on the first one
	 *
	 * @param array $common - configuration for the main classes
	 * @param array $args1  - configuration for the first text field
	 * @param array $args2  - configuration for the second field
	 */
	public function text_fields_horizontal( $common = array(), $args1 = array(), $args2 = array() ) {

		$defaults = array(
			'name'         => 'text',
			'class'        => '',
		);

		$common = $this->add_common_defaults( $common, $defaults );

		$args1 = $this->add_defaults( $args1, $defaults );
		$args2 = $this->add_defaults( $args2, $defaults );		?>
		
		<li class="input_group <?php echo esc_html( $common['input_group_class'] )?>" id="<?php echo $common['id']; ?>_group" >
			<span class="main_label <?php echo esc_html( $common['main_label_class'] )?>"><?php echo esc_html( $common['label'] ); ?></span>
			<div class="text-fields-horizontal <?php echo esc_html( $common['input_class'] )?>">
				<ul>   <?php

					$this->horizontal_text_input($args1);
					$this->horizontal_text_input($args2); ?>

				</ul>
			</div>
		</li>		<?php
	}

	/**
	 * Renders two text fields that related to each other. One field is text and other is select.
	 *
	 * @param array $common
	 * @param array $args1
	 * @param array $args2
	 */
	public function text_and_select_fields_horizontal( $common = array(), $args1 = array(), $args2 = array() ) {

		$args1 = $this->add_defaults( $args1 );
		$args2 = $this->add_defaults( $args2 );
		$common = $this->add_common_defaults( $common );		?>

		<li class="input_group <?php echo esc_html( $common['input_group_class'] )?>" id="<?php echo $common['id']; ?>_group" >
			<span class="main_label <?php echo esc_html( $common['main_label_class'] )?>"><?php echo esc_html( $common['label'] ); ?></span>
			<div class="text-select-fields-horizontal <?php echo esc_html( $common['input_class'] )?>">
				<ul>  <?php

					$this->text($args1);
					$this->dropdown($args2);

					// HELP
					$help_text = $common['info'];
					if ( ! empty( $help_text ) ) { ?>
						<span class='info-icon'><p class='hidden'><?php echo $help_text; ?></p></span>					<?php 
					}	?>

				</ul>
			</div>
		</li>		<?php
	}

	/**
	 * Renders several HTML checkboxes in several columns
	 *
	 * @param array $args
	 * @param $is_multi_select_not
	 */
	public function checkboxes_multi_select( $args = array(), $is_multi_select_not ) {

		$defaults = array(
			'id'           => 'checkbox',
			'name'         => 'checkbox',
			'value'        => array(),
			'class'        => '',
			'main_class'   => '',
			'main_tag' => 'li'
		);
		$args = $this->add_defaults( $args, $defaults );
		$id =  esc_attr( $args['name'] );
		$ix = 0;    	?>

		<<?php echo esc_html( $args['main_tag'] )?> class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id ?>_group" >

			<span class="main_label <?php echo esc_html( $args['main_label_class'] )?>"><?php echo esc_html( $args['label'] ); ?></span>

			<div class="checkboxes-vertical <?php echo esc_html( $args['input_class'] )?>" id="<?php echo $id ?>">
				<ul>  		<?php

					foreach( $args['options'] as $key => $label ) {

						$tmp_value = is_array($args['value']) ? $args['value'] : array();

						if ( $is_multi_select_not ) {
							$checked = in_array($key, array_keys($tmp_value)) ? '' : 'checked';
						} else {
							$checked = in_array($key, array_keys($tmp_value)) ? 'checked' : '';
						}

						$label = str_replace(',', '', $label);   			?>

						<li class="input_group <?php echo esc_html( $args['input_group_class'] )?>" id="<?php echo $id; ?>_group">
							<?php
							if ( $is_multi_select_not ) { ?>
								<input type="hidden" value="<?php echo esc_attr( $key . '[[-HIDDEN-]]' . $label ); ?>" name="<?php echo esc_attr( $args['name'] ) . '_' . $ix; ?>">
							<?php }	?>

							<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id.$ix; ?>">
								<?php echo esc_html( $args['label'] ); ?>
							</label>

							<div class="input_container <?php echo esc_html( $args['input_class'] )?>" id="">
								<input type="checkbox"
								       name="<?php echo $id. '_' . $ix; ?>"
								       id="<?php echo $id.$ix; ?>"
								       value="<?php echo esc_attr( $key . '[[-,-]]' . $label ); ?>"
									<?php echo $checked; ?>
								/>
							</div>
						</li>   	<?php

						$ix++;
					} //foreach   	?>

				</ul>
			</div>
		</<?php echo esc_html( $args['main_tag'] )?>>   <?php
	}

	/**
	 * Output submit button
	 *
	 * @param string $button_label
	 * @param string $action
	 * @param string $class
	 * @param string $html - any additional hidden fields
	 * @param bool $unique_button - is this unique button or a group of buttons - use 'ID' for the first and 'class' for the other
	 *
	 * @return string
	 */
	public function submit_button( $button_label, $action, $class='', $html='', $unique_button=true, $return_html=false  ) {


		if ( $return_html ) {
			ob_start();
		}		?>
		
		<div class=" <?php echo $class; ?>">
			<input type="hidden" id="_wpnonce_<?php echo $action; ?>" name="_wpnonce_<?php echo $action; ?>" value="<?php echo wp_create_nonce( "_wpnonce_$action" ); ?>"/>
            <input type="hidden" name="action" value="<?php echo $action; ?>"/>     <?php
            if ( $unique_button ) {  ?>
	            <input type="submit" id="<?php echo $action; ?>" class="primary-btn" value="<?php echo $button_label; ?>" />  <?php
            } else {    ?>
	            <input type="submit" class="<?php echo $action; ?> primary-btn" value="<?php echo $button_label; ?>" />  <?php
            }
			echo $html;  ?>
		</div>  <?php

		if ( $return_html ) {
			return ob_get_clean();
		}
		return '';
	}

	// Basic Form Elements------------------------------------------------------------------------------------------/

	/**
	 * Renders an HTML Text field
	 * This has Wrappers because you need to be able to wrap both elements ( Label , Input )
	 *
	 * @param array $args Arguments for the text field
	 * @return string Text field
	 */
	public function text_basic( $args = array() ) {

		$args = $this->add_defaults( $args );
		$id             = $args['name'];
		$autocomplete   = $args['autocomplete'] ? 'on' : 'off';
		$readonly       = $args['readonly'] ? ' readonly' : '';
		$data = '';
		$label_wrap_open  = '';
		$label_wrap_close = '';
		$input_wrap_open  = '';
		$input_wrap_close = '';

		if ( ! empty( $args['data'] ) ) {
			foreach ( $args['data'] as $key => $value ) {
				$data .= 'data-' . $key . '="' . $value . '" ';
			}
		}
        if ( ! empty( $args['label_wrapper']) ) {
	        $label_wrap_open   = '<' . esc_html( $args['label_wrapper'] ) . ' class="' . esc_html( $args['main_label_class'] ) . '" >';
	        $label_wrap_close  = '</' . esc_html( $args['label_wrapper'] ) . '>';
        }
		if ( ! empty( $args['input_wrapper']) ) {
			$label_wrap_open   = '<' . esc_html( $args['input_wrapper'] ) . ' class="' . esc_html( $args['input_group_class'] ) . '" >';
			$label_wrap_close  = '<' . esc_html( $args['input_wrapper'] ) . '>';
		}

		if ( $args['return_html'] ) {
			ob_start();
        }

        echo $label_wrap_open;  ?>
		<label class="<?php echo esc_html( $args['label_class'] ); ?>" for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $args['label'] ); ?></label>		<?php
        echo $label_wrap_close;

        echo  $input_wrap_open; ?>
		<input type="text" name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_html( $args['input_class'] ); ?>"
               autocomplete="<?php echo $autocomplete; ?>" value="<?php echo esc_attr( $args['value'] ); ?>"
               placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>" maxlength="<?php echo $data; ?>" <?php echo $readonly; ?> />		<?php
        echo  $input_wrap_close;

		if ( $args['return_html'] ) {
			return ob_get_clean();
		}
		return '';
	}

	/**
	 * Return submit button that has minimum HTML.
	 *
	 * @param  array  $args Arguments for the submit field
     * @return string Submit field
	 */
	public function submit_basic( $args = array() ) {

        $args = $this->add_defaults( $args );
        $action = esc_attr( $args['action'] );

        $button_id = '';
        if ( $args['unique'] ) {
            $button_id = $action;
        }

        if ( $args['return_html'] ) {
            ob_start();
        } ?>

        <input type="hidden" id="_wpnonce_<?php echo $action; ?>" name="_wpnonce_<?php echo $action; ?>" value="<?php echo wp_create_nonce( "_wpnonce_$action" ); ?>"/>
        <input type="hidden" name="action" value="<?php echo $action; ?>"/>
        <input type="submit" id="<?php echo $button_id; ?>" class="<?php echo esc_html( $args['input_class'] ) ?>" value="<?php echo esc_attr( $args['value'] ); ?>" />        <?php

        if ( $args['return_html'] ) {
            return ob_get_clean();
        }
        return '';
	}

	/**
	 * Renders an HTML Checkbox
	 *
	 * @param array $args
	 */
	public function checkbox_basic( $args = array() ) {

		$defaults = array(
			'name'         => 'checkbox',
			'class'        => '',
		);
		$args = $this->add_defaults( $args, $defaults );
		$id             =  esc_attr( $args['name'] );
		$checked = checked( "on", $args['value'], false );		?>

		<label class="<?php echo esc_html( $args['label_class'] )?>" for="<?php echo $id; ?>">
				<?php echo wp_kses( $args['label'], array(
					'a' => array(
						'href' => array(),
						'title' => array()
					),
					'br' => array(),
					'em' => array(),
					'strong' => array(),
				) ); ?>
		</label>
		<input type="checkbox"
		       name="<?php echo $id ?>"
		       id="<?php echo $id ?>"
		       value="on"
			<?php echo $checked; ?>
		/>

		<?php

			if ( !empty( $args['info'] ) ) { ?>
				<span class='info-icon'><p class='hidden'><?php echo $args['info']; ?></p></span>			<?php
			} ?>

		<?php
	}

	// Other Elements------------------------------------------------------------------------------------------/

	/*
		HTML Notification box with Title and Body text.

		Copied HTML / CSS from CREL Plugin
		$values:
		@param: string $value['id']            ( Optional ) Container ID, used for targeting with other JS
		@param: string $value['type']          ( Required ) ( error, success, warning, info )
		@param: string $value['title']         ( Required ) The big Bold Main text
		@param: HTML   $value['desc']          ( Required ) Any HTML P, List etc...
		@since version 6.8.0
	 */
	public function notification_box_basic( $args = array() ) {

		$icon = '';
		switch ( $args['type']) {
			case 'error':   $icon = 'epkbfa-exclamation-triangle';
			break;
			case 'success': $icon = 'epkbfa-check-circle';
			break;
			case 'warning': $icon = 'epkbfa-exclamation-circle';
			break;
			case 'info':    $icon = 'epkbfa-info-circle';
			break;

		}

		?>

		<div <?php echo isset($args['id']) ? 'id="'.$args['id'].'"' : ''; ?>class="epkb-notification-box-basic <?php echo 'epkb-notification-box-basic--'.$args['type']; ?>">

				<div class="epkb-notification-box-basic__icon">
					<div class="epkb-notification-box-basic__icon__inner epkbfa <?php echo $icon; ?>"></div>
				</div>

				<div class="epkb-notification-box-basic__body">
					<h4 class="epkb-notification-box-basic__body__title"><?php echo $args['title']; ?></h4>
					<div class="epkb-notification-box-basic__body__desc"><?php echo $args['desc']; ?></div>
				</div>

		</div>    <?php
	}

	/*
		HTML Notification box with paragraph / checkbox options and submit / cancel buttons.
		$values:
		@param: string $value['id']            ( Required ) Container ID, used for targeting with other JS
		@param: string $value['type']          ( Required ) How it will look ( epkb-error = Red  )
		@param: string $value['header']        ( Required ) The big Bold Main text
		@param: HTML   $value['content']       ( Required ) Any HTML P, List etc...
		@param: array  $value['checkboxes']    ( Optional ) Pass in an array of text labels.
	 */
	public function confirmation_box_with_checkboxes( $args ) { ?>

        <div class="epkb-notification-box">

            <form id="<?php echo $args['form_id']; ?>" class="<?php echo $args['type']; ?>">

                <section class="epkb-header">
                    <h3><?php echo $args['header']; ?></h3>
                </section>

                <section class="epkb-body">					<?php

                    if ( isset( $args['content'] ) ) {      ?>
                        <div class="epkb-body-content"><?php echo $args['content']; ?></div>                    <?php
                    }

					if ( isset( $args['checkboxes'] ) ) {   ?>
                        <div class="epkb-body-checkboxes">
                            <ul>
                                <?php foreach ( $args['checkboxes'] as $checkbox ) {
	                                $this->checkbox( $checkbox );
                               } ?>
                            </ul>
                        </div>					<?php
					}   ?>

                </section>

                <section class="epkb-footer">   <?php
					if ( isset ( $args['cancel_button'] ) ) {   ?>
                        <button id="epkb-notification-box-cancel" class="epkb-error-btn"><?php echo $args['cancel_button']; ?></button>       <?php
					}   ?>
                    <button id="epkb-notification-box-apply" class="epkb-success-btn"><?php echo $args['apply_button']; ?></button>
                </section>  <?php

	            wp_nonce_field(  $args['wpnonce'],  $args['wpnonce'] );  ?>

            </form>

        </div>    <?php
	}

	/*
		HTML Notification box with paragraph / checkbox options and submit / cancel buttons. With collapsed checkboxes and submit buttons.
		$values:
		@param: string $value['id']            ( Required ) Container ID, used for targeting with other JS
		@param: string $value['type']          ( Required ) How it will look ( epkb-error = Red  )
		@param: string $value['header']        ( Required ) The big Bold Main text
		@param: HTML   $value['content']       ( Required ) Any HTML P, List etc...
		@param: array  $value['checkboxes']    ( Optional ) Pass in an array of text labels.
	 */
	public function confirmation_box_with_checkboxes_collapse( $args ) { ?>

        <div class="epkb-notification-box epkb-collapse">

            <form id="<?php echo $args['form_id']; ?>" class="<?php echo $args['type']; ?>">

                <section class="epkb-header">
                    <h3><?php echo $args['header']; ?></h3>
                </section>


                <section class="epkb-body">
                    <?php if ( isset( $args['content'] ) ) { ?>
                        <div class="epkb-body-content"><?php echo $args['content']; ?></div>
                        <?php }	?>
	                <div class="epkb-content-toggle"><?php echo $args['toggle_text']; ?><span class="epkbfa epkbfa-arrow-circle-down"></span> </div>

					<?php if ( isset( $args['checkboxes'] ) ) {   ?>
                        <div class="epkb-body-checkboxes">
                            <ul>
								<?php foreach ( $args['checkboxes'] as $checkbox ) {
									$this->checkbox( $checkbox );
								} ?>
                            </ul>
                        </div>					<?php
					}   ?>

                </section>
                <section class="epkb-footer">   <?php
					if ( isset ( $args['cancel_button'] ) ) {   ?>
                        <button id="epkb-notification-box-cancel" class="epkb-error-btn"><?php echo $args['cancel_button']; ?></button>       <?php
					}   ?>
                    <button id="epkb-notification-box-apply" class="epkb-success-btn"><?php echo $args['apply_button']; ?></button>
                </section>  <?php

				wp_nonce_field(  $args['wpnonce'],  $args['wpnonce'] );  ?>

            </form>

        </div>    <?php
	}

	/*
		HTML toggle Box.
		$values:
		@param: string $value['id']            ( Required ) Container ID, used for targeting with other JS
		@param: string $value['title']         ( Required ) The text while its collapsed
		@param: HTML   $value['content']       ( Required ) Any HTML P, List etc...
	 */
	public function toggle_box( $args ) { ?>

        <div id="<?php echo $args['id']; ?>" class="epkb-toggle-box epkb-toggle-closed">

                <section class="epkb-toggle-box-header">
                    <h3>
                        <?php echo $args['title']; ?>
                        <span class="epkb-toggle-box-icon epkbfa epkbfa-arrow-circle-down"></span>
                    </h3>

                </section>

                <section class="epkb-toggle-box-body">
                    <div class="epkb-toggle-box-content"><?php echo $args['content']; ?></div>
                </section>

        </div>    <?php
	}

	/*
		HTML Info Box.
		$values:
		@param: string $value['id']            ( Required ) Container ID, used for targeting with other JS
		@param: string $value['title']         ( Required ) The text title
		@param: HTML   $value['content']       ( Required ) Any HTML P, List etc...
	 */
	public function info_box( $args ) {  ?>

        <span id="<?php echo $args['id']; ?>" class="epkb-info-box epkb-info-toggle-closed">

            <div class="epkb-info-box-icon epkbfa epkbfa-info-circle"></div>


            <div class="epkb-info-box-popup">
                <div class="epkb-info-box-container">
                    <span class="epkb-info-box-close epkbfa epkbfa-times"></span>
                    <span class="epkb-info-box-icon-max epkbfa epkbfa-expand"></span>
                    <span class="epkb-info-box-icon-min epkbfa epkbfa-compress"></span>
                    <section class="epkb-info-box-header">
                        <h3><?php echo $args['title']; ?></h3>
                    </section>
                    <section class="epkb-info-box-body">
                        <div class="epkb-info-box-content"><?php echo $args['content']; ?></div>
                    </section>
                </div>
            </div>

        </span>	<?php
	}

	/*
		HTML Info Box Version 2
		$values:
		@param: string $icon            Icon to display
		@param: string $title           The text title
		@param: string $dec             Text for box
		@param: string $buttonText      Text for Button
		@param: string $buttonURL       Link
	 */
	public function info_box_v2( $icon, $title, $dec, $buttonText, $buttonURL, $buttonClass = 'epkb-aibb-btn--blue', $buttonText2='', $buttonURL2='' ) { ?>

		<div class="epkb-admin-info-box">

			<div class="epkb-admin-info-box__header">
				<div class="epkb-admin-info-box__header__icon <?php echo $icon; ?>"></div>
				<div class="epkb-admin-info-box__header__title"><?php echo $title; ?></div>
			</div>

			<div class="epkb-admin-info-box__body">
				<p><?php echo $dec; ?></p>
				<?php if ( $buttonText ) { ?>
					<a href="<?php echo $buttonURL; ?>" target="_blank" class="epkb-aibb-btn <?php echo $buttonClass; ?>"><?php echo $buttonText; ?></a>
				<?php } ?>
				<?php if ( $buttonText2 ) { ?>
					<a href="<?php echo $buttonURL2; ?>" target="_blank" class="epkb-aibb-btn epkb-aibb-btn--blue"><?php echo $buttonText2; ?></a>
				<?php } ?>
			</div>

		</div>	<?php 
	}

	/*
		HTML Info Box with image
		$values:
		@param: string $icon            Icon to display
		@param: string $title           The text title
		@param: string $dec             Text for box
		@param: string $buttonText      Text for Button
		@param: string $buttonURL       Link
		@param: string $img_url         URL of image.
	 */
	public function info_box_with_img( $icon, $title, $dec, $buttonText, $buttonURL, $img_url ) { ?>

		<div class="epkb-admin-info-box-img">

			<div class="epkb-admin-info-box-img__header">
				<div class="epkb-admin-info-box-img__header__icon <?php echo $icon; ?>"></div>
				<div class="epkb-admin-info-box-img__header__title"><?php echo $title; ?></div>
			</div>

			<div class="epkb-admin-info-box-img__body">
				<img class="epkb-admin-info-box-img__body__img" src="<?php echo $img_url; ?>" alt="Info box image">
				<p><?php echo $dec; ?></p>
				<?php if ( $buttonText ) { ?>
					<a href="<?php echo $buttonURL; ?>" target="_blank" class="epkb-aibb-btn epkb-aibb-btn--blue"><?php echo $buttonText; ?></a>
				<?php } ?>
			</div>

		</div>	<?php
	}

	/*
		HTML Advertisement Box
		This box will have a title, image, either a description or list a button and more info link.
		$values:
	    @param: string $args['id']              ( Optional ) Container ID, used for targeting with other JS
	    @param: string $args['class']           ( Optional ) Container CSS, used for targeting with CSS
	    @param: string $args['icon']            ( Optional ) Icon to display ( from this list: https://fontawesome.com/v4.7.0/icons/ )
	    @param: string $args['title']           ( Required ) The text title
	    @param: string $args['img_url']         ( Required ) URL of image.
	    @param: string $args['desc']            ( Optional ) Paragraph Text
	    @param: array  $args['list']            ( Optional ) array() of list items.

	    @param: string $args['btn_text']        ( Optional ) Button Text
	    @param: string $args['btn_url']         ( Optional ) Button URL
	    @param: string $args['btn_color']       ( Required ) blue,yellow,orange,red,green

		@param: string $args['more_info_text']  ( Optional ) More Info Text
	    @param: string $args['more_info_url']   ( Optional ) More Info URL
	    @param: string $args['more_info_color'] ( Required ) blue,yellow,orange,red,green
	 */
	public function advertisement_ad_box( $args ) {

		$args = $this->add_defaults( $args );		?>

		<div id="<?php echo $args['id']; ?>" class="epkb-admin-ad-container <?php echo $args['class']; ?>">

			<!----- Box Type ----->
			<span class="epkb-admin-ad-container__widget"> <i class="epkbfa epkbfa-puzzle-piece " aria-hidden="true"></i><?php echo __( 'Plugin', 'echo-knowledge-base'); ?></span>

			<!----- Header ----->
			<div class="epkb-aa__header-container">
				<div class="epkb-header__icon epkbfa <?php echo $args['icon']; ?>"></div>
				<div class="epkb-header__title"><?php echo $args['title']; ?></div>
			</div>

			<!----- Body ---=--->
			<div class="epkb-aa__body-container">
				<div class="featured_img">
					<img class="epkb-body__img" src="<?php echo $args['img_url']; ?>" alt="<?php echo $args['title']; ?>">
				</div>
				<p class="epkb-body__desc"><?php echo $args['desc']; ?></p>

				<ul class="epkb-body__check-mark-list-container">					<?php
					if ( $args['list'] ) {
						foreach ($args['list'] as $item) {
							echo '<li class="epkb-check-mark-list__item">';
							echo '<span class="epkb-check-mark-list__item__icon epkbfa epkbfa-check"></span>';
							echo '<span class="epkb-check-mark-list__item__text">' . $item . '</span>';
							echo '</li>';
						}
					}					?>
				</ul>

				<?php if ( $args['btn_text'] ) { ?>
					<a href="<?php echo $args['btn_url']; ?>" target="_blank" class="epkb-body__btn epkb-body__btn--<?php echo $args['btn_color']; ?>"><?php echo $args['btn_text']; ?></a>
				<?php } ?>

				<?php if ( $args['more_info_text'] ) { ?>
					<a href="<?php echo $args['more_info_url']; ?>" target="_blank" class="epkb-body__link epkb-body__link--<?php echo $args['more_info_color']; ?>">
						<span class="epkb-body__link__icon epkbfa epkbfa-info-circle"></span>
						<span class="epkb-body__link__text"><?php echo $args['more_info_text']; ?></span>
						<span class="epkb-body__link__icon-after epkbfa epkbfa-angle-double-right"></span>

					</a>
				<?php } ?>

			</div>

		</div>	<?php
	}
}
