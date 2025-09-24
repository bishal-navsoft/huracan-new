<?PHP
class AuditRemidial extends AppModel
{
	public $name = 'AuditRemidial';
	
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