<?PHP
class JobWellData extends AppModel
{
	public $name = 'JobWellData';
  
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
