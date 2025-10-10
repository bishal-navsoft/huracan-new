<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ImmediateCausesTable extends Table
{
    public function initialize(array $config): void
    {
	parent::initialize($config);

	$this->setTable('immediate_causes');
	$this->setPrimaryKey('id');
	$this->setDisplayField('type');
    }
	
	
	
}
?>
