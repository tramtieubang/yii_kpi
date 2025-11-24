<?php

use yii\db\Migration;

/**
 * m251116_124852_insert_sample_data_all
 * Chèn dữ liệu mẫu test toàn bộ hệ thống KPI, bao gồm positions, business_fields, department, staff, KPI, work, evaluation, report, summary
 */
class m251116_124852_insert_sample_data_all extends Migration
{
    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $db = Yii::$app->db;

        // -------------------------------
        // 0️⃣ Tạo dữ liệu cho positions
        // -------------------------------
        $positions = ['Giám đốc', 'Trưởng phòng', 'Nhân viên', 'Thực tập sinh'];
        $positionIds = [];
        foreach ($positions as $name) {
            $this->insert('{{%positions}}', [
                'name' => $name,
                'description' => "Mô tả $name",
                'created_at' => $now,
                'updated_at' => $now
            ]);
            $positionIds[] = (int)$db->getLastInsertID();
        }

        // -------------------------------
        // 1️⃣ Tạo dữ liệu cho business_fields
        // -------------------------------
        $fields = ['Sales', 'IT', 'Marketing', 'HR', 'Sản xuất', 'R&D', 'Logistics', 'Tài chính', 'Hành chính'];
        $fieldIds = [];
        foreach ($fields as $name) {
            $this->insert('{{%business_fields}}', [
                'name' => $name,
                'description' => "Mô tả lĩnh vực $name",
                'created_at' => $now,
                'updated_at' => $now
            ]);
            $fieldIds[] = (int)$db->getLastInsertID();
        }

        // -------------------------------
        // 2️⃣ Tạo dữ liệu cho department
        // -------------------------------
        $departments = ['Phòng Kinh doanh','Phòng IT','Phòng Marketing','Phòng Nhân sự','Phòng Sản xuất'];
        $departmentIds = [];
        foreach ($departments as $i => $name) {
            $this->insert('{{%department}}', [
                'name' => $name,
                'code' => 'DEP' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'description' => "Mô tả $name"
            ]);
            $departmentIds[] = (int)$db->getLastInsertID();
        }

