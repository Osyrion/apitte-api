<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228212157 extends AbstractMigration
{
	public function getDescription(): string
	{
		return '';
	}

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
			$table->addColumn('created_at', 'datetime');
			$table->addColumn('updated_at', 'datetime');

			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['email']);
		}

		// Products table
		if (!$schema->hasTable('products')) {
			$table = $schema->createTable('products');
			$table->addColumn('id', 'integer', ['autoincrement' => true]);
			$table->addColumn('name', 'string', ['length' => 255]);
			$table->addColumn('value', 'decimal', ['precision' => 10, 'scale' => 2]);
			$table->addColumn('stock', 'integer', ['default' => 0]);
			$table->addColumn('created_at', 'datetime');
			$table->addColumn('updated_at', 'datetime');

			$table->setPrimaryKey(['id']);
		}

		// Orders table
		if (!$schema->hasTable('orders')) {
			$table = $schema->createTable('orders');
			$table->addColumn('id', 'integer', ['autoincrement' => true]);
			$table->addColumn('customer_id', 'integer', ['notnull' => true]);
			$table->addColumn('order_state', 'string', ['length' => 255]);
			$table->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2]);
			$table->addColumn('created_at', 'datetime');
			$table->addColumn('updated_at', 'datetime');

			$table->setPrimaryKey(['id']);

			$table->addIndex(['customer_id']);
			$table->addForeignKeyConstraint('customers',
				['customer_id'], ['id'], ['onDelete' => 'CASCADE', 'onUpdate' => 'CASCADE']);
		}

		// Order products table
		if (!$schema->hasTable('order_products')) {
			$table = $schema->createTable('order_products');
			$table->addColumn('id', 'integer', ['autoincrement' => true]);
			$table->addColumn('product_id', 'integer', ['notnull' => true]);
			$table->addColumn('order_id', 'integer');
			$table->addColumn('created_at', 'datetime');
			$table->addColumn('updated_at', 'datetime');

			$table->setPrimaryKey(['id']);
			$table->addIndex(['product_id']);
			$table->addIndex(['order_id']);

			$table->addForeignKeyConstraint('products',
				['product_id'], ['id'], ['onDelete' => 'CASCADE', 'onUpdate' => 'CASCADE']);
			$table->addForeignKeyConstraint('orders',
				['order_id'], ['id'], ['onDelete' => 'CASCADE', 'onUpdate' => 'CASCADE']);
		}
	}

	public function down(Schema $schema): void
	{
		$schema->dropTable('customers');
		$schema->dropTable('products');
		$schema->getTable('orders')->removeForeignKey('customer_id');
		$schema->dropTable('orders');
		$schema->getTable('order_products')->removeForeignKey('product_id');
		$schema->getTable('order_products')->removeForeignKey('order_id');
		$schema->dropTable('order_products');
	}
}
