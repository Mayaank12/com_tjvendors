<?php
/**
 * @version    SVN:
 * @package    Com_Tjvendors
 * @author     Techjoomla <contact@techjoomla.com>
 * @copyright  Copyright  2009-2017 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access to this file
defined('_JEXEC') or die;

JLoader::import('joomla.application.component.model');

/**
 * List view : Pending Payouts
 *
 * @since  1.6
 */
class TjvendorsViewVendors extends JViewLegacy
{
	protected $user_id;

	protected $input;

	protected $items;

	protected $pagination;

	protected $filterForm;

	protected $activeFilters;

	protected $currencies;

	protected $vendor_id;

	protected $uniqueClients;

	protected $totalDetails;

	protected $vendorClient;

	/**
	 * Display passbook transaction list
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$this->user_id = JFactory::getUser()->id;
		$this->input = $app->input;

		// Get data from the model
		$items_model = JModelLegacy::getInstance('vendors', 'TjvendorsModel');
		$this->items = $items_model->getItems();
		$this->pagination	= $items_model->getPagination();
		$this->state		= $items_model->getState();
		$this->filterForm		= $items_model->getFilterForm();
		$this->activeFilters	= $items_model->getActiveFilters();
		$tjvendorFrontHelper = new TjvendorFrontHelper;
		$this->currencies = $tjvendorFrontHelper->getCurrencies();
		$this->vendor_id = $tjvendorFrontHelper->getvendor();
		$this->uniqueClients = $tjvendorFrontHelper->getUniqueClients($this->user_id);
		$client = $this->state->get('filter.vendor_client', '');
		$currency = $this->state->get('filter.currency', '');
		$this->totalDetails = $tjvendorFrontHelper->getTotalDetails($this->vendor_id, $client, $currency);
		$this->vendorClient = $app->getUserStateFromRequest('client', 'client', '');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}
		// Display the template
		parent::display($tpl);
	}
}
