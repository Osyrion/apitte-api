<?php

declare(strict_types=1);

use Doctrine\DBAL\Schema\Schema;
use Phinx\Migration\AbstractMigration;

final class CreateCustomerTable extends AbstractMigration
{
	public function up(Schema $schema): void
	{
		// Customers table
		if (!$schema->hasTable('customers')) {
			$table = $schema->createTable('customers');
			$table->addColumn('id', 'integer', ['autoincrement' => true]);
			$table->addColumn('first_name', 'string', ['length' => 255]);
			$table->addColumn('last_name', 'string', ['length' => 255]);
			$table->addColumn('email', 'string', ['length' => 255]);
			$table->addColumn('telephone', 'string', ['length' => 255]);
			$table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
			$table->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);

			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['email']);
		}
	}

	public function down(Schema $schema): void
	{
		$schema->dropTable('customers');
	}
}
