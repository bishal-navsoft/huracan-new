<?PHP
class JobRemidial extends AppModel
{
	public $name = 'JobRemidial';
	
	 public $belongsTo = array(
        'Priority' => array(
            'className'    => 'Priority',
            'foreignKey'   => 'remidial_priority'
        ),
	'AdminMaster' => array(
            'className'    => 'AdminMaster',
            'foreignKey'   => 'remidial_createby'
        )
	
    );
	
}
?>