<?php
/**
 * @author          Gileba <info@gileba.be>
 * @link            https://gileba.be
 * @copyright       Copyright © 2023 Gileba All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

namespace Joomla\Plugin\System\Vmnotify\Extension;

defined("_JEXEC") or die("Restricted access");

use Joomla\Component\Virtuemart\Administrator\Helpers\vmmodel;
use Joomla\Database\DatabaseAwareInterface;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Mail\Mail;
use Joomla\CMS\Plugin\CMSPlugin;

final class Vmnotify extends CMSPlugin implements DatabaseAwareInterface
{
	use DatabaseAwareTrait;

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

		// Run this in virtuemart only
		if ($option !== "com_virtuemart") {
			return;
		}

		// Run this only when the task "notifycustomer" is used
		$task = $input->get("task");
		if ($task !== "notifycustomer") {
			return;
		}

		$virtuemart_product_id = $input->get("virtuemart_product_id", 0, "INT");
		$virtuemart_user_id = $input->get("virtuemart_user_id", 0, "INT");

		$db = $this->getDatabase();

		// Get the Vendor ID
		$query = $db->getQuery(true);
		$query
			->select($db->quoteName("virtuemart_vendor_id"))
			->from($db->quoteName("#__virtuemart_products"))
			->where($db->quoteName("virtuemart_product_id") . " = " . $virtuemart_product_id);

		$db->setQuery($query);
		$item = $db->loadObject();
		$virtuemart_vendor_id = $item->virtuemart_vendor_id;

		// Get the email of the Vendor
		$query = $db->getQuery(true);
		$query
			->select($db->quoteName(["vmu.virtuemart_user_id", "vmu.virtuemart_vendor_id", "u.email"]))
			->from($db->quoteName("#__virtuemart_vmusers", "vmu"))
			->join(
				"LEFT",
				$db->quoteName("#__users", "u") .
					" ON " .
					$db->quoteName("vmu.virtuemart_user_id") .
					" = " .
					$db->quoteName("u.id")
			)
			->where($db->quoteName("vmu.virtuemart_vendor_id") . " = " . $virtuemart_vendor_id);
		echo $db->getQuery(false);

		$db->setQuery($query);
		$item = $db->loadObject();
		$vendorEmail = $item->email;

		// Get the product name
		$query = $db->getQuery(true);
		$query
			->select($db->quoteName(["l.product_name", "product_in_stock", "p.product_sku"]))
			->from($db->quoteName("#__virtuemart_products_" . VMLANG, "l"))
			->join(
				"LEFT",
				$db->quoteName("#__virtuemart_products", "p") .
					" ON " .
					$db->quoteName("p.virtuemart_product_id") .
					" = " .
					$db->quoteName("l.virtuemart_product_id")
			)
			->where($db->quoteName("p.virtuemart_product_id") . " = " . $virtuemart_product_id);

		$db->setQuery($query);
		$item = $db->loadObject();

		// Send the mail to the vendor
		$mailer = new Mail();
		$mailer->setSender($vendorEmail);
		$mailer->addReplyTo($vendorEmail);
		$mailer->addRecipient($vendorEmail);
		$mailer->setSubject(Text::_("PLG_SYSTEM_VMNOTIFY_MAIL_SUBJECT", $item->product_name));
		$mailer->setBody(Text::_("PLG_SYSTEM_VMNOTIFY_MAIL_BODY", $item->product_name));

		$mailResult = $mailer->send();
	}
}
