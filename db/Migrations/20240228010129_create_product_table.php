<?php

declare(strict_types=1);

use Doctrine\DBAL\Schema\Schema;
use Phinx\Migration\AbstractMigration;

final class CreateProductTable extends AbstractMigration
{
	public function up(Schema $schema): void
	{
		// Products table
		if (!$schema->hasTable('products')) {
			$table = $schema->createTable('products');
			$table->addColumn('id', 'integer', ['autoincrement' => true]);
			$table->addColumn('name', 'string', ['length' => 255]);
			$table->addColumn('value', 'decimal', ['precision' => 10, 'scale' => 2]);
			$table->addColumn('stock', 'bigint', ['default' => 0]);
			$table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
			$table->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);

			$table->setPrimaryKey(['id']);
		}
	}

	public function down(Schema $schema): void
	{
		$schema->dropTable('products');
	}
}
