<?php
/**
 * @author          Gileba <info@gileba.be>
 * @link            https://gileba.be
 * @copyright       Copyright © 2023 Gileba All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined("_JEXEC") or die("Restricted access");

use Joomla\CMS\Plugin\CMSPlugin;

class plgSystemVmnotify extends CMSPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 *  Handling of protected extensions
	 *  Throws a notice message if the Download Key is missing before downloading the package
	 *
	 *  @param   string  &$url      Update Site URL
	 *  @param   array   &$headers
	 */
}
