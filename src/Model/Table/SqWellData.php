<?PHP
class SqWellData extends AppModel
{
	public $name = 'SqWellData';
  
	public $belongsTo = array(
		'Welldata' => array(
		    'className'    => 'Welldata',
		    'foreignKey'   => 'fluid_name'
		),
             'WellStatus' => array(
		    'className'    => 'WellStatus',
		    'foreignKey'   => 'staus_name' 
		    
		)
       );  

}
?>
