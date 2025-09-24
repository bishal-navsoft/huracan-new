<?PHP
class SqPersonnel extends AppModel
{
	public $name = 'SqPersonnel';
		
	public $belongsTo = array(
        'AdminMaster' => array(
            'className'    => 'AdminMaster',
            'foreignKey'   => 'personal_data'
	)
    );
	
}
?>