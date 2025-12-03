<?php

use yii\db\Migration;

/**
 * m251116_124852_insert_sample_data_all
 * Chèn dữ liệu mẫu test toàn bộ hệ thống KPI
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
        // 4️⃣ Tạo KPI + Formula
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
            $kpiId = (int)$db->getLastInsertID();
            $kpiIds[] = $kpiId;

            $this->insert('{{%kpi_formula}}', [
                'kpi_id' => $kpiId,
                'formula' => 'actual / target * weight',
                'description' => "Công thức tính KPI $kpi"
            ]);
        }

        // -------------------------------
        // 5️⃣ Work Registered + History
        // -------------------------------
        $workIds = [];
        foreach ($staffIds as $i => $staffId) {
            $kpiId = $kpiIds[array_rand($kpiIds)];
            $status = rand(1, 3);
            $startDate = "2025-12-" . str_pad($i+1,2,'0',STR_PAD_LEFT) . " 08:00:00";
            $endDate = "2025-12-" . str_pad($i+4,2,'0',STR_PAD_LEFT) . " 17:00:00";

            $this->insert('{{%kpi_work_registered}}', [
                'staff_id' => $staffId,
                'kpi_id' => $kpiId,
                'title' => "Công việc đăng ký ".($i+1),
                'description' => "Mô tả công việc ".($i+1),
                'status_id' => $status,
                'color' => '#00a65a',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'created_at' => $now,
                'updated_at' => $now
            ]);
            $workId = (int)$db->getLastInsertID();
            $workIds[] = $workId;

            // -------------------------------
            // Sửa: thêm staff_id để không vi phạm FK
            $this->insert('{{%kpi_work_registered_history}}', [
                'work_registered_id' => $workId,
                'staff_id' => $staffId,
                'title' => "Công việc đăng ký ".($i+1),
                'description' => "Mô tả công việc ".($i+1),
                'color' => '#00a65a',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'old_status' => null,
                'new_status' => $status,
                'old_data' => null,
                'new_data' => json_encode([
                    'staff_id' => $staffId,
                    'kpi_id' => $kpiId,
                    'title' => "Công việc đăng ký ".($i+1),
                    'description' => "Mô tả công việc ".($i+1),
                    'status_id' => $status,
                    'color' => '#00a65a',
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]),
                'action_type' => 'create',
                'updated_by' => $staffId,
                'created_at' => $now
            ]);
        }

        // -------------------------------
        // 6️⃣ Work Assignment + History
        // -------------------------------
        foreach ($workIds as $i => $workId) {
            $staffId = $staffIds[array_rand($staffIds)];
            $kpiId = $kpiIds[array_rand($kpiIds)];
            $status = rand(1,6);
            $startDate = "2025-12-" . str_pad($i+1,2,'0',STR_PAD_LEFT);
            $endDate = "2025-12-" . str_pad($i+4,2,'0',STR_PAD_LEFT);

            $this->insert('{{%kpi_work_assignment}}', [
                'work_registered_id' => $workId,
                'staff_id' => $staffId,
                'status_id' => $status,
                'assigned_at' => $now,
                'kpi_id' => $kpiId,
                'title' => "Công việc đăng ký ".($i+1),
                'description' => "Mô tả công việc ".($i+1),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'color' => '#3788d8'
            ]);
            $assignmentId = (int)$db->getLastInsertID();

            // Sửa: thêm staff_id để không vi phạm FK
            $this->insert('{{%kpi_work_assignment_history}}', [
                'work_assignment_id' => $assignmentId,
                'staff_id' => $staffId,
                'kpi_id' => $kpiId,
                'title' => "Công việc đăng ký ".($i+1),
                'description' => "Mô tả công việc ".($i+1),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'color' => '#3788d8',
                'old_status' => null,
                'new_status' => $status,
                'old_data' => null,
                'new_data' => json_encode([
                    'staff_id' => $staffId,
                    'kpi_id' => $kpiId,
                    'title' => "Công việc đăng ký ".($i+1),
                    'description' => "Mô tả công việc ".($i+1),
                    'status_id' => $status,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]),
                'action_type' => 'create',
                'updated_by' => $staffId,
                'assigned_at' => $now,
                'created_at' => $now
            ]);
        }

        // -------------------------------
        // 7️⃣ KPI Evaluation
        // -------------------------------
        foreach ($staffIds as $i => $staffId) {
            $this->insert('{{%kpi_kpi_evaluation}}', [
                'kpi_id' => $kpiIds[array_rand($kpiIds)],
                'staff_id' => $staffId,
                'score' => rand(50,100)/10,
                'comment' => "Nhận xét KPI ".($i+1),
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
                'month' => 12,
                'total_assignment' => rand(3,10),
                'total_completed' => rand(1,10),
                'kpi_score' => rand(60,100),
                'created_at' => $now
            ]);
        }
    }

    public function safeDown()
    {
        $this->delete('{{%kpi_summary}}');
        $this->delete('{{%kpi_kpi_evaluation}}');
        $this->delete('{{%kpi_work_assignment_history}}');
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
