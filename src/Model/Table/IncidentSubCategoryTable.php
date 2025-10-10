<?PHP
namespace App\Model\Table;

use Cake\ORM\Table;

class IncidentSubCategoryTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('incident_sub_categories');
        $this->setPrimaryKey('id');
        $this->setDisplayField('type');
    }
	
}
?>