        // -------------------------------
        // 3️⃣ Tạo dữ liệu cho staff
        // -------------------------------
        $staffIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $this->insert('{{%staff}}', [
                'department_id' => $departmentIds[array_rand($departmentIds)],
                'position_id' => $positionIds[array_rand($positionIds)],
                'business_field_id' => $fieldIds[array_rand($fieldIds)],
                'name' => "Nhân viên $i",
                'email' => "staff$i@example.com",
                'phone' => '09' . rand(10000000, 99999999),
                'hire_date' => "2023-01-" . str_pad(rand(1,28),2,'0',STR_PAD_LEFT),
                'created_at' => $now,
                'updated_at' => $now
            ]);
            $staffIds[] = (int)$db->getLastInsertID();
        }

        // -------------------------------
        // 4️⃣ Chèn KPI và Formula
        // -------------------------------
        $kpis = ['Doanh số','CSKH','IT Support','Marketing','HR tuyển dụng','Sản xuất','R&D nghiên cứu','Logistics','Tài chính','Hành chính'];
        $kpiIds = [];
        foreach ($kpis as $index => $kpi) {
            $this->insert('{{%kpi_kpi}}', [
                'code' => 'KPI' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'name' => $kpi,
                'unit' => 'Đơn vị',
                'target' => rand(50, 100),
                'weight' => rand(1, 10),
                'description' => "Mô tả KPI $kpi",
                'created_at' => $now,
                'updated_at' => $now
            ]);
            $kpiIds[] = (int)$db->getLastInsertID();

            // Formula
            $this->insert('{{%kpi_formula}}', [
                'kpi_id' => $kpiIds[$index],
                'formula' => 'actual / target * weight',
                'description' => "Công thức tính KPI $kpi"
            ]);
        }

        // -------------------------------
        // 5️⃣ Work Registered + History
        // -------------------------------
        $workIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $staffId = $staffIds[array_rand($staffIds)];
            $kpiId = $kpiIds[array_rand($kpiIds)];

            $this->insert('{{%kpi_work_registered}}', [
                'staff_id' => $staffId,
                'kpi_id' => $kpiId,
                'title' => "Công việc đăng ký $i",
                'description' => "Mô tả công việc $i",
                'status_id' => rand(1, 3),
                'date_start' => "2025-11-" . str_pad($i, 2, '0', STR_PAD_LEFT) . " 08:00:00",
                'date_end' => "2025-11-" . str_pad($i + 3, 2, '0', STR_PAD_LEFT) . " 17:00:00",
                'created_at' => $now,
                'updated_at' => $now
            ]);
            $workId = (int)$db->getLastInsertID();
            $workIds[] = $workId;

            $this->insert('{{%kpi_work_registered_history}}', [
                'work_registered_id' => $workId,
                'title' => "Công việc đăng ký $i",
                'description' => "Mô tả công việc $i",
                'date_start' => "2025-11-" . str_pad($i, 2, '0', STR_PAD_LEFT) . " 08:00:00",
                'date_end' => "2025-11-" . str_pad($i + 3, 2, '0', STR_PAD_LEFT) . " 17:00:00",
                'action_type' => 'create',
                'updated_by' => $staffId,
                'created_at' => $now
            ]);
        }

        // -------------------------------
        // 6️⃣ Work Assignment + Calendar + Relation + Work Report + Work Result
        // -------------------------------
        $assignmentIds = [];
        foreach ($workIds as $i => $workId) {
            $staffId = $staffIds[array_rand($staffIds)];

            // Assignment
            $this->insert('{{%kpi_work_assignment}}', [
                'work_registered_id' => $workId,
                'staff_id' => $staffId,
                'status_id' => rand(1, 6),
                'assigned_at' => $now
            ]);
            $assignmentId = (int)$db->getLastInsertID();
            $assignmentIds[] = $assignmentId;

            // Calendar
            $this->insert('{{%kpi_work_calendar}}', [
                'assignment_id' => $assignmentId,
                'title' => "Công việc chính thức " . ($i + 1),
                'start_time' => "2025-11-" . str_pad($i + 1, 2, '0', STR_PAD_LEFT) . " 08:00:00",
                'end_time' => "2025-11-" . str_pad($i + 4, 2, '0', STR_PAD_LEFT) . " 17:00:00",
                'color' => '#00FF00',
                'is_all_day' => false
            ]);

            // Work Relation
            $this->insert('{{%kpi_work_relation}}', [
                'kpi_id' => $kpiIds[array_rand($kpiIds)],
                'assignment_id' => $assignmentId,
                'weight' => rand(1, 10),
                'target' => rand(50, 100),
                'actual' => rand(10, 100)
            ]);

            // KPI Work Report
            $this->insert('{{%kpi_work_report}}', [
                'work_assignment_id' => $assignmentId,
                'content' => "Nội dung báo cáo công việc " . ($i + 1),
                'reported_at' => $now
            ]);

            // KPI Work Result
            $this->insert('{{%kpi_work_result}}', [
                'work_assignment_id' => $assignmentId,
                'kpi_id' => $kpiIds[array_rand($kpiIds)],
                'actual' => rand(10, 100),
                'target' => rand(50, 100),
                'weight' => rand(1, 10),
                'score' => rand(50, 100),
                'month' => 11,
                'year' => 2025,
                'created_at' => $now
            ]);
        }

        // -------------------------------
        // 7️⃣ KPI Evaluation
        // -------------------------------
        foreach (range(1, 10) as $i) {
            $this->insert('{{%kpi_kpi_evaluation}}', [
                'kpi_id' => $kpiIds[array_rand($kpiIds)],
                'staff_id' => $staffIds[array_rand($staffIds)],
                'score' => rand(50, 100) / 10,
                'comment' => "Nhận xét KPI $i",
                'evaluated_at' => $now
            ]);
        }

        // -------------------------------
        // 8️⃣ KPI Summary
        // -------------------------------
        foreach ($staffIds as $staffId) {
            $this->insert('{{%kpi_summary}}', [
                'staff_id' => $staffId,
                'year' => 2025,
                'month' => 11,
                'total_assignment' => rand(3, 10),
                'total_completed' => rand(1, 10),
                'kpi_score' => rand(60, 100),
                'created_at' => $now
            ]);
        }
    }

    public function safeDown()
    {
        $this->delete('{{%kpi_summary}}');
        $this->delete('{{%kpi_work_result}}');
        $this->delete('{{%kpi_work_report}}');
        $this->delete('{{%kpi_kpi_evaluation}}');
        $this->delete('{{%kpi_work_relation}}');
        $this->delete('{{%kpi_work_calendar}}');
        $this->delete('{{%kpi_work_assignment}}');
        $this->delete('{{%kpi_work_registered_history}}');
        $this->delete('{{%kpi_work_registered}}');
        $this->delete('{{%kpi_formula}}');
        $this->delete('{{%kpi_kpi}}');
        $this->delete('{{%staff}}');
        $this->delete('{{%department}}');
        $this->delete('{{%business_fields}}');
        $this->delete('{{%positions}}');
    }
}
