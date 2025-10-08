<?PHP
namespace App\Model\Table;

use Cake\ORM\Table;

class IncidentTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Explicit table name if it doesn't follow conventions
        $this->setTable('incidents'); // your actual table name
        $this->setPrimaryKey('id');
    }
}
?>
