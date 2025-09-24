<?PHP
class SuggestionRemidial extends AppModel
{
	public $name = 'SuggestionRemidial';
	
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