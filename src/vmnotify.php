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
	 *  The Joomla Application object
	 *
	 *  @var  object
	 */
	protected $app;

	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	public function onAfterRoute(): void
	{
		// Run this in frontend only
		if (!$this->app->isClient("site")) {
			return;
		}

		$input = $this->app->getInput();
		$option = $input->get("option");

		if ($option !== "com_users") {
			return;
		}

		$view = $input->get("view");

		$this->app->enqueueMessage($view);
	}
}
