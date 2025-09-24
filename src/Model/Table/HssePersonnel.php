<?PHP
class HssePersonnel extends AppModel
{
	public $name = 'HssePersonnel';
	public $belongsTo = array(
        'AdminMaster' => array(
            'className'    => 'AdminMaster',
            'foreignKey'   => 'personal_data'
	)
    );
	
}
?>