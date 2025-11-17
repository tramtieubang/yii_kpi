<?php

use yii\db\Migration;

class m251116_091121_create_positions_business_fields_and_insert_demo extends Migration
{
    /**
     * {@inheritdoc}
     */

   public function safeUp()
    {
        // 1️⃣ Bảng positions
        $this->createTable('{{%positions}}', [
            'id' => $this->primaryKey()->comment('ID chức vụ'),
            'name' => $this->string(255)->notNull()->comment('Tên chức vụ'),
            'description' => $this->text()->null()->comment('Mô tả chức vụ'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Thời gian tạo'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Thời gian cập nhật'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT="Bảng chức vụ"');

        // 2️⃣ Bảng business_fields
        $this->createTable('{{%business_fields}}', [
            'id' => $this->primaryKey()->comment('ID lĩnh vực kinh doanh'),
            'name' => $this->string(255)->notNull()->comment('Tên lĩnh vực kinh doanh'),
            'description' => $this->text()->null()->comment('Mô tả lĩnh vực'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Thời gian tạo'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Thời gian cập nhật'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT="Lĩnh vực kinh doanh"');

        // 3️⃣ Bảng departments
        $this->createTable('{{%departments}}', [
            'id' => $this->primaryKey()->comment('ID phòng ban'),
            'code' => $this->string(50)->notNull()->unique()->comment('Mã phòng ban'),
            'name' => $this->string(255)->notNull()->comment('Tên phòng ban'),
            'description' => $this->text()->comment('Mô tả phòng ban'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Thời gian tạo'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Thời gian cập nhật'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT="Bảng phòng ban"');

        // 4️⃣ Bảng employees
        $this->createTable('{{%employees}}', [
            'id' => $this->primaryKey()->comment('ID nhân viên'),
            'user_id' => $this->integer()->notNull()->comment('ID user trong bảng user'),
            'department_id' => $this->integer()->notNull()->comment('ID phòng ban'),
            'position_id' => $this->integer()->null()->comment('ID chức vụ'), // SET NULL → phải null
            'business_field_id' => $this->integer()->null()->comment('ID lĩnh vực kinh doanh'), // SET NULL → phải null
            'name' => $this->string(255)->notNull()->comment('Họ tên nhân viên'),
            'email' => $this->string(255)->notNull()->unique()->comment('Email nhân viên'),
            'phone' => $this->string(50)->comment('Số điện thoại'),
            'hire_date' => $this->date()->comment('Ngày tuyển dụng'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Thời gian tạo'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Thời gian cập nhật'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT="Bảng nhân viên"');

        // 5️⃣ Foreign keys
        $this->addForeignKey('fk_employees_user', '{{%employees}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_employees_department', '{{%employees}}', 'department_id', '{{%departments}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_employees_position', '{{%employees}}', 'position_id', '{{%positions}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_employees_business_field', '{{%employees}}', 'business_field_id', '{{%business_fields}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function safeDown()
    {
        // 1️⃣ Xóa foreign key employees
        $this->dropForeignKey('fk_employees_business_field', '{{%employees}}');
        $this->dropForeignKey('fk_employees_position', '{{%employees}}');
        $this->dropForeignKey('fk_employees_department', '{{%employees}}');
        $this->dropForeignKey('fk_employees_user', '{{%employees}}');

        // 2️⃣ Xóa bảng employees
        $this->dropTable('{{%employees}}');

        // 3️⃣ Xóa bảng departments
        $this->dropTable('{{%departments}}');

        // 4️⃣ Xóa bảng business_fields & positions
        $this->dropTable('{{%business_fields}}');
        $this->dropTable('{{%positions}}');
    }
}