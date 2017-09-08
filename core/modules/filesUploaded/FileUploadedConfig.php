<?php
namespace core\modules\filesUploaded;
class FileUploadedConfig extends \core\modules\base\ModuleConfig
{
    use \core\traits\adapters\Alias,
        \core\traits\adapters\Date;


    const SECONDARY_CATEGORY_ID = 1;
    const PRIMARY_CATEGORY_ID = 2;
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 1;

    protected $objectClass = '\core\modules\filesUploaded\FileUploaded';
    protected $objectsClass = '\core\modules\filesUploaded\FilesUploaded';

    protected $tablePostfix = '_files'; // set value without preffix!\
    protected $idField = 'id';
    protected $objectFields = array(
        'id',
        'alias',
        'name',
        'title',
        'description',
        'date',
        'objectId',
        'statusId',
        'categoryId',
        'extension',
    );

    public function rules()
    {
        return array(
            'title' => array(
                'adapt' => '_adaptHtml',
            ),
            'alias' => array(
                'adapt' => '_adaptAlias',
            ),
            'tmpName' => array(
                'validation' => array('_validFileExists'),
            ),
            'statusId' => array(
                'validation' => array('_validInt', array('notEmpty'=>true)),
            ),
            'categoryId' => array(
                'validation' => array('_validInt', array('notEmpty'=>true)),
            ),
            'date' => array(
                'adapt' => '_adaptRegDate',
            ),
            'extension' => array(
                'validation' => array('_validNotEmpty'),
                'adapt' => '_adaptHtml',
            ),
        );
    }

    public function outputRules()
    {
        return array(
            'date' => array('_outDate')
        );
    }

    public function _outDate($data)
    {
        return \core\utils\Dates::convertDate($data, 'simple');
    }

    public function _adaptRegDate($key)
    {
        $this->data[$key] = (!empty($this->data[$key])) ? \core\utils\Dates::convertDate($this->data[$key], 'mysql') : time();
    }

    public function _adaptAlias()
    {
        if( ! $this->data['alias'] )
            $this->generateAlias();
    }
    public function generateAlias()
    {
        $this->data['alias'] = $this->transformAlias($this->data['name']);
    }
    public function transformAlias($name)
    {

        $name = explode('.', $name);
        $ext = array_pop ($name);
        $alias = implode("_", $name);

        while( \core\db\Db::getMySql()->isExist($this->table,  'alias', $alias) ){
            $alias1 = $alias;
            $array = explode("_", $alias);
            if( sizeof($array) > 1){
                $last = array_pop ($array);
                if($last == '0'  or  $last == '-0'){
                    $alias = implode("_", $array);
                    $alias = $alias.'_'.$last.'_1';
                }
                else{
                    if((int)$last == 0){
                        $alias = $alias1.'_1';
                    }
                    else{
                        $last = (int)$last;
                        if($last > 0){
                            $alias = implode("_", $array);
                            $last = $last + 1;
                            $last = (string)$last;
                            $alias = $alias.'_'.$last;
                        }
                        if($last < 0  or  $last == 0){
                            $alias = implode("_", $array);
                            $last = (string)$last;
                            $alias = $alias.'_'.$last.'_1';
                        }
                    }
                }
            }
            else
                $alias = $alias1.'_1';
        }
        return $alias;
    }

    public function _adaptHtml($key)
    {
        if (isset($this->data[$key]))
            $this->data[$key] = \core\utils\DataAdapt::textValid($this->data[$key]);
    }

    public function _validFileExists($key)
    {
        if (!file_exists(DIR.$this->data[$key])) {
            $this->addError($key, 'File does not exists');
            return false;
        }
        return true;
    }
}