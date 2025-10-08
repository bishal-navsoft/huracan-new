<?PHP
namespace App\Model\Table;

use Cake\ORM\Table;

class FieldlocationTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Explicit table name if it doesn't follow conventions
        $this->setTable('fieldlocations'); // your actual table name
        $this->setPrimaryKey('id');
    }
}
?>
