<?php

use yii\db\Migration;

class m251127_090151_create_table_kpi_work_assignment_history extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // BẢNG LỊCH SỬ PHÂN CÔNG CÔNG VIỆC
        $this->createTable('{{%kpi_work_assignment_history}}', [
            'id' => $this->primaryKey()->comment('ID lịch sử'),
            'work_assignment_id' => $this->integer()->notNull()->comment('ID phân công công việc'),
            'staff_id' => $this->integer()->comment('Nhân viên được phân công'), // CHO NULL
            'status_id' => $this->integer()->comment('Trạng thái công việc'),
            'kpi_id' => $this->integer()->comment('ID KPI liên quan'),
            'title' => $this->string(255)->notNull()->comment('Tiêu đề công việc'),
            'description' => $this->text()->comment('Mô tả công việc'),
            'start_date' => $this->date()->notNull()->comment('Ngày bắt đầu'),
            'end_date' => $this->date()->comment('Ngày kết thúc'),
            'color' => $this->string(20)->defaultValue('#3788d8')->comment('Màu sắc lịch'),
            'action_type' => $this->string(20)->defaultValue('update')->comment('Loại hành động (create/update/delete/assign/change)'),
            'assigned_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->comment('Ngày phân công'),
            'updated_by' => $this->integer()->comment('Người thực hiện hành động'),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        // KHÓA NGOẠI assignment
        $this->addForeignKey(
            'fk_kpi_assignment_history_assignment',
            '{{%kpi_work_assignment_history}}',
            'work_assignment_id',
            '{{%kpi_work_assignment}}',
            'id',
            'CASCADE'
        );

        // KHÓA NGOẠI staff
        $this->addForeignKey(
            'fk_kpi_assignment_history_staff',
            '{{%kpi_work_assignment_history}}',
            'staff_id',
            '{{%staff}}',
            'staff_id',
            'SET NULL'
        );

        // KHÓA NGOẠI status
        $this->addForeignKey(
            'fk_kpi_assignment_history_status',
            '{{%kpi_work_assignment_history}}',
            'status_id',
            '{{%kpi_work_assignment_status}}',
            'id',
            'SET NULL'
        );

        // KHÓA NGOẠI KPI
        $this->addForeignKey(
            'fk_kpi_assignment_history_kpi',
            '{{%kpi_work_assignment_history}}',
            'kpi_id',
            '{{%kpi_kpi}}',
            'id',
            'SET NULL'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%kpi_work_assignment_history}}');
    }
}