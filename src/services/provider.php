<?php
/**
 * @author          Gileba <info@gileba.be>
 * @link            https://gileba.be
 * @copyright       Copyright © 2023 Gileba All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined("_JEXEC") or die("Restricted access");

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\System\Vmnotify\Extension\Vmnotify;

return new class implements ServiceProviderInterface {
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 *
	 * @since   4.4.0
	 */
	public function register(Container $container): void
	{
		$container->set(PluginInterface::class, function (Container $container) {
			$plugin = new Vmnotify(
				$container->get(DispatcherInterface::class),
				(array) PluginHelper::getPlugin("system", "vmnotify")
			);
			$plugin->setApplication(Factory::getApplication());
			$plugin->setDatabase($container->get(DatabaseInterface::class));

			return $plugin;
		});
	}
};
