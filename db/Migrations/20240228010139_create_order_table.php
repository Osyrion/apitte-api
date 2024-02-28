<?php

declare(strict_types=1);

use Doctrine\DBAL\Schema\Schema;
use Phinx\Migration\AbstractMigration;

final class CreateOrderTable extends AbstractMigration
{
	public function up(Schema $schema): void
	{
		// Orders table
		if (!$schema->hasTable('orders')) {
			$table = $schema->createTable('orders');
			$table->addColumn('id', 'integer', ['autoincrement' => true]);
			$table->addColumn('customer_id', 'bigint', ['notnull' => false]);
			$table->addColumn('order_state', 'string', ['length' => 255]);
			$table->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2]);
			$table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
			$table->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);

			$table->setPrimaryKey(['id']);

			$table->addForeignKeyConstraint('customers', ['customer_id'], ['id'], ['onDelete' => 'CASCADE']);
		}
	}

	public function down(Schema $schema): void
	{
		$schema->getTable('orders')->removeForeignKey('customer_id');
		$schema->dropTable('orders');
	}
}
