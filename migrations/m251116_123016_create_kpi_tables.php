<?php

use yii\db\Migration;

/**
 * Class m251116_123016_create_kpi_tables
 *
 * Migration tạo toàn bộ các bảng liên quan hệ thống KPI:
 *
 * 0️⃣ Bảng trạng thái công việc đăng ký và phân công
 * 1️⃣ Bảng KPI và công thức
 * 2️⃣ Bảng công việc đăng ký và lịch sử
 * 3️⃣ Bảng phân công công việc và lịch công việc
 * 4️⃣ Bảng mối quan hệ KPI ↔ công việc
 * 5️⃣ Bảng báo cáo công việc (text)
 * 6️⃣ Bảng kết quả công việc (numeric)
 * 7️⃣ Bảng đánh giá KPI
 * 8️⃣ Bảng tổng hợp KPI (summary)
 *
 * Tất cả bảng sử dụng ENGINE=InnoDB và charset utf8mb4
 */
class m251116_123016_create_kpi_tables extends Migration
{
    /**
     * Tạo các bảng khi migrate up
     */
   public function safeUp()
    {
        // -------------------------------
        // 0️⃣ Bảng trạng thái công việc đăng ký
        // -------------------------------
        $this->createTable('{{%kpi_work_registered_status}}', [
            'id' => $this->primaryKey()->comment('ID trạng thái'),
            'name' => $this->string(50)->notNull()->comment('Tên trạng thái công việc đăng ký'),
            'description' => $this->string(255)->comment('Mô tả trạng thái công việc đăng ký'),
            'color' => $this->string(20)->null()->comment('Màu đại diện'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->batchInsert('{{%kpi_work_registered_status}}', ['id','name','description','color'], [
            [1, 'Chờ duyệt', 'Công việc vừa tạo, chờ phê duyệt', '#f39c12'], // màu cam
            [2, 'Duyệt', 'Công việc đã được phê duyệt', '#00a65a'],       // màu xanh lá
            [3, 'Từ chối', 'Công việc bị từ chối', '#dd4b39'],             // màu đỏ
        ]);

        // -------------------------------
        // 0b. Bảng trạng thái công việc phân công
        // -------------------------------
        $this->createTable('{{%kpi_work_assignment_status}}', [
            'id' => $this->primaryKey()->comment('ID trạng thái'),
            'name' => $this->string(50)->notNull()->comment('Tên trạng thái phân công'),
            'description' => $this->string(255)->comment('Mô tả trạng thái phân công'),
            'color' => $this->string(20)->null()->comment('Màu đại diện'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->batchInsert('{{%kpi_work_assignment_status}}', ['id','name','description','color'], [
            [1, 'Chưa hoàn thành', 'Công việc chưa xong', '#f39c12'],  // cam
            [2, 'Hoàn thành', 'Công việc đúng hạn', '#00a65a'],       // xanh lá
            [3, 'Trễ hạn', 'Công việc quá hạn', '#dd4b39'],           // đỏ
            [4, 'Đang thực hiện', 'Công việc đang làm', '#0073b7'],   // xanh dương
            [5, 'Tạm dừng', 'Công việc tạm dừng', '#f39c12'],         // cam nhạt
            [6, 'Bị hủy', 'Công việc bị hủy', '#808080'],             // xám
        ]);

        // -------------------------------
        // 1️⃣ Bảng KPI
        // -------------------------------
        $this->createTable('{{%kpi_kpi}}', [
            'id' => $this->primaryKey()->comment('ID KPI'),
            'code' => $this->string(50)->notNull()->unique()->comment('Mã KPI'),
            'name' => $this->string(255)->notNull()->comment('Tên KPI'),
            'unit' => $this->string(50)->comment('Đơn vị tính'),
            'target' => $this->decimal(10,2)->comment('Mục tiêu KPI'),
            'weight' => $this->decimal(5,2)->comment('Trọng số KPI'),
            'description' => $this->text()->comment('Mô tả KPI'),    
            'color' => $this->string(20)->null()->comment('Màu đại diện KPI'),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->comment('Ngày tạo'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Ngày cập nhật'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        // -------------------------------
        // 1b. Bảng công thức KPI
        // -------------------------------
        $this->createTable('{{%kpi_formula}}', [
            'id' => $this->primaryKey()->comment('ID công thức'),
            'kpi_id' => $this->integer()->notNull()->comment('ID KPI liên quan'),
            'formula' => $this->text()->notNull()->comment('Công thức tính KPI'),
            'description' => $this->string(255)->comment('Mô tả công thức'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->addForeignKey('fk_formula_kpi','{{%kpi_formula}}','kpi_id','{{%kpi_kpi}}','id','CASCADE');

        // -------------------------------
        // 2️⃣ Bảng công việc đăng ký
        // -------------------------------
        $this->createTable('{{%kpi_work_registered}}', [
            'id' => $this->primaryKey()->comment('ID công việc đăng ký'),
            'staff_id' => $this->integer()->notNull()->comment('ID nhân viên'),
            'kpi_id' => $this->integer()->comment('ID KPI liên quan'),
            'title' => $this->string(255)->notNull()->comment('Tiêu đề công việc'),
            'description' => $this->text()->comment('Mô tả công việc'),
            'status_id' => $this->integer()->notNull()->defaultValue(1)->comment('Trạng thái công việc'),
            'start_date' => $this->dateTime()->notNull()->comment('Ngày bắt đầu công việc'),
            'end_date' => $this->dateTime()->comment('Ngày kết thúc công việc'),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->comment('Ngày tạo'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('Ngày cập nhật'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->addForeignKey('fk_registered_staff','{{%kpi_work_registered}}','staff_id','{{%staff}}','staff_id','CASCADE');
        $this->addForeignKey('fk_registered_kpi','{{%kpi_work_registered}}','kpi_id','{{%kpi_kpi}}','id','CASCADE');
        $this->addForeignKey('fk_registered_status','{{%kpi_work_registered}}','status_id','{{%kpi_work_registered_status}}','id','CASCADE');

        // -------------------------------
        // 2b. Bảng lịch sử công việc đăng ký
        // -------------------------------
        $this->createTable('{{%kpi_work_registered_history}}', [
            'id' => $this->primaryKey()->comment('ID lịch sử'),
            'work_registered_id' => $this->integer()->notNull()->comment('ID công việc đăng ký'),
            'title' => $this->string(255)->notNull()->comment('Tiêu đề công việc'),
            'description' => $this->text()->comment('Mô tả công việc'),
            'start_date' => $this->dateTime()->notNull()->comment('Ngày bắt đầu công việc'),
            'end_date' => $this->dateTime()->comment('Ngày kết thúc công việc'),
            'action_type' => $this->string(20)->defaultValue('update')->comment('Loại hành động (create/update/delete)'),
            'updated_by' => $this->integer()->comment('ID người cập nhật'),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->comment('Ngày tạo bản ghi lịch sử'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->addForeignKey('fk_history_registered','{{%kpi_work_registered_history}}','work_registered_id','{{%kpi_work_registered}}','id','CASCADE');

        // -------------------------------
        // 3️⃣ Bảng phân công công việc
        // -------------------------------
        $this->createTable('{{%kpi_work_assignment}}', [
            'id' => $this->primaryKey()->comment('ID phân công'),
            'work_registered_id' => $this->integer()->notNull()->comment('ID công việc đăng ký'),
            'staff_id' => $this->integer()->notNull()->comment('ID nhân viên được phân công'),
            'status_id' => $this->integer()->notNull()->defaultValue(1)->comment('Trạng thái phân công'),
            
            // Lịch từ ngày → đến ngày
            'start_date' => $this->date()->notNull()->comment('Ngày bắt đầu'),
            'end_date' => $this->date()->comment('Ngày kết thúc'),

            'title' => $this->string(255)->notNull()->comment('Tiêu đề công việc'),
            'description' => $this->text()->comment('Mô tả công việc'),
            'color' => $this->string(20)->defaultValue('#3788d8')->comment('Màu lịch'),
                    
            'assigned_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->comment('Ngày phân công'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->addForeignKey('fk_assignment_registered','{{%kpi_work_assignment}}','work_registered_id','{{%kpi_work_registered}}','id','CASCADE');
        $this->addForeignKey('fk_assignment_staff','{{%kpi_work_assignment}}','staff_id','{{%staff}}','staff_id','CASCADE');
        $this->addForeignKey('fk_assignment_status','{{%kpi_work_assignment}}','status_id','{{%kpi_work_assignment_status}}','id','CASCADE');

        // -------------------------------
        // 3b. Bảng lịch công việc
        // -------------------------------
       /*  $this->createTable('{{%kpi_work_calendar}}', [
            'id' => $this->primaryKey()->comment('ID lịch'),
            'assignment_id' => $this->integer()->notNull()->comment('ID phân công'),
            'title' => $this->string(255)->notNull()->comment('Tiêu đề lịch'),
            'start_time' => $this->dateTime()->notNull()->comment('Ngày giờ bắt đầu'),
            'end_time' => $this->dateTime()->notNull()->comment('Ngày giờ kết thúc'),
            'color' => $this->string(20)->comment('Màu hiển thị lịch'),
            'is_all_day' => $this->boolean()->defaultValue(false)->comment('Cả ngày?'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->addForeignKey('fk_calendar_assignment','{{%kpi_work_calendar}}','assignment_id','{{%kpi_work_assignment}}','id','CASCADE');
 */
        // -------------------------------
        // 4️⃣ Bảng mối quan hệ KPI ↔ công việc
        // -------------------------------
        $this->createTable('{{%kpi_work_relation}}', [
            'id' => $this->primaryKey()->comment('ID quan hệ'),
            'kpi_id' => $this->integer()->notNull()->comment('ID KPI liên quan'),
            'assignment_id' => $this->integer()->notNull()->comment('ID phân công công việc'),
            'weight' => $this->decimal(5,2)->notNull()->comment('Trọng số KPI cho công việc'),
            'actual' => $this->decimal(10,2)->defaultValue(0)->comment('Kết quả thực tế'),
            'target' => $this->decimal(10,2)->defaultValue(0)->comment('Mục tiêu KPI'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->addForeignKey('fk_relation_kpi','{{%kpi_work_relation}}','kpi_id','{{%kpi_kpi}}','id','CASCADE');
        $this->addForeignKey('fk_relation_assignment','{{%kpi_work_relation}}','assignment_id','{{%kpi_work_assignment}}','id','CASCADE');

        // -------------------------------
        // 5️⃣ Bảng báo cáo công việc (text)
        // -------------------------------
        $this->createTable('{{%kpi_work_report}}', [
            'id' => $this->primaryKey()->comment('ID báo cáo'),
            'work_assignment_id' => $this->integer()->notNull()->comment('ID phân công công việc'),
            'content' => $this->text()->notNull()->comment('Nội dung báo cáo'),
            'reported_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('Ngày báo cáo'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->addForeignKey('fk_work_report_assignment','{{%kpi_work_report}}','work_assignment_id','{{%kpi_work_assignment}}','id','CASCADE');

        // -------------------------------
        // 6️⃣ Bảng kết quả công việc (numeric)
        // -------------------------------
        $this->createTable('{{%kpi_work_result}}', [
            'id' => $this->primaryKey()->comment('ID kết quả'),
            'work_assignment_id' => $this->integer()->notNull()->comment('ID phân công công việc'),
            'kpi_id' => $this->integer()->notNull()->comment('ID KPI liên quan'),
            'actual' => $this->decimal(10,2)->defaultValue(0)->comment('Kết quả thực tế'),
            'target' => $this->decimal(10,2)->defaultValue(0)->comment('Mục tiêu KPI'),
            'weight' => $this->decimal(5,2)->defaultValue(0)->comment('Trọng số KPI'),
            'score' => $this->decimal(10,2)->defaultValue(0)->comment('Điểm KPI'),
            'month' => $this->tinyInteger()->comment('Tháng'),
            'year' => $this->smallInteger()->comment('Năm'),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->comment('Ngày tạo kết quả'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->addForeignKey('fk_work_result_assignment','{{%kpi_work_result}}','work_assignment_id','{{%kpi_work_assignment}}','id','CASCADE');
        $this->addForeignKey('fk_work_result_kpi','{{%kpi_work_result}}','kpi_id','{{%kpi_kpi}}','id','CASCADE');

        // -------------------------------
        // 7️⃣ Bảng đánh giá KPI
        // -------------------------------
        $this->createTable('{{%kpi_kpi_evaluation}}', [
            'id' => $this->primaryKey()->comment('ID đánh giá'),
            'kpi_id' => $this->integer()->notNull()->comment('ID KPI'),
            'staff_id' => $this->integer()->notNull()->comment('ID nhân viên được đánh giá'),
            'score' => $this->decimal(10,2)->defaultValue(0)->comment('Điểm đánh giá KPI'),
            'comment' => $this->text()->comment('Nhận xét đánh giá'),
            'evaluated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->comment('Ngày đánh giá'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->addForeignKey('fk_eval_kpi','{{%kpi_kpi_evaluation}}','kpi_id','{{%kpi_kpi}}','id','CASCADE');
        $this->addForeignKey('fk_eval_staff','{{%kpi_kpi_evaluation}}','staff_id','{{%staff}}','staff_id','CASCADE');

        // -------------------------------
        // 8️⃣ Bảng tổng hợp KPI (summary)
        // -------------------------------
        $this->createTable('{{%kpi_summary}}', [
            'id' => $this->primaryKey()->comment('ID tổng hợp'),
            'staff_id' => $this->integer()->notNull()->comment('ID nhân viên'),
            'year' => $this->integer()->comment('Năm'),
            'month' => $this->integer()->comment('Tháng'),
            'total_assignment' => $this->integer()->defaultValue(0)->comment('Tổng số công việc phân công'),
            'total_completed' => $this->integer()->defaultValue(0)->comment('Tổng số công việc hoàn thành'),
            'kpi_score' => $this->decimal(10,2)->defaultValue(0)->comment('Tổng điểm KPI'),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->comment('Ngày tạo bản tổng hợp'),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->addForeignKey('fk_summary_staff','{{%kpi_summary}}','staff_id','{{%staff}}','staff_id','CASCADE');
        $this->createIndex('idx_summary_staff_month','{{%kpi_summary}}',['staff_id','year','month']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%kpi_summary}}');
        $this->dropTable('{{%kpi_kpi_evaluation}}');
        $this->dropTable('{{%kpi_work_result}}');
        $this->dropTable('{{%kpi_work_report}}');
        $this->dropTable('{{%kpi_work_relation}}');
        //$this->dropTable('{{%kpi_work_calendar}}');
        $this->dropTable('{{%kpi_work_assignment}}');
        $this->dropTable('{{%kpi_work_registered_history}}');
        $this->dropTable('{{%kpi_work_registered}}');
        $this->dropTable('{{%kpi_formula}}');
        $this->dropTable('{{%kpi_kpi}}');
        $this->dropTable('{{%kpi_work_assignment_status}}');
        $this->dropTable('{{%kpi_work_registered_status}}');
    }
}