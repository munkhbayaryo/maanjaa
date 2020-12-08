'use strict';
jQuery(document).ready(function($) {

	// we need Editor settings to continue
	// noinspection JSUnresolvedVariable
	if ( typeof epkb_editor_config == 'undefined' || epkb_editor_config == null || epkb_editor_config.length == 0 ) {
		return true;
	}

	window.EPKBEditor = {

		init: function(){
			// start app only once
			if ( typeof this.initSettings !== 'undefined' ) {
				return;
			}
			
			this.showLoader();

			// order is important
			this.addModalWrap();
			// noinspection JSUnresolvedVariable
			this.initSettings = Object.assign( {}, epkb_editor_config );
			this.currentSettings = Object.assign( {}, epkb_editor_config );
			this.currentEditorSettings = Object.assign( {}, epkb_editor_settings );
			this.activeZone = '';
			this.addIframe();
		},
		
		addModalWrap: function(){
			if ( $('#epkb-editor').length > 0 ) {
				return true;
			}

			$('body').append( EPKBEditorTemplates.modalWindow() );
			$('.epkb-editor-settings__panel-content-container').append( EPKBEditorTemplates.notice( { 'icon' : 'arrow-circle-right','title' : epkb_editor.clear_modal_notice, 'style' : 'edit-zone' } ) );
			this.modal = $('#epkb-editor');
			this.showModal(); 
			this.bindModalEvents();

			// Hide help link
			$('.epkb-editor-settings__panel-content-container .epkb-editor-settings__help').hide();
		},

		addIframe: function(){
			// noinspection ES6ModulesDependencies
			$('#wp-admin-bar-epkb-edit-mode-button a').text( epkb_editor.loading );

			let iframe = document.createElement('iframe');
			this.url = new URL( location.href );
			this.url.searchParams.set( 'epkb-editor', '1' );
			iframe.src = this.url;
			iframe.id = 'epkb-editor-iframe';
			document.body.appendChild(iframe);

			$('#epkb-editor-iframe').on( 'load', this, this.afterLoadIframe); // will fire on iframe update too
		},

		afterLoadIframe: function( event ) {

			// TODO: check if the iframe changed URL ( links are blocked, but user scripts can change the page ) and bring it back
			
			$('.epkb-frontend-loader').addClass('epkb-frontend-loader--iframe');

			// "this"
			let app = event.data;

			// "wrap" for edit screen
			app.iframe = $('#epkb-editor-iframe').contents();
			
			// load new zones settings if exists 
			app.currentSettings = Object.assign( {}, $('#epkb-editor-iframe')[0].contentWindow.epkb_editor_config, app.currentSettings );
			
			for ( let zone_name in app.currentSettings ) {
				if ( typeof $('#epkb-editor-iframe')[0].contentWindow.epkb_editor_config[zone_name] == 'undefined' ) {
					delete app.currentSettings[zone_name];
				}
			}
			
			// add styles tag to change styles in the iframe because jquery can't change hovers
			app.iframe.find('body').append(`<style type="text/css" id="epkb-editor-style"></style>`);
			app.styles = app.iframe.find('#epkb-editor-style');

			// turn on styles for body to hide it
			$('body').addClass('epkb-edit-mode');

			// "loader" off
			$('#wp-admin-bar-epkb-edit-mode-button a').text( epkb_editor.turned_on );

			// a lot of lo-end themes don't use body_class()
			app.iframe.find('body').addClass('epkb-editor-preview');

			// highlight edit zones
			app.addEditZones();

			// fill settings with current zone after click
			app.iframe.on( 'click', '.epkb-editor-zone', app, app.onZoneClick );
			app.iframe.on( 'click', '.epkb-editor-zone__tabs', false );
			app.iframe.on( 'click', '.epkb-editor-zone__tab--parent', function( event ){
				
				event.stopPropagation();
				
				let zone = $(this).data('zone');
				
				$(this).parents('.epkb-editor-zone').each(function(){
					if ( $(this).data('zone') == zone ) {
						$(this).click();
						zone = false;
					}
				});
				
			} );

			// block links inside iframes
			app.iframe.find('a').click( function(e){
				e.preventDefault();
			} );

			// block forms on the page to prevent page change
			app.iframe.on( 'submit', 'form:not(#epkb-settings-form)', function(e){
				app.removeLoader();
				return false;
			} );

			
			
			app.iframe.find( '.epkb-editor-zone' ).hover( function(){
				$(this).addClass( 'hover' );

				let parents = $(this).parents( '.epkb-editor-zone' );

				if ( parents.length ) {
					setTimeout( function() {
						parents.removeClass( 'hover' );
					}, 50 );
				}

			}, function(){
				$(this).removeClass( 'hover' );
				if ( $(this).parents( '.epkb-editor-zone' ).length && ! $(this).parents( '.epkb-editor-zone--active' ).length ) {
					$(this).parents( '.epkb-editor-zone' ).eq(0).addClass( 'hover' );
				}
			} );

			app.iframe.find('input[name="epkb_search_terms"]').prop('autocomplete','off');

			app.updateStyles();
			app.updateAttributes();
			app.updateText();
			
			// pre-open settings 
			if ( epkb_editor.preopen == 'templates' ) {	
				epkb_editor.preopen = 'open_themes';
				$('.epkb-editor-header__inner__config').click();
				$('.epkb-editor-settings-menu__group-item-container[data-name=templates]').click();
			}
			
			// select zone if we have such task 
			if ( typeof app.preselectZone == 'undefined' || typeof app.currentSettings[app.preselectZone] == 'undefined' || app.iframe.find( app.currentSettings[app.preselectZone].classes ).length == 0 ) {
				app.removeLoader();
				return;
			}
			
			app.iframe.find( app.currentSettings[app.preselectZone].classes ).click();
			app.preselectZone = undefined;
			app.removeLoader();
		},

		addEditZones: function(){
			for ( let settingGroupSlug in this.currentSettings ) {

				let zoneWrapper = this.iframe.find(this.currentSettings[settingGroupSlug].classes);
				
				if ( zoneWrapper.length == 0 ) {
					continue;
				}

				zoneWrapper.addClass('epkb-editor-zone');
				zoneWrapper.data( 'zone', settingGroupSlug );
			}
		},

		bindModalEvents: function() {
			// close editor button
			$(document).on( 'click', '#epkb-editor-close', this, this.hideModal );
			$('#epkb-editor-exit').on( 'click', this, this.toggleMode );

			// change any setting on the panel
			$(document).on( 'change keyup', '#epkb-editor input, #epkb-editor textarea, #epkb-editor select', this, this.onFieldChange );

			// modal tabs
			$(document).on( 'click', '#epkb-editor .epkb-editor-settings__panel-navigation__tab:not(.epkb-editor-settings__panel-navigation__tab--disabled)', this, this.onTabClick );
			
			// settings links 
			$(document).on( 'click', '.epkb-editor-settings-menu__group-item-container', this, this.onMenuItemClick );
			
			// linked inputs
			$(document).on( 'click', '#epkb-editor .epkb-editor-settings-control__input__linking', this, this.toggleLinkedDimensions );
			
			// save button
			$(document).on( 'click', '#epkb-editor-save', this, this.saveSettings );
			
			// notice close 
			$(document).on( 'click', '.epkb-close-notice', function(){
				$('.eckb-bottom-notice-message').remove();
			});
			
			// Click on the gears button 
			$(document).on( 'click', '.epkb-editor-header__inner__config', this, this.showSettings );
			
			// save/cancel editor button 
			$(document).on( 'click', '#epkb-editor-popup__button-cancel', this, this.onCancelWpEditor );
			$(document).on( 'click', '#epkb-editor-popup__button-update', this, this.onSaveWpEditor );
			$(document).on( 'click', '.epkb-editor-settings-wpeditor_button', this, this.showWpEditor );
			
			// back and close button in settings 
			$(document).on( 'click', '.epkb-editor-header__inner__back-btn', this, this.onBackClick );
			$(document).on( 'click', '.epkb-editor-header__inner__close-btn', this, this.onCloseClick );
			
			// click on the menu item 
			$( '#epkb-editor' ).on( 'click', '.epkb-editor-header__inner__menu-btn', this, this.showMenu );
			
			// eprf feedback settings 
			$( '#epkb-editor' ).on( 'click', '#eprf_edit_feedback_zone', this, function( event ) {
				let app = event.data;
				
				app.iframe.find('#eprf-article-feedback-container').click();
				
				return false;
			} );
		},
		
		// delete all content from modal before fill it for the new zone/settings screen 
		clearModal: function () {
			$('.epkb-editor-header__inner__title').html('');
			$('.epkb-editor-settings__panel-navigation-container').html('');
			$('.epkb-editor-settings__panel-content-container').html(EPKBEditorTemplates.modalTabsBody());
			$('.epkb-editor-settings__panel-navigation-container, .epkb-editor-settings__panel-content-container').show();
			$( '.epkb-editor-settings-menu-container' ).hide();
			this.iframe.find('.epkb-editor-zone__tabs').remove();
		},
		
		// toggle back/close button to menu/settings 
		showMenuButtons: function() {
			$('.epkb-editor-header__inner__menu-btn').show();
			$('.epkb-editor-header__inner__config').show();
			$('.epkb-editor-header__inner__back-btn').hide();
			$('.epkb-editor-header__inner__close-btn').hide();
		},
		
		// call before changing active zone 
		showBackButtons: function() {
			$('.epkb-editor-header__inner__menu-btn').hide();
			$('.epkb-editor-header__inner__config').hide();
			$('.epkb-editor-header__inner__back-btn').show();
			$('.epkb-editor-header__inner__close-btn').show();
			
			// remember last zone that the user editing
			if ( this.activeZone !== 'settings' || this.activeZone !== 'menu' || this.activeZone !== 'settings_panels' ) {
				this.lastActiveZone = this.activeZone;
			}
			
		},

		/*******  TAB CHANGE   ********/

		onTabClick: function ( event ) {
			event.stopPropagation();

			let tabName = $(this).data('target');

			$('.epkb-editor-settings__panel-navigation__tab').removeClass('epkb-editor-settings__panel-navigation__tab--active');
			$('.epkb-editor-settings__panel').removeClass('epkb-editor-settings__panel--active');

			$(this).addClass('epkb-editor-settings__panel-navigation__tab--active');
			$('#epkb-editor-settings-panel-' + tabName).addClass('epkb-editor-settings__panel--active');
		},
		
		onMenuItemClick: function ( event ) {
			
			if ( $(this).attr('href') != '#' ) {
				return true; // usual link 
			}
			
			let app = event.data;
			
			$('.epkb-editor-settings-panel-global__links-container, .epkb-editor-settings__panel-navigation-container').hide();
			$('#epkb-editor-settings-panel-global>.epkb-editor-settings-control-container').hide();
			
			// open templates 
			if ( $(this).data('name') == 'templates' ) {
				$('#epkb-editor-settings-templates').show();
			}
			
			// open layouts 
			if ( $(this).data('name') == 'layouts' ) {
				$('#epkb-editor-settings-layouts').show();
			}
			
			app.showBackButtons();
			app.activeZone = 'settings_panels';
			
			return false;
			
		},
		
		// "<" button in menu/otions 
		onBackClick: function ( event ) {
			event.stopPropagation();
			
			let app = event.data;
			
			if ( app.activeZone == 'settings_panels' ) {
				// show settings 
				app.showSettings( event );
			} else if ( typeof ( app.currentSettings[app.lastActiveZone] ) !== 'undefined' ) {
				app.iframe.find( app.currentSettings[app.lastActiveZone].classes ).click();
				app.showMenuButtons();
			} else {
				// TODO initial screen 
				app.clearModal();
				$('.epkb-editor-header__inner__title').html( epkb_editor.epkb_name );
				$('.epkb-editor-settings__panel-content-container').append( EPKBEditorTemplates.notice( { 'icon' : 'info-circle','title' : epkb_editor.clear_modal_notice, 'style' : 'edit-zone' } ) );
				$('.epkb-editor-settings__help').hide();
				app.showMenuButtons();
			}
		},
		
		// "X" button in menu/options
		onCloseClick: function ( event ) {
			event.stopPropagation();
			
			let app = event.data;
			
			if ( typeof ( app.currentSettings[app.lastActiveZone] ) !== 'undefined' ) {
				app.iframe.find( app.currentSettings[app.lastActiveZone].classes ).click();
				app.showMenuButtons();
			} else {
				// TODO initial screen 
				app.clearModal();
				$('.epkb-editor-header__inner__title').html( epkb_editor.epkb_name );
				$('.epkb-editor-settings__panel-content-container').append( EPKBEditorTemplates.notice( { 'icon' : 'info-circle','title' : epkb_editor.clear_modal_notice, 'style' : 'edit-zone' } ) );
				$('.epkb-editor-settings__help').hide();
				app.showMenuButtons();
			}
		},


		/*******  FIELD OPERATIONS   ********/

		onFieldChange: function( event ) {			
			
			let name = $(this).attr('name'),
				newVal = $(this).val(),
				refresh = false,
				app = event.data;
			
			// checkbox 
			if ( $(this).prop('type') == 'checkbox' ) {
				newVal = $(this).prop('checked') ? 'on' : 'off';
			}
			
			// check number type min and max 
			if ( $(this).attr('type') == 'number' && newVal == '' ) {
				
				if ( typeof $(this).attr('min') !== 'undefined' ) {
					newVal = $(this).attr('min');
				} else if ( typeof $(this).attr('max') !== 'undefined' ) {
					newVal = $(this).attr('max');
				}
			}
			
			// check settings panel 
			if ( $(this).closest('#epkb-editor-settings-panel-hidden').length > 0 ) {
				
				// wrong configs 
				if ( typeof app.currentSettings[name] == 'undefined' ) {
					return;
				}
				
				// update value 
				if ( newVal == 'on' ) {
					// turn on 
					for ( let optionName in app.currentSettings[name].disabled_settings ) {
						let optionsList = app.getOptionsList( optionName );
						
						if ( optionsList.length < 1 ) {
							continue;
						}
						
						if ( app.currentSettings[name].disabled_settings[optionName] == optionsList[0] ) {
							app.updateOption( optionName, optionsList[1] );
						} else {
							app.updateOption( optionName, optionsList[0] );
						}
					}
					
					// set zone that should be checked after reload 
					app.preselectZone = name;
					
				} else {
					// turn off 
					
					for ( let optionName in app.currentSettings[name].disabled_settings ) {
						app.updateOption( optionName, app.currentSettings[name].disabled_settings[optionName] );
					}
				}
				
				// reload iframe (zone will be selected after reload)
				app.refreshIframe( app );
				
				return;
			}
			
			// check dimensions 
			if ( $(this).closest('.epkb-editor-settings-control-type-dimensions').length > 0 ) {
				
				let groupName = $(this).closest('.epkb-editor-settings-control-type-dimensions').data('field');
				let isLinked = $(this).closest('.epkb-editor-settings-control-type-dimensions').find('.epkb-editor-settings-control__input__linking').hasClass('epkb-editor-settings-control__input__linking--active')
				
				for ( let settingGroupSlug in app.currentSettings ) {
					
					if ( typeof app.currentSettings[settingGroupSlug].settings[groupName] == 'undefined' ) {
						continue;
					}
					
					// something went wrong 
					if ( typeof app.currentSettings[settingGroupSlug].settings[groupName].subfields[name] == 'undefined' ) {
						continue; 
					}
					
					app.currentSettings[settingGroupSlug].settings[groupName].subfields[name].value = newVal;
					
					if ( typeof app.currentSettings[settingGroupSlug].settings[groupName].subfields[name].reload !== 'undefined' ) {
						refresh = true;
					}
					
					if ( ! isLinked ) {
						continue;
					}
					
					// if linked set all neibors values the same 
					$(this).closest('.epkb-editor-settings-control-type-dimensions').find('input').val( newVal );
					
					for ( let fieldName in app.currentSettings[settingGroupSlug].settings[groupName].subfields ) {
						app.currentSettings[settingGroupSlug].settings[groupName].subfields[fieldName].value = newVal;
					}
					
				}
			} 
			
			// check multiple 
			if ( $(this).closest('.epkb-editor-settings-control-type-multiple').length > 0 ) {
				
				let groupName = $(this).closest('.epkb-editor-settings-control-type-multiple').data('field');
				
				for ( let settingGroupSlug in app.currentSettings ) {
					
					if ( typeof app.currentSettings[settingGroupSlug].settings[groupName] == 'undefined' ) {
						continue;
					}
					
					// something went wrong 
					if ( typeof app.currentSettings[settingGroupSlug].settings[groupName].subfields[name] == 'undefined' ) {
						continue; 
					}
					
					app.currentSettings[settingGroupSlug].settings[groupName].subfields[name].value = newVal;
					
					if ( typeof app.currentSettings[settingGroupSlug].settings[groupName].subfields[name].reload !== 'undefined' ) {
						refresh = true;
					}					
				}
			} 
			
			for ( let settingGroupSlug in app.currentSettings ) {
				if ( typeof app.currentSettings[settingGroupSlug].settings[name] == 'undefined' ) {
					continue;
				}

				app.currentSettings[settingGroupSlug].settings[name].value = newVal;
				
				if ( typeof app.currentSettings[settingGroupSlug].settings[name].reload !== 'undefined' ) {
					refresh = true;
				}
			}
			
			if ( epkb_editor.page_type == 'article-page' ) {
				
				let leftSidebar = app.currentSettings.left_sidebar.settings,
				leftSidebarWidthDesktop = app.isLeftPanelActive() ? leftSidebar['article-left-sidebar-desktop-width-v2'].value : 0,
				//leftSidebarWidthTablet = app.iframe.find('#eckb-article-left-sidebar div').length ? leftSidebar['article-left-sidebar-tablet-width-v2'].value : 0,
				
				rightSidebar = app.currentSettings.right_sidebar.settings,
				rightSidebarWidthDesktop = app.isRightPanelActive() ? rightSidebar['article-right-sidebar-desktop-width-v2'].value : 0,
				//rightSidebarWidthTablet = app.iframe.find('#eckb-article-right-sidebar div').length ? rightSidebar['article-right-sidebar-tablet-width-v2'].value : 0,
				
				articleColumnSettings = [
					'article-right-sidebar-desktop-width-v2',
					'article-right-sidebar-tablet-width-v2',
					'article-left-sidebar-desktop-width-v2',
					'article-left-sidebar-tablet-width-v2',
				];
				
				if ( ~articleColumnSettings.indexOf( name ) ) {
					// recalculate content area 
					app.currentSettings.article_content.settings['article-content-desktop-width-v2'].value = 100 - leftSidebarWidthDesktop - rightSidebarWidthDesktop;
					//app.currentSettings.article_content.settings['article-content-tablet-width-v2'] = 100 - leftSidebarWidthTablet - rightSidebarWidthTablet;
				}
				
			}
			
			if ( typeof app.currentEditorSettings.settings_zone.settings[name] != 'undefined' ) {
				app.currentEditorSettings.settings_zone.settings[name].value = newVal;
				
				if ( typeof app.currentEditorSettings.settings_zone.settings[name].reload !== 'undefined' ) {
					refresh = true;
				}
			}
			
			app.checkTogglers();
			app.updateStyles();
			app.updateAttributes();
			app.updateText();
			
			if ( refresh ) {
				app.refreshIframe( app );
			}
		},
		
		addField: function( fieldName, field ) {
			// check type, see EPKB_Input_Filter class 				
				switch ( field.type ) {
					case 'color_hex':
						this.addColorPicker( fieldName, field );
						break;
					case 'text':
						this.addText( fieldName, field );
						break;
					case 'wp_editor': 
						this.addWpEditor( fieldName, field );
						break;
					case 'header':
						this.addHeader( fieldName, field );
						break;
					case 'header_desc':
						this.addheader_desc( fieldName, field );
						break;
					case 'select':
						this.addSelect( fieldName, field );
						break;
					case 'divider':
						this.addDivider( fieldName, field );
						break;
					case 'checkbox':
						this.addCheckbox( fieldName, field );
						break;	
					case 'number':
						this.addNumber( fieldName, field );
						break;
					case 'units':
						this.addUnits( fieldName, field );
						break;
						
					// TODO add 
					// wp_editor - for Elegant Layout
					// notice - when will need it 
				}
				
				switch( field.group_type ) {
					case 'dimensions':
						this.addDimensions( fieldName, field );
						break;
					case 'multiple': 
						this.addMultiple( fieldName, field );
						break;
				}
		},
		
		addHeader: function( fieldName, field ) {
			
			let el;
			if ( typeof field.element_wrapper == 'undefined' ) {
				el = $( '#epkb-editor-settings-panel-' + field.editor_tab );
			} else {
				el =  field.element_wrapper;
			}
			
			el.append( EPKBEditorTemplates.header( {
				name: fieldName,
				content: field.content,
			} ) );
		},

		addheader_desc: function( fieldName, field) {

			let el;
			if ( typeof field.element_wrapper == 'undefined' ) {
				el = $( '#epkb-editor-settings-panel-' + field.editor_tab );
			} else {
				el =  field.element_wrapper;
			}

			el.append( EPKBEditorTemplates.header_desc( {
				title: field.title,
				desc: field.desc,
			} ) );
		},

		addText: function( fieldName, field ) {
			
			let el;
			if ( typeof field.element_wrapper == 'undefined' ) {
				el = $( '#epkb-editor-settings-panel-' + field.editor_tab );
			} else {
				el =  field.element_wrapper;
			}
			
			el.append( EPKBEditorTemplates.text( {
				name: fieldName,
				label: field.label,
				value: field.value,
				style: field.style,
				html: ( typeof field.html != 'undefined' ),
				description: ( typeof field.description == 'undefined' ) ? '' : field.description
			} ) );
		},

		addNumber: function( fieldName, field ) {

			let data = {
				name: fieldName,
				label: field.label,
				value: field.value,
				style: field.style,
				separator_above: field.separator_above,
				description: ( typeof field.description == 'undefined' ) ? '' : field.description
			};

			if ( typeof field.style != 'undefined' ) {
				data.style = field.style;
			}

			if ( typeof field.max != 'undefined' ) {
				data.max = field.max;
			}

			if ( typeof field.min != 'undefined' ) {
				data.min = field.min;
			}

			
			let el;
			if ( typeof field.element_wrapper == 'undefined' ) {
				el = $( '#epkb-editor-settings-panel-' + field.editor_tab );
			} else {
				el =  field.element_wrapper;
			}
			
			el.append( EPKBEditorTemplates.number( data ) );
		},
		
		addUnits: function( fieldName, field ) {
			
			let el;
			if ( typeof field.element_wrapper == 'undefined' ) {
				el = $( '#epkb-editor-settings-panel-' + field.editor_tab );
			} else {
				el =  field.element_wrapper;
			}
			
			el.append( EPKBEditorTemplates.units( {
				name: fieldName,
				value: field.value,
				options: field.options,
			} ) );
		},

		addDimensions: function( fieldName, field ) {
			// here can be only 4 inputs one by one
			
			let el;
			if ( typeof field.element_wrapper == 'undefined' ) {
				el = $( '#epkb-editor-settings-panel-' + field.editor_tab );
			} else {
				el =  field.element_wrapper;
			}
			
			el.append( EPKBEditorTemplates.dimensions( {
				name: fieldName,
				label: field.label,
				subfields: field.subfields,
				units: ( typeof field.units == 'undefined' ) ? '' : field.units,
				linked: ( typeof field.linked == 'undefined' ) ? '' : field.linked,
				description: ( typeof field.description == 'undefined' ) ? '' : field.description
			} ) );
		},
		
		addMultiple: function ( fieldName, field ) {
			let $wrapper = $(`<div class="epkb-editor-settings-control-type-multiple" data-field="${fieldName}"></div>`);
			$( '#epkb-editor-settings-panel-' + field.editor_tab ).append($wrapper);
			
			for ( let subfieldName in field.subfields ) {
				field.subfields[subfieldName].editor_tab = field.editor_tab;
				field.subfields[subfieldName].element_wrapper = $wrapper;
				this.addField( subfieldName, field.subfields[subfieldName] );
			}
		},
		
		addSelect: function( fieldName, field ) {
			
			let el;
			if ( typeof field.element_wrapper == 'undefined' ) {
				el = $( '#epkb-editor-settings-panel-' + field.editor_tab );
			} else {
				el =  field.element_wrapper;
			}
			
			el.append( EPKBEditorTemplates.select( {
				name: fieldName,
				label: field.label,
				value: field.value,
				style: field.style,
				options: field.options,
				separator_above: field.separator_above,
				description: ( typeof field.description == 'undefined' ) ? '' : field.description

			} ) );
		},

		addCheckbox: function( fieldName, field ) {
			
			let el;
			if ( typeof field.element_wrapper == 'undefined' ) {
				el = $( '#epkb-editor-settings-panel-' + field.editor_tab );
			} else {
				el =  field.element_wrapper;
			}
			
			el.append( EPKBEditorTemplates.checkbox( {
				name: fieldName,
				label: field.label,
				value: field.value,
				separator_above: field.separator_above,
				description: ( typeof field.description == 'undefined' ) ? '' : field.description

			} ) );
		},

		addColorPicker: function( fieldName, field ) {
			
			let el;
			if ( typeof field.element_wrapper == 'undefined' ) {
				el = $( '#epkb-editor-settings-panel-' + field.editor_tab );
			} else {
				el =  field.element_wrapper;
			}
			
			el.append( EPKBEditorTemplates.colorPicker( {
				name: fieldName,
				label: field.label,
				value: field.value,
				separator_above: field.separator_above,
				description: ( typeof field.description == 'undefined' ) ? '' : field.description
			} ) );
		},

		addDivider: function( fieldName, field ) {
			
			let el;
			if ( typeof field.element_wrapper == 'undefined' ) {
				el = $( '#epkb-editor-settings-panel-' + field.editor_tab );
			} else {
				el =  field.element_wrapper;
			}
			
			el.append( EPKBEditorTemplates.divider() );
		},
		
		addWpEditor: function( fieldName, field ) {
			
			let el;
			if ( typeof field.element_wrapper == 'undefined' ) {
				el = $( '#epkb-editor-settings-panel-' + field.editor_tab );
			} else {
				el =  field.element_wrapper;
			}
			
			el.append( EPKBEditorTemplates.wpEditor( {
				name: fieldName,
				label: field.label,
				value: field.value,
				description: ( typeof field.description == 'undefined' ) ? '' : field.description
			} ) );
		},
		
		showWpEditor: function ( event ) {
			
			let app = event.data;
			
			let fieldName = $(this).closest('.epkb-editor-settings-control-type-wp-editor').data('field');
			
			// get value from fieldName 
			let text = app.getOption( fieldName );
			
			// fill value to the editor 
			
			if ( ! tinymce.get('epkbeditormce') ) {
				$('.epkb-editor-popup .wp-editor-wrap .switch-tmce').trigger('click');
			}
			
			tinymce.get('epkbeditormce').setContent( text );

			// set what field do we edit 
			$('#epkbeditormce').data( 'fieldName', fieldName );
			
			// update title of the popup 
			$('.epkb-editor-popup__header').text( $('textarea[name=' + fieldName + ']').closest( '.epkb-editor-settings-control__field' ).find('.epkb-editor-settings-control__title').text() );
			
			// show editor 
			$('.epkb-editor-popup').addClass( 'epkb-editor-popup--active' );
			
			return false;
		},
		
		onSaveWpEditor: function ( event ) {
			
			let app = event.data;
			
			// get field 
			let text = tinymce.get('epkbeditormce').getContent();
			let fieldName = $('#epkbeditormce').data( 'fieldName' );
			
			// save
			$('textarea[name=' + fieldName + ']').val( text );
			
			// hide popup 
			$('.epkb-editor-popup').removeClass( 'epkb-editor-popup--active' );
			
			// trigger update 
			$('textarea[name=' + fieldName + ']').trigger('change');
		},
		
		onCancelWpEditor: function () {
			// just hide editor 
			$('.epkb-editor-popup').removeClass( 'epkb-editor-popup--active' );
		},
		
		/*******  ZONE CHANGE   ********/

		onZoneClick: function( event ){
			event.stopPropagation();
			$( 'body' ).trigger( 'click.wpcolorpicker' );
			
			let zone = $(this).data('zone'),
				app = event.data;
			
			app.showMenuButtons();
			
			// Remove all active zone classes
			app.iframe.find( '.epkb-editor-zone' ).removeClass( 'epkb-editor-zone--active' );

			// Add Class to clicked on Zone
			$( this ).addClass( 'epkb-editor-zone--active' );
			$( this ).find( '.epkb-editor-zone.hover' ).removeClass('hover');
			app.iframe.find( '.epkb-editor-zone__tabs').remove();
			
			// clear modal 
			app.clearModal();
			
			// update header
			$('.epkb-editor-header__inner__title').html(app.currentSettings[zone].title);
			
			if (typeof app.currentSettings[zone] == 'undefined') {
				$('.epkb-editor-header__inner__title').html( epkb_editor.epkb_name );
				$('.epkb-editor-settings__panel-content-container').append( EPKBEditorTemplates.notice( { 'title' : epkb_editor.no_settings } ) );
				app.activeZone = '';
				
				return true;
			}
			
			// set active zone 
			app.activeZone = zone;
			
			// add tabs 
			let tabs = [];
			
			for ( let fieldName in app.currentSettings[zone].settings ) {
				
				if ( ~tabs.indexOf( app.currentSettings[zone].settings[fieldName].editor_tab ) ) {
					continue;
				}
				
				tabs.push( app.currentSettings[zone].settings[fieldName].editor_tab );
			}
			
			// add tabs buttons on the top
			$('.epkb-editor-settings__panel-navigation-container').append( EPKBEditorTemplates.modalTabsHeader( tabs ) );
			
			for ( let fieldName in app.currentSettings[zone].settings ) {
				app.addField( fieldName, app.currentSettings[zone].settings[fieldName] );
			}
			
			// change active tab if needed, content by default
			if ( ! $('#epkb-editor-settings-panel-content').html() ) {
				$('#epkb-editor-settings-panel-content').removeClass('epkb-editor-settings__panel--active');
				$('#epkb-editor-settings-panel-' + tabs[0]).addClass('epkb-editor-settings__panel--active');	
			}
			
			app.addTabsToZone( $(this) );
			app.activateColorPickers();
			app.activateSliders();
			app.checkTogglers();
			app.showModal();
		},
		
		// add tabs to active zone 
		addTabsToZone: function( $el ) {
			
			this.iframe.find('.epkb-editor-zone__tabs').remove();
			
			if ( ! this.activeZone ) {
				return;
			}
			
			let activeZoneName = this.currentSettings[ this.activeZone ].zone_tab_title;
			
			if ( typeof activeZoneName == 'undefined' ) {
				activeZoneName = this.currentSettings[ this.activeZone ].title;
			}
			
			let activeZoneEl = $el;
			let tabHTML = `
				<div class="epkb-editor-zone__tabs">
					<div class="epkb-editor-zone__tab--active">${activeZoneName}</div>
			`;
			
			let parentZoneEl = activeZoneEl.parents( '.epkb-editor-zone' ).eq(0);
			
			if ( parentZoneEl.length && typeof this.currentSettings[ parentZoneEl.data('zone') ].parent_zone_tab_title !== 'undefined' ) {
				
				tabHTML += `<div class="epkb-editor-zone__tab--parent" data-zone="${parentZoneEl.data('zone')}">${this.currentSettings[ parentZoneEl.data('zone') ].parent_zone_tab_title}</div>`;
			}
			
			tabHTML += `</div>`;
			
			activeZoneEl.append( tabHTML );
		},
		
		toggleLinkedDimensions( event ) {
			if ( $(this).hasClass('epkb-editor-settings-control__input__linking--active') ) {
				$(this).removeClass('epkb-editor-settings-control__input__linking--active');
				return true;
			}
			
			let firstField = $(this).closest('.epkb-editor-settings-control__fields').find('input').first();
			$(this).addClass('epkb-editor-settings-control__input__linking--active');
			
			$(this).closest('.epkb-editor-settings-control__fields').find('input').val( firstField.val() );
			firstField.change();
		},
		
		updateStyles: function() {
			// clear old styles
			this.styles.html('');

			for ( let settingGroupSlug in this.currentSettings ) {
				for ( let fieldName in this.currentSettings[settingGroupSlug].settings ) {
					let field = this.currentSettings[settingGroupSlug].settings[fieldName];
					
					// check togglers 
					if ( typeof field.toggler == 'string' ) {
						let togglerOption = this.getOption(field.toggler);
						
						if ( togglerOption == 'off' ) {						
							continue;
						}
					} else if ( typeof field.toggler == 'object' ) {
						// object, for selects 
						let togglerState = true;
							
						for ( let togglerFieldName in field.toggler ) {
							let togglerOption = this.getOption(togglerFieldName);
								
							if ( togglerOption !== field.toggler[togglerFieldName] ) {
								togglerState = false;
							}
						}
							
						if ( ! togglerState ) {
							continue;
						}
					}
					
					let important = '!important';
					
					if ( typeof field.style_important !== 'undefined' && ! field.style_important ) {
						important = '';
					}
					
					// dimensions field
					if ( field.group_type == 'dimensions' ) {
						
						for ( let subfieldName in field.subfields ) {
							let subfield = field.subfields[subfieldName];
							
							if ( typeof subfield.styles != 'undefined' ) {
								for ( let selector in subfield.styles ) {
									this.styles.append(`
										${selector} {
											${subfield.styles[selector]}: ${subfield.value}${ this.getPostfix( subfield.postfix ) }${important};
										}
									`);
								}
							}
							
							if ( typeof subfield.target_selector == 'undefined' || typeof subfield.style_name == 'undefined' ) {
								continue;
							}
							
							this.styles.append(`
								${subfield.target_selector} {
									${subfield.style_name}: ${subfield.value}${ this.getPostfix( subfield.postfix ) }${important};
								}
							`);
						}
						
						continue;
					}
					
					// dimensions field
					if ( field.group_type == 'multiple' ) {

						if ( field.style_template == 'undefined' || field.target_selector == 'undefined' || field.style_name == 'undefined' ) {
							continue;
						}
						
						let cssRule = field.style_template;
						
						for ( let subfieldName in field.subfields ) {
							let subfield = field.subfields[subfieldName];
							
							cssRule = cssRule.replace( subfieldName, subfield.value + this.getPostfix( subfield.postfix ) );
						}
						
						this.styles.append(`
							${field.target_selector} {
								${field.style_name}: ${cssRule}${important};
							}
						`);
					}
					
					if ( typeof field.styles != 'undefined' ) {
						for ( let selector in field.styles ) {
							this.styles.append(`
								${selector} {
									${field.styles[selector]}: ${field.value}${ this.getPostfix( field.postfix ) }${important};
								}
							`);
						}
					}

					if ( typeof field.target_selector == 'undefined' || typeof field.style_name == 'undefined' ) {
						continue;
					}
					
					if ( field.target_selector ==  '#eckb-article-page-container-v2 #eckb-article-right-sidebar' ) {
					console.log(fieldName)
					console.log(field.target_selector);
					console.log(field.style_name);
					}
					this.styles.append(`
						${field.target_selector} {
							${field.style_name}: ${field.value}${ this.getPostfix( field.postfix ) }${important};
						}
					`);
				}
			}
			
			// check article columns 
			if ( epkb_editor.page_type == 'article-page' ) {
				
				let leftSidebar = this.currentSettings.left_sidebar.settings,
					rightSidebar = this.currentSettings.right_sidebar.settings,
					leftSidebarWidthDesktop = 0,
					rightSidebarWidthDesktop = 0,
					contentWidthDesktop;
				
				if ( this.isLeftPanelActive() ) {
					leftSidebarWidthDesktop = leftSidebar['article-left-sidebar-desktop-width-v2'].value ? leftSidebar['article-left-sidebar-desktop-width-v2'].value : 20;
				}
				
				if ( this.isRightPanelActive() ) {
					rightSidebarWidthDesktop = rightSidebar['article-right-sidebar-desktop-width-v2'].value ? rightSidebar['article-right-sidebar-desktop-width-v2'].value : 20;
				}
			
				contentWidthDesktop = 100 - leftSidebarWidthDesktop - rightSidebarWidthDesktop;
				
				// resave content width if it is not true 
				this.currentSettings.left_sidebar.settings['article-left-sidebar-desktop-width-v2'].value = leftSidebarWidthDesktop;
				this.currentSettings.right_sidebar.settings['article-right-sidebar-desktop-width-v2'].value = rightSidebarWidthDesktop;
				this.currentSettings.article_content.settings['article-content-desktop-width-v2'].value = contentWidthDesktop;
				
				this.styles.append(`
						#eckb-article-page-container-v2 #eckb-article-body {
							grid-template-columns: ${leftSidebarWidthDesktop}% ${contentWidthDesktop}% ${rightSidebarWidthDesktop}%!important;
						}
					`);
			}

		},

		updateAttributes: function() {

			for ( let settingGroupSlug in this.currentSettings ) {
				for ( let fieldName in this.currentSettings[settingGroupSlug].settings ) {
					let field = this.currentSettings[settingGroupSlug].settings[fieldName];

					if ( typeof field.target_attr == 'undefined' ) {
						continue;
					}
					
					// check togglers 
					if ( typeof field.toggler == 'string' ) {
						let togglerOption = this.getOption(field.toggler);
						
						if ( togglerOption == 'off' ) {						
							continue;
						}
					} else if ( typeof field.toggler == 'object' ) {
						// object, for selects 
						let togglerState = true;
							
						for ( let togglerFieldName in field.toggler ) {
							let togglerOption = this.getOption(togglerFieldName);
								
							if ( togglerOption !== field.toggler[togglerFieldName] ) {
								togglerState = false;
							}
						}
							
						if ( ! togglerState ) {
							continue;
						}
					}
					
					let attributes = field.target_attr.split('|');

					for ( let attribute of attributes ) {
						this.iframe.find(field.target_selector).prop( attribute, field.value );
					}

				}
			}
		},

		updateText: function() {

			for ( let settingGroupSlug in this.currentSettings ) {
				for ( let fieldName in this.currentSettings[settingGroupSlug].settings ) {
			
					let field = this.currentSettings[settingGroupSlug].settings[fieldName];
					
					// check togglers 
					if ( typeof field.toggler == 'string' ) {
						let togglerOption = this.getOption(field.toggler);
						
						if ( togglerOption == 'off' ) {						
							continue;
						}
					} else if ( typeof field.toggler == 'object' ) {
						// object, for selects 
						let togglerState = true;
							
						for ( let togglerFieldName in field.toggler ) {
							let togglerOption = this.getOption(togglerFieldName);
								
							if ( togglerOption !== field.toggler[togglerFieldName] ) {
								togglerState = false;
							}
						}
							
						if ( ! togglerState ) {
							continue;
						}
					}
					
					if ( typeof field.text != 'undefined' ) {
						this.iframe.find(field.target_selector).text( this.decodeHtml(field.value) );
					}

					if ( typeof field.html != 'undefined' ) {
						this.iframe.find(field.target_selector).html( field.value );
					}
				}
			}
		},
		
		checkTogglers: function() {
			// check active inputs and togglers in modal 
			if ( this.activeZone == '' || typeof this.currentSettings[this.activeZone] == 'undefined' ) {
				return;
			}
			
			for ( let fieldName in this.currentSettings[this.activeZone].settings ) {

				let field = this.currentSettings[this.activeZone].settings[fieldName];
				
				if ( typeof field.toggler == 'undefined' ) {
					continue;
				}

				let togglerState = false;

				if ( typeof field.toggler == 'string' ) {
					togglerState = ( this.getOption(field.toggler) == 'on' ); 
				}

				if ( typeof field.toggler == 'object' ) {
					// object, for selects 
					togglerState = true;
					for ( let togglerFieldName in field.toggler ) {
						let togglerOption = this.getOption(togglerFieldName);
						
						if ( togglerOption !== field.toggler[togglerFieldName] ) {
							togglerState = false;
						}
					}
					
				}
				
				if ( togglerState ) {
					$('[data-field='+fieldName+']').show();
				} else {
					$('[data-field='+fieldName+']').hide();
				}
			}
		},

		refreshIframe: function( app ) {
			
			app.showLoader();
			
			// add form to add POST parameters during reload the iframe
			if ( app.iframe.find('#epkb-settings-form').length == 0 ) {
				app.iframe.find('body').append(`
					<form id="epkb-settings-form" method="post">
						<input type="hidden" name="epkb-editor-settings">
						<input type="hidden" name="epkb-editor" value="1">
						<input type="hidden" name="epkb-editor-kb-id" value="${epkb_editor.epkb_editor_kb_id}">
					</form>
				`);
			}
			
			let allSettings = Object.assign( {}, app.currentSettings, app.currentEditorSettings );
			
			for ( let settingZone in allSettings ) {
				for ( let fieldName in allSettings[settingZone].settings ) {
					delete allSettings[settingZone].settings[fieldName].element_wrapper;
				}
			}
			
			delete allSettings.themes;

			app.iframe.find('#epkb-settings-form input[name=epkb-editor-settings]').val( JSON.stringify( allSettings ) );
			app.iframe.find('#epkb-settings-form').submit();
		},

		activateColorPickers: function() {
			if ( $('#epkb-editor').find('.epkb-editor-settings-control-type-color input').length ) {
				$('#epkb-editor').find('.epkb-editor-settings-control-type-color input').wpColorPicker({
					change: function( colorEvent, ui) {
						setTimeout( function() {
							$( colorEvent.target).trigger('change');
						}, 50);
					},
					// a callback to fire when the input is emptied or an invalid color
					clear: function( event, ui) {
						let input = $(event.target).closest('.epkb-editor-settings-control__input').find('.wp-picker-input-wrap label input[type=text]');
						
						if ( input.length < 1 ) {
							return;
						}
						
						if ( typeof input.data('default_color') == 'undefined' ) {
							return;
						}
						
						input.iris('color', input.data('default_color'));
						
					}
				});
			}
		},
		
		activateSliders: function() {
			$( ".epkb-editor-settings-control-type-number--slider .epkb-editor-settings-control__slider" ).each(function(){
				
				let input = $(this).closest('.epkb-editor-settings-control-container').find('input');
				let that = $(this);
				
				that.slider({
					max: parseFloat(input.prop('max')),
					min: parseFloat(input.prop('min')),
					value: parseFloat(input.val()),
					change: function( event, ui ){
						input.val( ui.value );
						input.trigger( 'change', [ 'updateSlider' ] );
					},
				});
				
				input.change(function( event, type ){
					if ( type == 'updateSlider' ) {
						return true;
					}
					
					that.slider( 'option', 'value', parseFloat($(this).val()) );
				});
			});
		},
		
		/*******  MODAL SHOW/HIDE   ********/
		toggleMode: function( event ){
			let app = event.data;
			
			if ( $('body').hasClass( 'epkb-edit-mode' ) ) {
				
				// remove preopen parameter and reload
				let rtn = location.href.split( '?' )[0],
					param,
					params_arr = [],
					queryString = ( location.href.indexOf( '?' ) !== -1 ) ? location.href.split( '?' )[1] : '';
					
				if ( queryString !== '' ) {
					params_arr = queryString.split( '&' );
					for ( var i = params_arr.length - 1; i >= 0; i -= 1 ) {
						param = params_arr[i].split( '=' )[0];
						if ( param === 'preopen' ) {
							params_arr.splice( i, 1 );
						}
					}
					rtn = rtn + '?' + params_arr.join( '&' );
				}

				if ( location.href == rtn ) {
					location.reload();
				} else {
					location.href = rtn;
				}
				
			} else {
				$('#wp-admin-bar-epkb-edit-mode-button a').text( epkb_editor.turned_on );
				$('body').addClass( 'epkb-edit-mode' );
				app.showModal();
			}
		},
		
		showModal: function(){
			$('body').addClass('epkb-editor--active');
			$('#epkb-editor-iframe').show();
		},

		hideModal: function(){
			$('body').removeClass('epkb-editor--active');
		},
		
		/*******  MESSAGES/LOADER SETTINGS   ********/
		// TODO 
		showLoader: function(){
			$('.epkb-frontend-loader').addClass('epkb-frontend-loader--active'); 
		},
		
		removeLoader: function(){
			setTimeout( function() {
				$('.epkb-frontend-loader').removeClass('epkb-frontend-loader--active'); 
			}, 200);
		},
		
		showMessage: function( data = {} ) {
			
			let message = '';
			
			if ( typeof data.html == 'undefined' ) {
				message = EPKBEditorTemplates.message( data );
			} else {
				message = data.html;
			}
			
			$('.eckb-bottom-notice-message').remove();
			$('body').append( message );
			
			setTimeout(function(){
				$('.eckb-bottom-notice-message').remove();
			}, 5000);
		},

		/**** Decode HTML ******/
		decodeHtml: function( html )  {
			var txt = document.createElement("textarea");
			txt.innerHTML = html;
			return txt.value;
		},

		/*******  SAVE SETTINGS   ********/
		
		saveSettings: function( event ) {
			
			event.preventDefault();
			
			let app = event.data;
			let config = {};
			let allSettings = Object.assign( {}, app.currentSettings, app.currentEditorSettings );
			
			delete allSettings.themes;
			
			for ( let zone in allSettings ) {
				let settings = allSettings[zone].settings;
				
				for ( let fieldName in settings ) {
					let field = settings[fieldName];
					
					if ( typeof field.group_type == 'undefined' ) {
						// simple field 
						config[fieldName] = field.value;
					} else {
						// group type 
						for ( let subfieldName in field.subfields ) {
							config[subfieldName] = field.subfields[subfieldName].value;
						}
					}
				}
			}
			
			let postData = {
				action: 'eckb_apply_editor_changes',
				_wpnonce_apply_editor_changes: epkb_editor._wpnonce_apply_editor_changes,
				kb_config: config,
				epkb_editor_kb_id: epkb_editor.epkb_editor_kb_id,
				page_type: epkb_editor.page_type
			};
			
			$.ajax({
				type: 'POST',
				dataType: 'json',
				data: postData,
				url: epkb_editor.ajaxurl,
				beforeSend: function (xhr) {
					app.showLoader();
				}
			}).done(function (response) {
				
				if ( typeof response.error == 'undefined' ) {
					// success 
					app.showMessage({
						text: response.message
					});
					
				} else {
					app.showMessage({
						html: response.message,
						type: 'error'
					});
				}

			}).always(function () {
				app.removeLoader();
			});
		},
	
		/******* SETTINGS ********/
		
		showMenu: function ( event ) {
			let app = event.data;
			event.stopPropagation();
			$( 'body' ).trigger( 'click.wpcolorpicker' );
			
			// clear modal 
			app.clearModal();
			
			// update header
			$('.epkb-editor-header__inner__title').html( epkb_editor.epkb_name );
			
			// hide tabs 
			$( '.epkb-editor-settings__panel-navigation-container, .epkb-editor-settings__panel-content-container' ).hide();
			
			// show menu 
			$( '.epkb-editor-settings-menu-container' ).show();
			
			app.showBackButtons();
			
			// change active zone 
			app.activeZone = 'menu';
		},
		
		showSettings: function( event ) {
			
			event.stopPropagation();
			
			$( 'body' ).trigger( 'click.wpcolorpicker' );
			
			let app = event.data;

			// clear modal 
			app.clearModal();
			
			// update header
			$('.epkb-editor-header__inner__title').html( epkb_editor.epkb_name );
			
			// add tabs buttons on the top
			$('.epkb-editor-settings__panel-navigation-container').append( EPKBEditorTemplates.modalTabsHeaderSettings() );
			
			// make active global tab 
			$('#epkb-editor-settings-panel-content').removeClass('epkb-editor-settings__panel--active');
			$('#epkb-editor-settings-panel-global').addClass('epkb-editor-settings__panel--active');
			
			// add global tab settings 
			$('#epkb-editor-settings-panel-global').append( epkb_editor.settings_html );
			
			// set active to template and layouts
			$('.epkb-editor-settings-control-image-select input[value='+epkb_editor_settings.settings_zone.settings.templates_for_kb.value+']').prop('checked', 'checked');
			$('.epkb-editor-settings-control-image-select input[value='+epkb_editor_settings.settings_zone.settings.kb_main_page_layout.value+']').prop('checked', 'checked');
			
			$('.epkb-editor-settings-panel-global__links-container').append(
				( epkb_editor.page_type == 'main-page' ? EPKBEditorTemplates.menuLinks( '#', 'templates', 'sliders', 'Theme' ) : '' ) +
				( epkb_editor.page_type == 'main-page' ? EPKBEditorTemplates.menuLinks( '#', 'layouts', 'sitemap', 'Layouts' ) : '' ) +
				EPKBEditorTemplates.menuLinks( epkb_editor.kb_url+'&page=epkb-kb-configuration&wizard-global', 'urls', 'globe', 'URLs and Slug' ) +
				EPKBEditorTemplates.menuLinks( epkb_editor.kb_url+'&page=epkb-kb-configuration&wizard-ordering', 'ordering', 'object-group', 'Order Categories and Articles' )
			);
			
			// add settings on the global tab 
			for ( let fieldName in app.currentEditorSettings.settings_zone.settings ) {
				let field = app.currentEditorSettings.settings_zone.settings[fieldName];
				field.element_wrapper = $('#epkb-editor-settings-panel-global');
				app.addField( fieldName, field );
			}

			// fill disabled zone 
			for ( let zoneName in app.currentSettings ) {
				if ( typeof app.currentSettings[zoneName].disabled_settings == 'undefined' ) {
					continue;
				}
				
				let zone = app.currentSettings[zoneName], 
				    is_on = false;
					
				for ( let fieldName in zone.disabled_settings ) {
					let optionValue = app.getOption( fieldName );
					let conditionValue = zone.disabled_settings[fieldName];
					
					if ( optionValue != conditionValue ) {
						is_on = true;
					}						
				}
				
				// dont show activated zones
				if ( is_on ) {
					continue;
				}
				
				app.addCheckbox( zoneName, {
					label: zone.title,
					value: is_on ? 'on' : 'off',
					editor_tab: 'hidden'
				});
			}
			
			// uncomment if we will have these operations in config 
			//app.activateColorPickers();
			//app.activateSliders();
			
			app.showBackButtons();
			
			// change active zone 
			app.activeZone = 'settings';
		},
		
		/******* HELPERS ********/
		
		// get value of the option from current settings 
		getOption: function( optionName ) {
			
			for ( let settingGroupSlug in this.currentSettings ) {
				
				for ( let fieldName in this.currentSettings[settingGroupSlug].settings ) {
					
					let field = this.currentSettings[settingGroupSlug].settings[fieldName];
					
					if ( optionName == fieldName ) {
						
						return field.value;
					}
					
					if ( field.group_type == 'dimensions' || field.group_type == 'multiple' ) {
						
						if ( typeof field.subfields[optionName] == 'undefined' ) {
							continue;
						}
						
						return field.subfields[optionName].value;
						
					}
				}
			}
			
			return false;
		},
		
		// update value of the option in current settings 
		updateOption: function( optionName, newVal ) {
			
			for ( let settingGroupSlug in this.currentSettings ) {
				
				for ( let fieldName in this.currentSettings[settingGroupSlug].settings ) {
					
					let field = this.currentSettings[settingGroupSlug].settings[fieldName];
					
					if ( optionName == fieldName ) {
						
						this.currentSettings[settingGroupSlug].settings[fieldName].value = newVal;
					}
					
					if ( field.group_type == 'dimensions' || field.group_type == 'multiple' ) {
						
						if ( typeof field.subfields[optionName] == 'undefined' ) {
							continue;
						}
						
						this.currentSettings[settingGroupSlug].settings[fieldName].subfields[optionName].value = newVal;
					}
				}
			}
		},
		
		// Work for select and checkbox: return array of the possible settings 
		getOptionsList: function(optionName ) {
			
			let optionsList = [];
			
			for ( let settingGroupSlug in this.currentSettings ) {
				
				for ( let fieldName in this.currentSettings[settingGroupSlug].settings ) {
					
					let field = this.currentSettings[settingGroupSlug].settings[fieldName];
					
					if ( optionName == fieldName && field.type == 'checkbox' ) {
						return [ 'on', 'off' ];
					}
					
					if ( optionName == fieldName && field.type == 'select' ) {
						return Object.keys( field.options );
					}
					
					if ( field.group_type == 'multiple' ) {
						if ( typeof field.subfields[optionName] == 'undefined' ) {
							continue;
						}
						
						if ( field.subfields[optionName].type == 'checkbox' ) {
							return [ 'on', 'off' ];
						}
						
						if ( field.subfields[optionName].type == 'select' ) {
							return Object.keys( field.subfields[optionName].options );
						}
					}
				}
			}
			
			return optionsList;
		},
		
		isLeftPanelActive: function() {
			return ( this.iframe.find('#eckb-article-left-sidebar div').length && this.iframe.find('#eckb-article-left-sidebar').width() ) || ( this.getOption( 'article-left-sidebar-toggle' ) == 'on' );
		},
		
		isRightPanelActive: function() {
			return (  this.iframe.find('#eckb-article-right-sidebar div').length && this.iframe.find('#eckb-article-right-sidebar').width() ) || ( this.getOption( 'article-right-sidebar-toggle' ) == 'on' );
		},
		
		getPostfix: function ( postfix ) {
			
			if ( typeof postfix == 'undefined' || postfix == '' ) {
				return '';
			}
			
			// check if we have a field with postfix like a name 
			let postfixField = this.getOption( postfix );
			
			if ( false === postfixField ) {
				return postfix;
			}
			
			return postfixField;
		}
	};

	window.EPKBEditorTemplates = {
		
		divider: function () {
			return `<div class="epkb-editor-settings-separator"></div>`;
		},
		
		select: function ( data ) {
			
			data = Object.assign( {
				name: '',
				label: '',
				value: '',
				style: '',
				separator_above: '',
				description: '',
			}, data );

			let html = '';
			if ( typeof data.separator_above !== 'undefined' ) {
				if( data.separator_above === 'yes' ){
					html += ` <div class="epkb-editor-settings-control-separator"></div>`;
				}
			}

			if ( typeof data.style == 'undefined' ) {
				data.style = 'full'
			}
			
			html += `
				<div class="epkb-editor-settings-control-container epkb-editor-settings-control-type-select epkb-editor-settings-control-type-select--${data.style}" data-field="${data.name}">
					<div class="epkb-editor-settings-control__field">
						<label class="epkb-editor-settings-control__title">${data.label}</label>
						<div class="epkb-editor-settings-control__input">
							<select name="${data.name}" value="${data.value}">
			`;

			for ( let optionName in data.options ) {
				html += `
								<option value="${optionName}" ${ ( data.value == optionName ) ? 'selected="selected"' : '' }>${data.options[optionName]}</option>
				`;
			}

			html += `
							</select>
						</div>
					</div>
					${ data.description ? '<div class="epkb-editor-settings-control__description">' + data.description + '</div>' : '' }
				</div>
			`;

			return html;
		},
		
		units: function ( data ) {
			
			data = Object.assign( {
				name: '',
				value: '',
				options: []
			}, data );

			let html = '';
			
			html += `
				<div class="epkb-editor-settings-control-container epkb-editor-settings-control-type-units" data-field="${data.name}">
					<div class="epkb-editor-settings-control__field">
						<div class="epkb-editor-settings-control__input">
			`;

			for ( let optionName in data.options ) {
				html += `
							<label>
								<input type="radio" name="${data.name}" value="${optionName}" ${ ( data.value == optionName ) ? 'checked="checked"' : '' }>
								<span>${data.options[optionName]}</span>
							</label>
				`;
			}

			html += `
						</div>
					</div>
				</div>
			`;

			return html;
		},
		
		checkbox: function ( data ) {
			data = Object.assign( {
				name: '',
				label: '',
				value: '',
				separator_above: '',
				description: ''
			}, data );


			let html = '';
			if ( typeof data.separator_above !== 'undefined' ) {
				if( data.separator_above === 'yes' ){
					html += ` <div class="epkb-editor-settings-control-separator"></div>`;
				}
			}

			html += `
				<div class="epkb-editor-settings-control-container epkb-editor-settings-control-type-toggle" data-field="${data.name}">
					<div class="epkb-editor-settings-control__field">
						<label class="epkb-editor-settings-control__title">${data.label}</label>
						<div class="epkb-editor-settings-control__input">
							<label class="epkb-editor-settings-control-toggle">
								<input type="checkbox" class="epkb-editor-settings-control__input__toggle" value="yes" name="${data.name}" ${ ( data.value == 'on' ) ? 'checked="checked"' : '' }>
								<span class="epkb-editor-settings-control__input__label" data-on="${epkb_editor.checkbox_on}" data-off="${epkb_editor.checkbox_off}"></span>
								<span class="epkb-editor-settings-control__input__handle"></span>
							</label>
						</div>
					</div>
					${ data.description ? '<div class="epkb-editor-settings-control__description">' + data.description + '</div>' : '' }
				</div>
			`;
			return html;

		},
		
		text: function ( data ) {
			data = Object.assign( {
				name: '',
				label: '',
				value: '',
				style: '',
				description: '',
				html: false
			}, data );

			if ( typeof data.style == 'undefined' ) {
				data.style = 'full'
			}
			
			if ( data.html ) {
				return `
					<div class="epkb-editor-settings-control-container epkb-editor-settings-control-type-text epkb-control-text--${data.style}" data-field="${data.name}">
						<div class="epkb-editor-settings-control__field">
							<label class="epkb-editor-settings-control__title">${data.label}</label>
							<div class="epkb-editor-settings-control__input">
								<textarea name="${data.name}">${data.value}</textarea>
							</div>
						</div>
						${ data.description ? '<div class="epkb-editor-settings-control__description">' + data.description + '</div>' : '' }
					</div>
				`;
				
			} 
			
			return `
				<div class="epkb-editor-settings-control-container epkb-editor-settings-control-type-text epkb-control-text--${data.style}" data-field="${data.name}">
					<div class="epkb-editor-settings-control__field">
						<label class="epkb-editor-settings-control__title">${data.label}</label>
						<div class="epkb-editor-settings-control__input">
							<input type="text" name="${data.name}" value="${data.value}" >
						</div>
					</div>
					${ data.description ? '<div class="epkb-editor-settings-control__description">' + data.description + '</div>' : '' }
				</div>
			`;

		},
		
		dimensions: function ( data ) {
			data = Object.assign( {
				label: '',
				units: '',
				name: '',
				description: '',
				subfields: {},
			}, data );
			
			if ( Object.keys(data.subfields).length < 1 ) {
				return this.notice( { 'title' : epkb_editor.wrong_dimensions } )
			}

			let dimCount = Object.keys(data.subfields).length;
			
			let html = `
				<div class="epkb-editor-settings-control-container epkb-editor-settings-control-type-dimensions epkb-editor-settings-control-type-dimensions--count-${dimCount}" data-field="${data.name}">
					<div class="epkb-editor-settings-control__header">
						<div class="epkb-editor-settings-control__header__label">${data.label}</div>
						<div class="epkb-editor-settings-control__header__units">${data.units}</div>
					</div>
					<div class="epkb-editor-settings-control__fields">
			`;
			
			// will be linked if all values are the same 
			let linked = true;
			let linkedValue;
			
			for ( let fieldName in data.subfields ) {
				if ( typeof linkedValue == 'undefined' ) {
					linkedValue = data.subfields[fieldName].value;
				}
				
				if ( linkedValue !== data.subfields[fieldName].value ) {
					linked = false;
				}
			}
			
			for ( let fieldName in data.subfields ) {
				html += `
					<div class="epkb-editor-settings-control__input ${ linked ? 'epkb-editor-settings-control__input__linking--active' : ''}">
						<input type="number" name="${fieldName}" value="${data.subfields[fieldName].value}" data-parentGroup="${data.name}">
						<span class="epkb-editor-settings-control__input__label">${data.subfields[fieldName].label}</span>
					</div>
				`;
			}
			
			html += ` 
						<div class="epkb-editor-settings-control__input">
							<button class="epkb-editor-settings-control__input__linking"><span class="epkbfa epkbfa-link" aria-hidden="true"></span></button>
						</div>
					</div>
					${ data.description ? '<div class="epkb-editor-settings-control__description">' + data.description + '</div>' : '' }
				</div>
			`;
			
			return html;
		},
		
		number: function ( data ) {
			data = Object.assign( {
				name: '',
				label: '',
				value: '',
				style: '',
				min: 0,
				max: 100,
				separator_above: '',
				description: ''
			}, data );

			let html = '';

			if ( typeof data.separator_above !== 'undefined' ) {
				if( data.separator_above === 'yes' ){
					html += ` <div class="epkb-editor-settings-control-separator"></div>`;
				}
			}
			if ( typeof data.style == 'undefined' ) {
				data.style = 'default'
			}
			html += `
				<div class="epkb-editor-settings-control-container epkb-editor-settings-control-type-number epkb-editor-settings-control-type-number--${data.style}" data-field="${data.name}">
					<div class="epkb-editor-settings-control__field">
						<label class="epkb-editor-settings-control__title">${data.label}</label>
						<div class="epkb-editor-settings-control__input">
							<input type="number" name="${data.name}" value="${data.value}" min="${data.min}" max="${data.max}">
						</div>
					</div>
					<div class="epkb-editor-settings-control__slider"></div>
					${ data.description ? '<div class="epkb-editor-settings-control__description">' + data.description + '</div>' : '' }
				</div>
			`;
			return html;
		},
		
		header: function ( data ) {
			
			data = Object.assign( { content: '', name: '' }, data );
			
			return `
				<div class="epkb-editor-settings-control-container epkb-editor-settings-control-type-header" data-field="${data.name}">
					${data.content}
				</div>
			`;
		},

		header_desc: function ( data ) {

			data = Object.assign( {
				title: '',
				desc: ''
			}, data );

			return `
				<div class="epkb-editor-settings-control-container epkb-editor-settings-control-type-header-desc">
					<div class="epkb-editor-settings-control-type-header-desc__title">${data.title}</div>
					<div class="epkb-editor-settings-control-type-header-desc__desc">${data.desc}</div>
				</div>
			`;
		},

		colorPicker: function ( data ) {
			// check defaults 
			data = Object.assign( {
				name: '',
				label: '',
				value: '',
				separator_above: '',
				description: ''
			}, data );

			let html = '';

			if ( typeof data.separator_above !== 'undefined' ) {
				if( data.separator_above === 'yes' ){
					html += ` <div class="epkb-editor-settings-control-separator"></div>`;
				}
			}

			html += `
			
				<div class="epkb-editor-settings-control-container epkb-editor-settings-control-type-color" data-field="${data.name}">
					<div class="epkb-editor-settings-control__field">
						<label class="epkb-editor-settings-control__title">${data.label}</label>
						<div class="epkb-editor-settings-control__input">
							<input type="text" name="${data.name}" value="${data.value}" data-default_color="${data.value}">
						</div>
					</div>
					${ data.description ? '<div class="epkb-editor-settings-control__description">' + data.description + '</div>' : '' }
				</div>
			`;


			return html;
		},
		
		wpEditor: function ( data ) {
			data = Object.assign( {
				name: '',
				label: '',
				value: '',
				description: ''
			}, data );
			
			return `
				<div class="epkb-editor-settings-control-container epkb-editor-settings-control-type-wp-editor" data-field="${data.name}">
					<div class="epkb-editor-settings-control__field">
						<label class="epkb-editor-settings-control__title">${data.label}</label>
						<div class="epkb-editor-settings-control__input">
							<textarea style="display: none;" name="${data.name}">
								${data.value}
							</textarea>
							<button class="epkb-editor-settings-wpeditor_button">${epkb_editor.edit_button}</button>
						</div>
					</div>
					${ data.description ? '<div class="epkb-editor-settings-control__description">' + data.description + '</div>' : '' }
				</div>
			`;
		},

		modalHeader: function () {
			return `
				<!-- Header Container -->
					<header class="epkb-editor-header-container">
						<div class="epkb-editor-header__inner">
							<div class="epkb-editor-header__inner__menu-btn"><span class="epkbfa epkbfa-bars"></span></div>
							<div class="epkb-editor-header__inner__back-btn"><span class="epkbfa epkbfa-chevron-left"></span></div>
							<div class="epkb-editor-header__inner__title">${epkb_editor.epkb_name}</div>
							<div class="epkb-editor-header__inner__config"><span class="epkbfa epkbfa-cog"></span></div>
							<div class="epkb-editor-header__inner__close-btn"><span class="epkbfa epkbfa-times"></span></div>
						</div>
					</header>
				<!-- /Header Container -->`;
		},
		
		modalTabsHeader: function( data = [] ) {
		
			if ( data.length == 0 ) {
				return '';
			}
			
			let tabs = '';
			let firstTab = true;
			let tabClass;
			
			if ( ~data.indexOf( 'content' ) && firstTab ) {
				tabClass = 'epkb-editor-settings__panel-navigation__tab--active';
				firstTab = false;
			} else if ( ! ~data.indexOf( 'content' ) ) {
				tabClass = 'epkb-editor-settings__panel-navigation__tab--disabled';
			} else {
				tabClass = '';
			}
			
			tabs += `
				<div id="epkb-editor-settings-tab-content"  class="epkb-editor-settings__panel-navigation__tab  ${ tabClass }" data-target="content">
					<span class="epkb-editor-settings__panel-navigation__tab__icon"><span class="epkbfa epkbfa-pencil"></span></span>
					<span class="epkb-editor-settings__panel-navigation__tab__title">${epkb_editor.tab_content}</span>
				</div>`;
			
			if ( ~data.indexOf( 'style' ) && firstTab ) {
				tabClass = 'epkb-editor-settings__panel-navigation__tab--active';
				firstTab = false;
			} else if ( ! ~data.indexOf( 'style' ) ) {
				tabClass = 'epkb-editor-settings__panel-navigation__tab--disabled';
			} else {
				tabClass = '';
			}

			tabs += `
				<div id="epkb-editor-settings-tab-style"  class="epkb-editor-settings__panel-navigation__tab ${ tabClass }" data-target="style">
					<span class="epkb-editor-settings__panel-navigation__tab__icon"><span class="epkbfa epkbfa-adjust"></span></span>						
          <span class="epkb-editor-settings__panel-navigation__tab__title">${epkb_editor.tab_style}</span>
				</div>
			`;
			
			if ( ~data.indexOf( 'features' ) && firstTab ) {
				tabClass = 'epkb-editor-settings__panel-navigation__tab--active';
				firstTab = false;
			} else if ( ! ~data.indexOf( 'features' ) ) {
				tabClass = 'epkb-editor-settings__panel-navigation__tab--disabled';
			} else {
				tabClass = '';
			}
			
			tabs += `
				<div id="epkb-editor-settings-tab-features"  class="epkb-editor-settings__panel-navigation__tab ${ tabClass }" data-target="features">
					<span class="epkb-editor-settings__panel-navigation__tab__icon"><span class="epkbfa epkbfa-puzzle-piece"></span></span>
					<span class="epkb-editor-settings__panel-navigation__tab__title">${epkb_editor.tab_features}</span>
				</div>
			`;
				
			
			if ( ~data.indexOf( 'advanced' ) && firstTab ) {
				tabClass = 'epkb-editor-settings__panel-navigation__tab--active';
				firstTab = false;
			} else if ( ! ~data.indexOf( 'advanced' ) ) {
				tabClass = 'epkb-editor-settings__panel-navigation__tab--disabled';
			} else {
				tabClass = '';
			}
			
			tabs += `
				<div id="epkb-editor-settings-tab-advanced"  class="epkb-editor-settings__panel-navigation__tab ${ tabClass }" data-target="advanced">
					<span class="epkb-editor-settings__panel-navigation__tab__icon"><span class="epkbfa epkbfa-cogs"></span></span>
					<span class="epkb-editor-settings__panel-navigation__tab__title">${epkb_editor.tab_advanced}</span>
				</div>`;
			
			return tabs;
		},
		
		modalTabsHeaderSettings: function( ) {

			return `
				<div id="epkb-editor-settings-tab-global"  class="epkb-editor-settings__panel-navigation__tab epkb-editor-settings__panel-navigation__tab--active" data-target="global">
					<span class="epkb-editor-settings__panel-navigation__tab__icon"><span class="epkbfa epkbfa-globe"></span></span>
					<span class="epkb-editor-settings__panel-navigation__tab__title">${epkb_editor.tab_global}</span>
				</div>
				<div id="epkb-editor-settings-tab-hidden"  class="epkb-editor-settings__panel-navigation__tab" data-target="hidden">
					<span class="epkb-editor-settings__panel-navigation__tab__icon"><span class="epkbfa epkbfa-eye-slash"></span></span>						
          <span class="epkb-editor-settings__panel-navigation__tab__title">${epkb_editor.tab_hidden}</span>
				</div>
			`;
		},
		
		modalTabsBody: function( data = [] ) {
		
			return `
				<div id="epkb-editor-settings-panel-content" class="epkb-editor-settings__panel epkb-editor-settings__panel--active" data-panel="content"></div>
				<div id="epkb-editor-settings-panel-style" class="epkb-editor-settings__panel" data-panel="style"></div>
				<div id="epkb-editor-settings-panel-features" class="epkb-editor-settings__panel" data-panel="features"></div>
				<div id="epkb-editor-settings-panel-advanced" class="epkb-editor-settings__panel" data-panel="advanced"></div>
				<div id="epkb-editor-settings-panel-global" class="epkb-editor-settings__panel" data-panel="global">
					<div class="epkb-editor-settings-panel-global__links-container"></div>
					<div class="epkb-editor-settings-panel-global__settings-container"></div>
				
				</div>
				<div id="epkb-editor-settings-panel-hidden" class="epkb-editor-settings__panel" data-panel="hidden"></div>
				<div class="epkb-editor-settings__help">
					<a href="https://www.echoknowledgebase.com/front-end-editor-support-and-questions/" target="_blank">
						<span class="">Need Help</span>
						<span class="epkbfa epkbfa-question-circle-o"></span>
					</a>
				</div>
			`;
		},

		// data: { tabs: [] }
		modalSettingsContainer: function( data = {} ) {
			
			let container = '';
			
			container += `
				<!-- Settings Container -->
				<main class="epkb-editor-settings-container">
					<div class="epkb-editor-settings__inner">
			`;
			
			if ( typeof data.tabs !== 'undefined' ) {
				container += `
					<!-- Panel Navigation -->
					<div class="epkb-editor-settings__panel-navigation-container">
						${this.modalTabsHeader( data.tabs )}
					</div>
					<!-- /Panel Navigation -->
					<!-- Panels Content -->
					<div class="epkb-editor-settings__panel-content-container">
						${this.modalTabsBody()}
						
					</div>
					<!-- /Panels Content -->
					
					${epkb_editor.menu_links_html}
					
				`;
			}
			
			container += `
					</div>
				</main>
				<!-- /Settings Container -->
			`;

			return container;
		},
	
		modalFooter: function () {
			return `
				<footer class="epkb-editor-footer-container">
					<nav class="epkb-editor-footer-nav">
						<div class="epkb-editor-footer-nav__item epkb-editor-footer-nav__item-icon"><span class="epkbfa epkbfa-reply-all"></span></div>
						<div class="epkb-editor-footer-nav__item epkb-editor-footer-nav__item-icon"><span class="epkbfa epkbfa-eye"></span></div>
						<div class="epkb-editor-footer-nav__item epkb-editor-footer-nav__item-btn">
							<button id="epkb-editor-exit" class="epkb-editor-btn epkb-editor-exit">${epkb_editor.exit_button}</button>
						</div>
						<div class="epkb-editor-footer-nav__item epkb-editor-footer-nav__item-btn">
							<button id="epkb-editor-save" class="epkb-editor-btn epkb-editor-save">${epkb_editor.save_button}</button>
						</div>
					</nav>
				</footer>
				
				<div id="epkb-editor-close">&times;</div>
			`;
		},
		
		modalWindow: function() {
			return `
				<div id="epkb-editor" class="epkb-editor-container">
					${ this.modalHeader() }
					${ this.modalSettingsContainer( { tabs : [] } ) }
					${ this.modalFooter() }
				</div>
			`;
		},
		
		// data = {  icon: '', title: '', message: '', style: '' }
		notice: function ( data = {} ) {

			// check defaults 
			data = Object.assign( {
				icon: 'exclamation-triangle', // https://fontawesome.com/icons/
				title: '',
				message: '',
				style: 'default'
			}, data );
			
			return `
				<div class="epkb-editor-settings-control-container epkb-editor-settings-control-type-notice epkb-editor-settings-control-type-notice--${data.style}">
					<div class="epkb-editor-notice__icon"><span class="epkbfa epkbfa-${data.icon}"></span></div>
					<div class="epkb-editor-notice__body">
						<div class="epkb-editor-notice__title">${data.title}</div>
						<div class="epkb-editor-notice__message">${data.message}</div>
					</div>
				</div>
			`;
		},

		
		// data = { title: '', text: ''}
		message: function( data = {} ) {
			
			if ( typeof data.title == 'undefined' ) {
				data.title = '';
			} else {
				data.title = '<h4>' + data.title + '</h4>';
			}
			
			if ( typeof data.text == 'undefined' ) {
				data.text = '';
			}
			
			if ( typeof data.type == 'undefined' ) {
				data.type = 'success';
			}
			
			return `
				<div class="eckb-bottom-notice-message">
					<div class="contents">
						<span class="${data.type}">
							${data.title}
							<p>${data.text}</p>
						</span>
					</div>
					<div class='epkb-close-notice epkbfa epkbfa-window-close'></div>
				</div>
			`;
		},
	
		menuLinks: function( url, dataName, icon, title  ) {

			return `
				<a href="${url}" data-name="${dataName}" class="epkb-editor-settings-menu__group-item-container" target="_blank">
					<div class="epkb-editor-settings-menu__group-item__icon epkbfa epkbfa-${icon}"></div>
					<div class="epkb-editor-settings-menu__group-item__title">${title}</div>
				</a>
			`;
		},	
	};
	
	if ( epkb_editor.preopen != 'undefined' && epkb_editor.preopen ) {	
		// pre-open settings 
		EPKBEditor.init();
	} else {
		// trigger Editor to open
		$('#wp-admin-bar-epkb-edit-mode-button').one('click', function(){
			EPKBEditor.init();
		});
	}
});

