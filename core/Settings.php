<?php
namespace core;
class Settings extends Model
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Date,
		\core\traits\adapters\Alias,
		\core\traits\adapters\Base,
		\core\traits\outAdapters\OutDate;

	public function mainTable()
	{
	    return 'tbl_settings';
	}

	public function rules()
	{
		return array(
			'pages_view_back, pages_view_forward, week_f_day' => array(
				'validation' => array('_validInt',  array('notEmpty'=>true)),
				'adapt' => '_adaptChangeName'
			),
			'admin_email' => array(
				'validation' => array('_validEmail',  array('not_empty'=>true)),
				'adapt' => '_adaptChangeName'
			),
			'bcc_email' => array(
				'validation' => array('_validEmail',  array('not_empty'=>true)),
				'adapt' => '_adaptChangeName'
			),
			'noreply_email, rate' => array(
				'adapt' => '_adaptChangeName'
			),
			'metaTitle, metaKeywords, metaDescription, headerText' => array(
				'adapt' => '_adaptChangeName'
			)
		);
	}

	public function getSettings($fields = '*', $filter = array())
	{
		$rows = $this->getAll($fields, $filter);
		if (empty($rows)) return;
		foreach ($rows as $row) {
			$rows_new[$row['name']] = $row['value'];
		}
		unset($rows);
		return $rows_new;
	}

	public function getSettingsEdit($filter = array())
	{
		$rows = $this->getAll('*', $filter);
		if (empty($rows)) return;
		$out = $this->_outByName();
		foreach ($rows as $key=>$row) {
			if (in_array($row['name'], array_keys($out))) {
				$rows[$key]['value'] = $this->$out[$row['name']]($row['value']);
			}
		}
		return $rows;
	}

	protected function _outByName()
	{
		return array();
	}

	public function getSetting($name)
	{
		$filters = array(
			'where' => array(
				'query' => '`name`="?s"',
				'data' => array($name),
				)
			);
		$row = $this->getOne('*', $filters);
		if (empty($row)) return;
		$out = $this->_outByAlias();
		if (in_array($row['name'], array_keys($out))) {
			return $this->$out[$row['name']]($row['value']);
		}
	}

	protected function _adaptChangeName($key)
	{
		$this->data['value'] = $this->data[$key];
		unset($this->data[$key]);
	}

	protected function _adaptSerialize($key)
	{
		$this->data['value'] = serialize($this->data[$key]);
		unset($this->data[$key]);
	}

	public function edit($rows) {
		foreach ($rows as $key=>$row) {
			$row['id'] = $key;
			$result = parent::baseEdit($row, array_keys($row));
			if (!$result) return false;
		}
		return $result;
	}

	public function getAllGlobalSettings()
	{
		return new \core\ArrayWrapper($this->getSettings('*'));
	}

}
?>
