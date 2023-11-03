<?php
/**
 * @author          Gileba <info@gileba.be>
 * @link            https://gileba.be
 * @copyright       Copyright © 2023 Gileba All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined("_JEXEC") or die("Restricted access");

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Mail\Mail;

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

		if ($option !== "com_virtuemart") {
			return;
		}

		$task = $input->get("task");
		if ($task !== "notifycustomer") {
			return;
		}

		$mailer = new Mail();
		$mailer->setSender("info@musalie.be");
		$mailer->addRecipient("info@gileba.be");
		$mailer->setSubject("Er is interesse in een uitverkocht product");
		$mailer->setBody("Dit is een test");

		$mailResult = $mailer->send();
	}
}
