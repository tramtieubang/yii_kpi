<?php

use yii\db\Migration;

/**
 * m251116_124852_insert_sample_data_all
 * Chèn dữ liệu mẫu test cho toàn bộ hệ thống KPI với user admin + nhân viên
 * Chèn dữ liệu mẫu test cho toàn bộ hệ thống KPI, bao gồm user, departments, employees, KPI, work, evaluation, report và summary
 */
class m251116_124852_insert_sample_data_all extends Migration
{
    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');

        /* ======================================================
         * 1. POSITIONS (10 record)
         * ====================================================== */
        $positions = [
            ['Giám đốc'], ['Phó Giám đốc'], ['Trưởng phòng'], ['Phó phòng'],
            ['Chuyên viên'], ['Nhân viên Kinh doanh'], ['Nhân viên IT'],
            ['Nhân viên HR'], ['Nhân viên Marketing'], ['Nhân viên Kế toán']
        ];

        foreach ($positions as $p) {
            $this->insert('positions', [
                'name' => $p[0],
                'description' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        /* ======================================================
         * 2. BUSINESS FIELDS (10 record)
         * ====================================================== */
        $fields = [
            'Công nghệ thông tin', 'Marketing', 'Bán hàng',
            'Logistics', 'Sản xuất', 'Tài chính', 'Nhân sự',
            'Dịch vụ khách hàng', 'R&D', 'Hành chính'
        ];
        foreach ($fields as $f) {
            $this->insert('business_fields', [
                'name' => $f,
                'description' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        /* ======================================================
         * 3. DEPARTMENTS (10 record)
         * ====================================================== */
        for ($i = 1; $i <= 10; $i++) {
            $this->insert('departments', [
                'code' => 'DP' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => 'Phòng ban ' . $i,
                'description' => null,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        /* ======================================================
         * 4. EMPLOYEES (10 record)
         * user_id: bạn cần phải có sẵn user từ 1..10 trong bảng user
         * ====================================================== */
        for ($i = 1; $i <= 10; $i++) {
            $this->insert('employees', [
                'user_id' => $i,
                'department_id' => rand(1, 10),
                'position_id' => rand(1, 10),
                'business_field_id' => rand(1, 10),
                'name' => "Nhân viên $i",
                'email' => "employee$i@example.com",
                'phone' => "09010000" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'hire_date' => "2020-01-" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        /* ======================================================
         * 5. KPI List (kpi_kpi) 10 record
         * ====================================================== */
        $kpis = [
            'Doanh số', 'CSKH', 'IT Support', 'Marketing',
            'HR tuyển dụng', 'Sản xuất', 'R&D nghiên cứu',
            'Logistics giao hàng', 'Tài chính', 'Hành chính'
        ];

        foreach ($kpis as $index => $kpi) {
            $this->insert('kpi_kpi', [
                'code' => 'KPI' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'name' => $kpi,
                'description' => null,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        /* ======================================================
         * 6. KPI_WORK_REGISTERED (10 record)
         * ====================================================== */
        for ($i = 1; $i <= 10; $i++) {
            $this->insert('kpi_work_registered', [
                'employee_id' => rand(1, 10),
                'kpi_id' => rand(1, 10),
                'title' => "Công việc đăng ký $i",
                'description' => "Mô tả công việc $i",
                'status' => rand(0, 2),
                'date_start' => "2025-01-" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'date_end' => "2025-01-" . str_pad(($i + 5), 2, '0', STR_PAD_LEFT),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        /* ======================================================
         * 7. KPI_WORK_ASSIGNMENT (10 record)
         * ====================================================== */
        for ($i = 1; $i <= 10; $i++) {
            $this->insert('kpi_work_assignment', [
                'work_registered_id' => $i,
                'employee_id' => rand(1, 10),
                'assigned_at' => $now,
                'status' => rand(0, 2),
            ]);
        }

        /* ======================================================
         * 8. KPI_EVALUATION (10 record)
         * ====================================================== */
        for ($i = 1; $i <= 10; $i++) {
            $this->insert('kpi_kpi_evaluation', [
                'kpi_id' => rand(1, 10),
                'employee_id' => rand(1, 10),
                'score' => rand(50, 100) / 10,
                'comment' => "Nhận xét $i",
                'evaluated_at' => $now,
            ]);
        }

        /* ======================================================
         * 9. KPI_WORK_REPORT (10 record)
         * ====================================================== */
        for ($i = 1; $i <= 10; $i++) {
            $this->insert('kpi_work_report', [
                'work_assignment_id' => $i,
                'content' => "Nội dung báo cáo $i",
                'reported_at' => $now,
            ]);
        }

        /* ======================================================
         * 10. KPI_SUMMARY (10 record)
         * ====================================================== */
        for ($i = 1; $i <= 10; $i++) {
            $this->insert('kpi_summary', [
                'employee_id' => $i,
                'total_registered' => rand(3, 10),
                'total_assigned' => rand(3, 10),
                'total_completed' => rand(1, 10),
                'average_score' => rand(60, 100) / 10,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }
    }

    public function safeDown()
    {
        $this->delete('kpi_summary');
        $this->delete('kpi_work_report');
        $this->delete('kpi_kpi_evaluation');
        $this->delete('kpi_work_assignment');
        $this->delete('kpi_work_registered');
        $this->delete('kpi_kpi');
        $this->delete('employees');
        $this->delete('departments');
        $this->delete('business_fields');
        $this->delete('positions');
    }
}
