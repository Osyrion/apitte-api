<?php

declare(strict_types=1);

use Doctrine\DBAL\Schema\Schema;
use Phinx\Migration\AbstractMigration;

final class CreateOrderProductsTable extends AbstractMigration
{
	public function up(Schema $schema): void
	{
		// Order products table
		if (!$schema->hasTable('order_products')) {
			$table = $schema->createTable('order_products');
			$table->addColumn('id', 'integer', ['autoincrement' => true]);
			$table->addColumn('product_id', 'bigint', ['notnull' => false]);
			$table->addColumn('order_id', 'bigint');
			$table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
			$table->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);

			$table->setPrimaryKey(['id']);

			$table->addForeignKeyConstraint('products', ['product_id'], ['id'], ['onDelete' => 'CASCADE']);
			$table->addForeignKeyConstraint('orders', ['order_id'], ['id'], ['onDelete' => 'CASCADE']);

			$table->addIndex(['product_id']);
			$table->addIndex(['order_id']);
		}
	}

	public function down(Schema $schema): void
	{
		$schema->getTable('order_products')->removeForeignKey('product_id');
		$schema->getTable('order_products')->removeForeignKey('order_id');
		$schema->dropTable('order_products');
	}
}
