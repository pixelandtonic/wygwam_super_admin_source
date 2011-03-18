<?php if ( ! defined('APP_VER')) exit('No direct script access allowed');


/**
 * Wygwam Super Admin Source
 * 
 * @author    Brandon Kelly <brandon@pixelandtonic.com>
 * @copyright Copyright (c) 2010 Brandon Kelly
 * @license   http://creativecommons.org/licenses/by-sa/3.0/ Attribution-Share Alike 3.0 Unported
 */

class Wygwam_super_admin_source_ext {

	var $name           = 'Wygwam Super Admin Source';
	var $version        = '1.0';
	var $description    = 'Forces Wygwam’s “Source” toolbar button to always show up for Super Admins';
	var $settings_exist = 'n';
	var $docs_url       = 'http://github.com/brandonkelly/wygwam_super_admin_source';

	/**
	 * Class Constructor
	 */
	function __construct()
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();
	}

	// --------------------------------------------------------------------

	/**
	 * Activate Extension
	 */
	function activate_extension()
	{
		// add the row to exp_extensions
		$this->EE->db->insert('extensions', array(
			'class'    => get_class($this),
			'method'   => 'wygwam_config',
			'hook'     => 'wygwam_config',
			'settings' => '',
			'priority' => 10,
			'version'  => $this->version,
			'enabled'  => 'y'
		));
	}

	/**
	 * Update Extension
	 */
	function update_extension($current = '')
	{
		// Nothing to change...
		return FALSE;
	}

	/**
	 * Disable Extension
	 */
	function disable_extension()
	{
		// Remove all Wygwam_super_admin_source_ext rows from exp_extensions
		$this->EE->db->where('class', get_class($this))
		             ->delete('extensions');
	}

	// --------------------------------------------------------------------

	/**
	 * wygwam_config hook
	 */
	function wygwam_config($config, $settings)
	{
		// If another extension shares the same hook,
		// we need to get the latest and greatest config
		if ($this->EE->extensions->last_call !== FALSE)
		{
			$config = $this->EE->extensions->last_call;
		}

		// try and find an existing Source button
		foreach ($config['toolbar'] as &$tbgroup)
		{
			if (array_search('Source', $tbgroup) !== FALSE)
			{
				// found it, so we're done here
				return $config;
			}
		}

		// ok, add it to the beginning
		array_unshift($config['toolbar'], array(array('Source')));

		return $config;
	}
}
