<?php

/*
Plugin Name: EU Cookiewet Plugin (Nederlands)
Plugin URI: http://github.com/mijndert
Description: Een simpele plugin voor WordPress om je website te laten voldoen aan de cookiewet. De plugin zal een balk bovenaan de website plaatsen om de gebruiker laten weten dat er cookies gebruikt worden. Ook wordt er een pagina aangemaakt welke linkt naar een zelf in te vullen cookie policy.
Author: Mijndert Stuij
Version: 1.0
Author URI: http://mijndertstuij.nl/
*/

/**
* EU Cookiewet Plugin Class
*/
class EUCookieWet
{
	/**
	 * adds the cookiewet menu item into the settings menu
	 *
	 * @return void
	 */
	public static function on_admin_menu()
	{
		add_options_page('EU Cookiewet', 'EU Cookiewet', 'manage_options', 'eu_cookiewet_plugin', array('EUCookieWet', 'eu_cookiewet_opties_pagina'));
	}

	/**
	 * registers options and setting fields
	 *
	 * @return void
	 */
	public static function on_admin_init()
	{
		register_setting('eu_cookiewet_opties', array('EUCookieWet', 'eu_cookiewet_opties'), 'eu_cookiewet_opties_validate');
		add_settings_section('eu_cookie_main', '', array('EUCookieWet', 'eu_cookie_section_text'), 'eu_cookie', 'eu_cookie_main' );
		add_settings_field('eu_cookie_text', 'Notificatie', array('EUCookieWet', 'eu_cookie_text_settings'), 'eu_cookie', 'eu_cookie_main' );
		add_settings_field('eu_cookie_accept', 'Knop voor acceptatie', array('EUCookieWet', 'eu_cookie_accept_settings'), 'eu_cookie', 'eu_cookie_main');
		add_settings_field('eu_cookie_more', 'Meer informatie link', array('EUCookieWet', 'eu_cookie_more_settings'), 'eu_cookie', 'eu_cookie_main');
	}

	/**
	 * prints some text used for the settings page
	 *
	 * @return void
	 */
	public static function eu_cookie_section_text()
	{
		echo '<p>U kunt deze instellingen gebruiken om de tekst van de plugin op uw website aan te passen.</p><p>We zouden willen aanraden deze tekst kort maar duidelijk te formuleren.</p>';
	}

	/**
	 * handles the 'text' field
	 *
	 * @return void
	 */
	public static function eu_cookie_text_settings()
	{
		$options = get_option('eu_cookiewet_opties');
		$value   = htmlentities ($options['eu_cookie_text_settings'], ENT_QUOTES);

		if (!$value)
		{
			$value = 'Deze website maakt gebruik van cookies voor het verbeteren van de gebruikerservaring';
		}

		echo '<input id="eu_cookie_text_settings" name="eu_cookiewet_opties[eu_cookie_text_settings]" size="50" type="text" value="'.htmlspecialchars($value).'" />';
	}

	/**
	 * handles the 'accept' field
	 *
	 * @return void
	 */
	public static function eu_cookie_accept_settings()
	{
		$options = get_option('eu_cookiewet_opties');
		$value   = htmlentities ( $options['eu_cookie_accept_settings'], ENT_QUOTES );

		if ( !$value )
		{
			$value = 'Cookies toestaan';
		}

		echo "<input id='eu_cookie_accept_settings' name='eu_cookiewet_opties[eu_cookie_accept_settings]' size='50' type='text' value='{$value}' />";
	}

	/**
	 * handles the 'more' field
	 *
	 * @return void
	 */
	public static function eu_cookie_more_settings()
	{
		$options = get_option('eu_cookiewet_opties');
		$value   = htmlentities ( $options['eu_cookie_more_settings'], ENT_QUOTES );
		if ( !$value )
		{
			$value = 'Meer informatie over de cookiewetgeving';
		}

		echo "<input id='eu_cookie_more_settings' name='eu_cookiewet_opties[eu_cookie_more_settings]' size='50' type='text' value='{$value}' />";
	}

	/**
	 * this is the validate method to make sure all input is sane
	 *
	 * @param  array $input input values
	 * @return array        sanitized input values
	 */
	public static function eu_cookiewet_opties_validate($input)
	{
		$options = get_option( 'eu_cookiewet_opties' );
		$options['eu_cookie_text_settings']   = trim($input['eu_cookie_text_settings']);
		$options['eu_cookie_accept_settings'] = trim($input['eu_cookie_accept_settings']);
		$options['eu_cookie_more_settings']   = trim($input['eu_cookie_more_settings']);

		return $options;
	}

	/**
	 * makes sure jquery is loaded
	 *
	 * @return void
	 */
	public static function eu_cookie_jquery()
	{
	    wp_enqueue_script( 'jquery' );
	}

