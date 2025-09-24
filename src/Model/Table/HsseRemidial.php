<?PHP
class HsseRemidial extends AppModel
{
	public $name = 'HsseRemidial';
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