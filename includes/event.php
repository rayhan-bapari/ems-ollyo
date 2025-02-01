<?php
class Event
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $sql = "INSERT INTO events (title, description, date, location, max_capacity, user_id, status, created_at)
                VALUES (:title, :description, :date, :location, :max_capacity, :user_id, :status, NOW())";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':title' => $data['title'],
                ':description' => $data['description'],
                ':date' => $data['date'],
                ':location' => $data['location'],
                ':max_capacity' => $data['max_capacity'],
                ':user_id' => $_SESSION['user_id'],
                ':status' => $data['status']
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating event: " . $e->getMessage());
            return false;
        }
    }

    public function update($event_id, $data)
    {
        $sql = "UPDATE events SET title = :title, description = :description, date = :date, location = :location, max_capacity = :max_capacity, status = :status
            WHERE id = :id AND user_id = :user_id";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':title' => $data['title'],
                ':description' => $data['description'],
                ':date' => $data['date'],
                ':location' => $data['location'],
                ':max_capacity' => $data['max_capacity'],
                ':status' => $data['status'],
                ':id' => $event_id,
                ':user_id' => $_SESSION['user_id']
            ]);

            return $stmt->rowCount() >= 0;
        } catch (PDOException $e) {
            error_log("Error updating event: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        $sql = "DELETE FROM events WHERE id = :id AND user_id = :user_id";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id, ':user_id' => $_SESSION['user_id']]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error deleting event: " . $e->getMessage());
            return false;
        }
    }


    public function getById($event_id)
    {
        $sql = "SELECT * FROM events WHERE id = :event_id";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':event_id' => $event_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching event: " . $e->getMessage());
            return false;
        }
    }

    public function getAllPaginated($page = 1, $perPage = 10, $filters = [])
    {
        $offset = ($page - 1) * $perPage;
        $where = "1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $where .= " AND (title LIKE :search OR description LIKE :search)";
            $params[':search'] = "%{$filters['search']}%";
        }

        if (!empty($filters['date'])) {
            $where .= " AND DATE(date) = :date";
            $params[':date'] = $filters['date'];
        }

        $sql = "SELECT * FROM events WHERE $where ORDER BY date DESC LIMIT :limit OFFSET :offset";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching events: " . $e->getMessage());
            return false;
        }
    }

    public function getTotalCount($filters = [])
    {
        $where = "1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $where .= " AND (title LIKE :search OR description LIKE :search)";
            $params[':search'] = "%{$filters['search']}%";
        }

        if (!empty($filters['date'])) {
            $where .= " AND DATE(date) = :date";
            $params[':date'] = $filters['date'];
        }

        $sql = "SELECT COUNT(*) FROM events WHERE $where";

        try {
            $stmt = $this->conn->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error fetching total events: " . $e->getMessage());
            return false;
        }
    }

    public function getAllWithAttendeeCount($filters = [])
    {
        $sql = "SELECT e.*, COUNT(a.id) as attendee_count
                FROM events e
                LEFT JOIN attendees a ON e.id = a.event_id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND e.title LIKE :search";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['start_date'])) {
            $sql .= " AND e.date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $sql .= " AND e.date <= :end_date";
            $params[':end_date'] = $filters['end_date'] . ' 23:59:59';
        }

        $sql .= " GROUP BY e.id ORDER BY e.date DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
