<?php

use yii\db\Migration;

/**
 * Class m251116_135500_insert_sample_kpi_users
 * Chèn dữ liệu mẫu test cho toàn bộ hệ thống KPI với user admin + nhân viên
 */

class m251116_124852_insert_sample_data_all extends Migration
{
    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');

        // -------------------------------
        // 1. Bảng user
        // -------------------------------
        $users = [
            ['admin','admin@example.com',1],
            ['user2','user2@example.com',0],
            ['user3','user3@example.com',0],
            ['user4','user4@example.com',0],
            ['user5','user5@example.com',0],
            ['user6','user6@example.com',0],
            ['user7','user7@example.com',0],
            ['user8','user8@example.com',0],
            ['user9','user9@example.com',0],
            ['user10','user10@example.com',0],
        ];

        foreach ($users as $i => $u) {
            $this->insert('{{%user}}', [
                'username' => $u[0],
                'auth_key' => Yii::$app->security->generateRandomString(),
                'password_hash' => Yii::$app->security->generatePasswordHash('123456'),
                'confirmation_token' => 'token'.$i,
                'status' => 10,
                'superadmin' => $u[2],
                'created_at' => $now,
                'updated_at' => $now,
                'registration_ip' => '127.0.0.'.($i+1),
                'bind_to_ip' => null,
                'email' => $u[1],
                'email_confirmed' => 1,
            ]);
        }

        // -------------------------------
        // 2. Bảng departments
        // -------------------------------
        $departments = [
            ['DPT001','Phòng Hành chính'],
            ['DPT002','Phòng Kinh doanh'],
            ['DPT003','Phòng IT'],
            ['DPT004','Phòng Nhân sự'],
            ['DPT005','Phòng Marketing'],
            ['DPT006','Phòng Tài chính'],
            ['DPT007','Phòng Sản xuất'],
            ['DPT008','Phòng Nghiên cứu'],
            ['DPT009','Phòng CSKH'],
            ['DPT010','Phòng Logistic'],
        ];

        foreach ($departments as $d) {
            $this->insert('{{%departments}}', [
                'code' => $d[0],
                'name' => $d[1],
                'description' => $d[1],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // -------------------------------
        // 3. Bảng employees
        // -------------------------------
        $employees = [
            ['Nguyễn Văn A',1,'0901000001','Trưởng phòng','2020-01-01'],
            ['Trần Thị B',2,'0901000002','Nhân viên kinh doanh','2021-03-15'],
            ['Lê Văn C',3,'0901000003','Chuyên viên IT','2019-05-20'],
            ['Phạm Thị D',4,'0901000004','Nhân viên HR','2020-07-10'],
            ['Vũ Văn E',5,'0901000005','Nhân viên Marketing','2022-02-01'],
            ['Đinh Thị F',6,'0901000006','Kế toán','2021-09-15'],
            ['Ngô Văn G',7,'0901000007','Nhân viên sản xuất','2018-12-01'],
            ['Bùi Thị H',8,'0901000008','Chuyên viên R&D','2022-06-10'],
            ['Lý Văn I',9,'0901000009','Nhân viên CSKH','2020-11-05'],
            ['Trương Thị J',10,'0901000010','Nhân viên Logistic','2021-08-20'],
        ];

        foreach ($employees as $i => $e) {
            $this->insert('{{%employees}}', [
                'user_id' => $i+1,
                'department_id' => $e[1],
                'name' => $e[0],
                'email' => strtolower(str_replace(' ','',$e[0])).'@example.com',
                'phone' => $e[2],
                'position' => $e[3],
                'hire_date' => $e[4],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // -------------------------------
        // 4. Bảng kpi_kpi
        // -------------------------------
        $kpis = [
            'KPI Doanh số',
            'KPI CSKH',
            'KPI IT',
            'KPI Marketing',
            'KPI HR',
            'KPI Sản xuất',
            'KPI R&D',
            'KPI Logistic',
            'KPI Tài chính',
            'KPI Hành chính',
        ];

        foreach ($kpis as $k) {
            $this->insert('{{%kpi_kpi}}', [
                'name' => $k,
                'description' => $k,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // -------------------------------
        // 5. Bảng kpi_work_registered
        // -------------------------------
        for($i=1;$i<=10;$i++){
            $this->insert('{{%kpi_work_registered}}', [
                'employee_id'=>$i,
                'kpi_id'=>$i,
                'title'=>'Công việc mẫu '.$i,
                'description'=>'Mô tả công việc mẫu '.$i,
                'status'=>rand(0,2),
                'date_start'=>date('Y-m-d',strtotime("-10 days +$i day")),
                'date_end'=>date('Y-m-d',strtotime("+20 days +$i day")),
                'created_at'=>$now,
                'updated_at'=>$now,
            ]);
        }

        // -------------------------------
        // 6. Bảng kpi_work_assignment
        // -------------------------------
        for($i=1;$i<=10;$i++){
            $this->insert('{{%kpi_work_assignment}}', [
                'work_registered_id'=>$i,
                'employee_id'=>$i,
                'assigned_at'=>$now,
                'status'=>rand(0,2),
            ]);
        }

        // -------------------------------
        // 7. Bảng kpi_kpi_evaluation
        // -------------------------------
        for($i=1;$i<=10;$i++){
            $this->insert('{{%kpi_kpi_evaluation}}', [
                'kpi_id'=>$i,
                'employee_id'=>$i,
                'score'=>rand(70,100),
                'comment'=>'Đánh giá mẫu '.$i,
                'evaluated_at'=>$now,
            ]);
        }

        // -------------------------------
        // 8. Bảng kpi_work_report
        // -------------------------------
        for($i=1;$i<=10;$i++){
            $this->insert('{{%kpi_work_report}}', [
                'work_assignment_id'=>$i,
                'content'=>'Báo cáo công việc mẫu '.$i,
                'reported_at'=>$now,
            ]);
        }
    }

    public function safeDown()
    {
        $this->truncateTable('{{%kpi_work_report}}');
        $this->truncateTable('{{%kpi_kpi_evaluation}}');
        $this->truncateTable('{{%kpi_work_assignment}}');
        $this->truncateTable('{{%kpi_work_registered}}');
        $this->truncateTable('{{%kpi_kpi}}');
        $this->truncateTable('{{%employees}}');
        $this->truncateTable('{{%departments}}');
        $this->delete('{{%user}}', ['username'=>['admin','user2','user3','user4','user5','user6','user7','user8','user9','user10']]);
    }
}
