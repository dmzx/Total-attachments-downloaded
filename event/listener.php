<?php
/**
*
* @package phpBB Extension - Total Attachments Downloaded
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\totalattdownloaded\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;
	/**
	* Constructor
	* @param \phpbb\template\template			$template
	* @param \phpbb\user						$user
	* @param \phpbb\db\driver\driver_interface	$db
	*
	*/
	public function __construct(\phpbb\template\template $template, \phpbb\user $user, \phpbb\db\driver\driver_interface $db)
	{
		$this->template = $template;
		$this->user = $user;
		$this->db = $db;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.page_header'	=> 'page_header',
		);
	}

	public function page_header($event)
	{
		$this->user->add_lang_ext('dmzx/totalattdownloaded', 'common');

		$sql = 'SELECT SUM(download_count) AS count
			FROM ' . ATTACHMENTS_TABLE. '
			WHERE extension = "zip"
			OR extension = "rar"';
		$result = $this->db->sql_query($sql);
		$download_count = (int) $this->db->sql_fetchfield('count');

		$this->template->assign_vars(array(
			'TOTAL_DOWNLOADED'	=> $this->user->lang['TOTAL_DOWNLOADED'] . ' <strong>' . number_format($download_count) . '</strong>',
		));
	}
}