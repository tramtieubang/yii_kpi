<?php

use yii\db\Migration;

class m251116_091121_create_positions_business_fields_and_insert_demo extends Migration
{
    /**
     * {@inheritdoc}
     */

    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');

        // 1️⃣ Bảng positions
        $this->createTable('{{%positions}}', [
            'position_id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text()->null(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT="Chức vụ nhân viên"');

        // 2️⃣ Bảng business_fields
        $this->createTable('{{%business_fields}}', [
            'business_field_id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text()->null(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT="Lĩnh vực kinh doanh"');

        // 3️⃣ Bảng department
        $this->createTable('{{%department}}', [
            'department_id' => $this->primaryKey(),
            'name' => $this->string(255)->null(),
            'code' => $this->string(100)->null(),
            'description' => $this->text()->null(),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT="Bảng phòng ban"');

        // 4️⃣ Bảng staff
        $this->createTable('{{%staff}}', [
            'staff_id' => $this->primaryKey()->comment('ID nhân viên'),
            'department_id' => $this->integer()->notNull()->comment('ID phòng ban'),
            'position_id' => $this->integer()->null()->comment('ID chức vụ'),
            'business_field_id' => $this->integer()->null()->comment('ID lĩnh vực kinh doanh'),
            'name' => $this->string(255)->notNull()->comment('Họ tên nhân viên'),
            'email' => $this->string(191)->notNull()->unique()->comment('Email nhân viên'),
            'phone' => $this->string(50)->null()->comment('Số điện thoại'),
            'hire_date' => $this->date()->null()->comment('Ngày tuyển dụng'),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->comment('Thời gian tạo'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Thời gian cập nhật'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT="Bảng nhân viên"');

        // 🔗 Thêm FK cho staff
        $this->addForeignKey(
            'fk_staff_department',
            '{{%staff}}', 'department_id',
            '{{%department}}', 'department_id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_staff_position',
            '{{%staff}}', 'position_id',
            '{{%positions}}', 'position_id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk_staff_business_field',
            '{{%staff}}', 'business_field_id',
            '{{%business_fields}}', 'business_field_id',
            'SET NULL'
        );

        // ✅ Chèn dữ liệu demo vào positions
        $this->batchInsert('{{%positions}}', ['name', 'description', 'created_at', 'updated_at'], [
            ['Giám đốc', 'Mô tả Giám đốc', $now, $now],
            ['Trưởng phòng', 'Mô tả Trưởng phòng', $now, $now],
            ['Nhân viên', 'Mô tả Nhân viên', $now, $now],
        ]);
    }

    public function safeDown()
    {
        // Xóa FK trước khi drop bảng
        $this->dropForeignKey('fk_staff_department', '{{%staff}}');
        $this->dropForeignKey('fk_staff_position', '{{%staff}}');
        $this->dropForeignKey('fk_staff_business_field', '{{%staff}}');

        $this->dropTable('{{%staff}}');
        $this->dropTable('{{%department}}');
        $this->dropTable('{{%business_fields}}');
        $this->dropTable('{{%positions}}');
    }
}