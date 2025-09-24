<?PHP
class SqInvestigation extends AppModel
{
	public $name = 'SqInvestigation';
	public $belongsTo = array(
        'SqReportMain' => array(
            'className'    => 'SqReportMain',
            'foreignKey'   => 'report_id',
          
        )
    );
    

}
?>