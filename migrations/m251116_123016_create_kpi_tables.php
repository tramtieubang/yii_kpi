<?php

use yii\db\Migration;

/**
 * Class m251116_123016_create_kpi_tables
 * Migration tạo các bảng KPI đầy đủ với foreign key, comment, index tối ưu
 * Migration tạo các bảng KPI với DATETIME cho các cột thời gian
 * php yii migrate/create create_kpi_tables
  * Migration tạo các bảng KPI đầy đủ với foreign key, comment, index tối ưu
 * Bao gồm bảng tổng hợp kpi_summary
 */
class m251116_123016_create_kpi_tables extends Migration
{
    public function safeUp()
    {
        $nowExpression = new \yii\db\Expression('CURRENT_TIMESTAMP');
       
        // -------------------------------
        // 1. Bảng kpi_kpi
        // -------------------------------
        $this->createTable('{{%kpi_kpi}}', [
            'id' => $this->primaryKey()->comment('ID KPI'),
            'code' => $this->string(50)->notNull()->unique()->comment('Mã KPI'),
            'name' => $this->string(255)->notNull()->comment('Tên KPI'),            
            'description' => $this->text()->comment('Mô tả KPI'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Thời gian tạo'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Thời gian cập nhật'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT="Bảng KPI"');

        // -------------------------------
        // 2. Bảng kpi_work_registered
        // -------------------------------
        $this->createTable('{{%kpi_work_registered}}', [
            'id' => $this->primaryKey()->comment('ID đăng ký công việc'),
            'employee_id' => $this->integer()->notNull()->comment('ID nhân viên đăng ký'),
            'kpi_id' => $this->integer()->notNull()->comment('ID KPI liên quan'),
            'title' => $this->string(255)->notNull()->comment('Tiêu đề công việc'),
            'description' => $this->text()->comment('Mô tả công việc'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Trạng thái (0: chờ duyệt, 1: duyệt, 2: từ chối)'),
            'date_start' => $this->date()->notNull()->comment('Ngày bắt đầu'),
            'date_end' => $this->date()->comment('Ngày kết thúc dự kiến'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Thời gian tạo'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Thời gian cập nhật'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT="Bảng đăng ký công việc theo KPI"');

        $this->addForeignKey('fk_work_registered_employee', '{{%kpi_work_registered}}', 'employee_id', '{{%employees}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_work_registered_kpi', '{{%kpi_work_registered}}', 'kpi_id', '{{%kpi_kpi}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_work_registered_emp_kpi_status_start', '{{%kpi_work_registered}}', ['employee_id','kpi_id','status','date_start']);

        // -------------------------------
        // 3. Bảng kpi_work_assignment
        // -------------------------------
        $this->createTable('{{%kpi_work_assignment}}', [
            'id' => $this->primaryKey()->comment('ID phân công công việc'),
            'work_registered_id' => $this->integer()->notNull()->comment('ID công việc đăng ký'),
            'employee_id' => $this->integer()->notNull()->comment('ID nhân viên được phân công'),
            'assigned_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Thời gian phân công'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Trạng thái công việc (0: chờ làm, 1: đang làm, 2: hoàn thành)'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT="Bảng phân công công việc"');

        $this->addForeignKey('fk_work_assignment_registered', '{{%kpi_work_assignment}}', 'work_registered_id', '{{%kpi_work_registered}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_work_assignment_employee', '{{%kpi_work_assignment}}', 'employee_id', '{{%employees}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_work_assignment_emp_status', '{{%kpi_work_assignment}}', ['employee_id','status']);

        // -------------------------------
        // 4. Bảng kpi_kpi_evaluation
        // -------------------------------
        $this->createTable('{{%kpi_kpi_evaluation}}', [
            'id' => $this->primaryKey()->comment('ID đánh giá KPI'),
            'kpi_id' => $this->integer()->notNull()->comment('ID KPI được đánh giá'),
            'employee_id' => $this->integer()->notNull()->comment('ID nhân viên được đánh giá'),
            'score' => $this->decimal(5,2)->notNull()->comment('Điểm đánh giá'),
            'comment' => $this->text()->comment('Nhận xét đánh giá'),
            'evaluated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Thời gian đánh giá'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT="Bảng đánh giá KPI"');

        $this->addForeignKey('fk_evaluation_kpi', '{{%kpi_kpi_evaluation}}', 'kpi_id', '{{%kpi_kpi}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_evaluation_employee', '{{%kpi_kpi_evaluation}}', 'employee_id', '{{%employees}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_evaluation_emp_kpi', '{{%kpi_kpi_evaluation}}', ['employee_id','kpi_id']);

        // -------------------------------
        // 5. Bảng kpi_work_report
        // -------------------------------
        $this->createTable('{{%kpi_work_report}}', [
            'id' => $this->primaryKey()->comment('ID báo cáo công việc'),
            'work_assignment_id' => $this->integer()->notNull()->comment('ID phân công công việc'),
            'content' => $this->text()->comment('Nội dung báo cáo'),
            'reported_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Thời gian báo cáo'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT="Bảng báo cáo công việc"');

        $this->addForeignKey('fk_work_report_assignment', '{{%kpi_work_report}}', 'work_assignment_id', '{{%kpi_work_assignment}}', 'id', 'CASCADE', 'CASCADE');

        // -------------------------------
        // 6. Bảng kpi_summary
        // -------------------------------
        $this->createTable('{{%kpi_summary}}', [
            'id' => $this->primaryKey(),
            'employee_id' => $this->integer()->notNull()->comment('ID nhân viên'),
            'total_registered' => $this->integer()->defaultValue(0)->comment('Tổng công việc đăng ký'),
            'total_assigned' => $this->integer()->defaultValue(0)->comment('Tổng công việc phân công'),
            'total_completed' => $this->integer()->defaultValue(0)->comment('Tổng công việc hoàn thành'),
            'average_score' => $this->decimal(5,2)->defaultValue(0)->comment('Điểm KPI trung bình'),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT="Bảng tổng hợp KPI"');

        $this->addForeignKey('fk_summary_employee', '{{%kpi_summary}}', 'employee_id', '{{%employees}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_summary_employee', '{{%kpi_summary}}', ['employee_id']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%kpi_summary}}');
        $this->dropTable('{{%kpi_work_report}}');
        $this->dropTable('{{%kpi_kpi_evaluation}}');
        $this->dropTable('{{%kpi_work_assignment}}');
        $this->dropTable('{{%kpi_work_registered}}');
        $this->dropTable('{{%kpi_kpi}}');
        $this->dropTable('{{%employees}}');
        $this->dropTable('{{%departments}}');
    }
}
