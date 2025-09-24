<?PHP
class JnPersonnel extends AppModel
{
	public $name = 'JnPersonnel';
	public $belongsTo = array(
        'AdminMaster' => array(
            'className'    => 'AdminMaster',
            'foreignKey'   => 'personal_data'
	),
	'Vehicle' => array(
            'className'    => 'Vehicle',
            'foreignKey'   => 'vehicle'
	)
    );
	
}
?>