	/**
	 * prints the css needed on the front page
	 *
	 * @return void
	 */
	public static function eu_add_cookie_css()
	{
		if ( !isset ( $_COOKIE["catAccCookies"] ) ) {
			echo '<style type="text/css" media="screen">
				html { margin-top: 32px; }
				* html body { margin-top: 32px; }
			#eu-cookie-bar {
				direction: ltr;
				color: #DDD;
				font: normal 13px/28px sans-serif;
				height: 30px;
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				min-width: 600px;
				z-index: 99999;
				padding:2px 20px 0;
				background-color: #464646;
				background-image: -ms-linear-gradient(bottom, #373737, #464646 5px);
				background-image: -moz-linear-gradient(bottom, #373737, #464646 5px);
				background-image: -o-linear-gradient(bottom, #373737, #464646 5px);
				background-image: -webkit-gradient(linear,left bottom,left top,from( #373737),to(#464646));
				background-image: -webkit-linear-gradient(bottom, #373737, #464646 5px);
				background-image: linear-gradient(bottom, #373737, #464646 5px);
				text-align:left;
			}
			#eu-cookie-bar a {
				color:#fff;
			}
			button#euCookie {
				margin:0 20px;
				line-height:20px;
				background:#45AE52;
				border:none;
				color:#fff;
				padding:0 12px;
				border-radius: 3px;
				cursor: pointer;
				font-size: 13px;
				font-weight: bold;
				font-family: sans-serif;
				text-shadow: #555 1px 1px;
			}
			button#euCookie:hover {
				background:#5EC544;
			}
			</style>
			<script type="text/javascript">
				function euAcceptCookies() {
					days = 30;
					var date = new Date();
					date.setTime(date.getTime()+(days*24*60*60*1000));
					var expires = "; expires="+date.toGMTString();
					document.cookie = "catAccCookies=true"+expires+"; path=/";
					jQuery("#eu-cookie-bar").hide();
					jQuery("html").css("margin-top","0");
				}
			</script>';
		}
	}

	/**
	 * prints the html required for the displaying of the cookie bar
	 *
	 * @return void
	 */
	public static function eu_add_cookie_bar()
	{
		if ( !isset ( $_COOKIE["catAccCookies"] ) )
		{
			$options = get_option('eu_cookiewet_opties');

			if ( $options['eu_cookie_text_settings'] )
			{
				$current_text = $options['eu_cookie_text_settings'];
			}
			else
			{
				$current_text = "Deze website maakt gebruik van cookies voor het verbeteren van de gebruikerservaring";
			}

			if ( $options['eu_cookie_accept_settings'] )
			{
				$accept_text = $options['eu_cookie_accept_settings'];
			}
			else
			{
				$accept_text = "Cookies toestaan";
			}

			if ( $options['eu_cookie_more_settings'] )
			{
				$more_text = $options['eu_cookie_more_settings'];
			}
			else
			{
				$more_text = "Meer informatie over de cookiewetgeving";
			}

			$link_text = strtolower ( $options['eu_cookie_link_settings'] );

			echo '<div id="eu-cookie-bar">' . htmlspecialchars ( $current_text ) . '<button id="euCookie" onclick="euAcceptCookies()">' . htmlspecialchars ( $accept_text ) . '</button><a href="' . get_bloginfo ( 'url' ) . '/cookie-policy/' . '">' . htmlspecialchars ( $more_text ) . '</a></div>';
		}
	}

	/**
	 * displays the configuration page
	 *
	 * @return void
	 */
	function eu_cookiewet_opties_pagina()
	{ ?>
		<div class="wrap">
			<h2>EU Cookiewet Plugin (Nederlands)</h2>
			<div id="poststuff" class="metabox-holder has-right-sidebar">
				<div class="meta-box-sortabless">
					<div class="postbox">
						<h3 class="hndle">Uw instellingen</h3>
						<div class="inside">
							<?php
							$options  = get_option('eu_cookiewet_opties');
							$pagename = 'Cookie Policy';
							$cpage    = get_page_by_title ( $pagename );

							if ( !$cpage )
							{
								global $user_ID;
								$page['post_type']    = 'page';
								$page['post_content'] = '<p></p>';
								$page['post_parent']  = 0;
								$page['post_author']  = $user_ID;
								$page['post_status']  = 'publish';
								$page['post_title']   = $pagename;
								$pageid               = wp_insert_post ( $page );

								if ( $pageid == 0 )
								{
									echo '<div class="updated settings-error">Pagina is niet aangemaakt.</div>';
								}
								else
								{
									echo '<div class="updated">De Cookie Policy pagina is aangemaakt.</div>';
								}
							} ?>
							<form action="options.php" method="post">
								<?php settings_fields('eu_cookiewet_opties'); ?>
								<?php do_settings_sections('eu_cookie'); ?>
								<input name="cat_submit" type="submit" id="submit" class="button-primary" style="margin-top:30px;" value="<?php esc_attr_e('Save Changes'); ?>" />
								<p>U kunt uw cookie policy pagina <a href="<?php bloginfo ( 'url' ); ?>/cookie-policy/">hier</a> aanpassen.</p>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php }
}

/**
 * hooks, actions & filters
 */
add_action('admin_menu', array('EUCookieWet', 'on_admin_menu'));
add_action('admin_init', array('EUCookieWet', 'on_admin_init'));
add_action('wp_enqueue_scripts', array('EUCookieWet', 'eu_cookie_jquery'));
add_action('wp_head', array('EUCookieWet', 'eu_add_cookie_css'));
add_action('wp_footer', array('EUCookieWet', 'eu_add_cookie_bar'), 1000 );