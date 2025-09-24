<?PHP
class SqRemidial extends AppModel
{
	public $name = 'SqRemidial';
	
	 public $belongsTo = array(
        'Priority' => array(
            'className'    => 'Priority',
            'foreignKey'   => 'remidial_priority'
        ),
	'AdminMaster' => array(
            'className'    => 'AdminMaster',
            'foreignKey'   => 'remidial_createby'
        ),
	
    );
	
}
?>