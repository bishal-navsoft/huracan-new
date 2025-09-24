<?PHP
class LdjsEmail extends AppModel
{
	public $name = 'LdjsEmail';
	
		public $belongsTo = array(
		'AdminMaster' => array(
		    'className'    => 'AdminMaster',
		    'foreignKey'   => 'user_id'
	   
		)
      
	);

	
}
?>