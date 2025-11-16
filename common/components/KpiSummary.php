<?php
namespace app\components;

use Yii;
use yii\base\Component;

class KpiSummary extends Component
{
    public $viewName = 'kpi_summary_view';

    /**
     * Lấy summary KPI theo view, có thể lọc theo khoảng thời gian.
     *
     * @param string|null $startDate ngày bắt đầu lọc (YYYY-MM-DD)
     * @param string|null $endDate   ngày kết thúc lọc (YYYY-MM-DD)
     * @return array
     */
    public function getSummary($startDate = null, $endDate = null)
    {
        $db = Yii::$app->db;

        // Kiểm tra view đã tồn tại chưa
        $exists = $db->createCommand("
            SELECT COUNT(*) 
            FROM information_schema.VIEWS 
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :view
        ")->bindValue(':view', $this->viewName)->queryScalar();

        if (!$exists) {
            // Tạo view nếu chưa có
            $db->createCommand("
                CREATE VIEW `{$this->viewName}` AS
                SELECT 
                    e.id AS employee_id,
                    e.name AS employee_name,
                    d.id AS department_id,
                    d.name AS department_name,
                    COUNT(DISTINCT wr.id) AS total_registered,
                    COUNT(DISTINCT wa.id) AS total_assigned,
                    SUM(CASE WHEN wa.status = 2 THEN 1 ELSE 0 END) AS total_completed,
                    AVG(ke.score) AS average_score,
                    MIN(wr.date_start) AS date_start,
                    MAX(wr.date_end) AS date_end
                FROM employees e
                JOIN departments d ON e.department_id = d.id
                LEFT JOIN kpi_work_registered wr ON wr.employee_id = e.id
                LEFT JOIN kpi_work_assignment wa ON wa.work_registered_id = wr.id
                LEFT JOIN kpi_kpi_evaluation ke ON ke.employee_id = e.id
                GROUP BY e.id, e.name, d.id, d.name;
            ")->execute();
        }

        // Build query động theo khoảng thời gian
        $sql = "SELECT * FROM `{$this->viewName}` WHERE 1=1";

        if ($startDate) {
            $sql .= " AND date_end >= :startDate";
        }
        if ($endDate) {
            $sql .= " AND date_start <= :endDate";
        }

        $cmd = $db->createCommand($sql);
        if ($startDate) $cmd->bindValue(':startDate', $startDate);
        if ($endDate) $cmd->bindValue(':endDate', $endDate);

        return $cmd->queryAll();
    }
}

