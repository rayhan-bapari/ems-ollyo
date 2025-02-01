<?php
class Attendee
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function register($data)
    {
        $capacityCheck = "SELECT e.max_capacity, COUNT(a.id) as current_attendees
                         FROM events e
                         LEFT JOIN attendees a ON e.id = a.event_id
                         WHERE e.id = :event_id
                         GROUP BY e.id, e.max_capacity";

        $stmt = $this->conn->prepare($capacityCheck);
        $stmt->execute([':event_id' => $data['event_id']]);
        $eventCapacity = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($eventCapacity && $eventCapacity['current_attendees'] >= $eventCapacity['max_capacity']) {
            return ['success' => false, 'message' => 'Event has reached maximum capacity'];
        }

        $checkDuplicate = "SELECT id FROM attendees WHERE email = :email AND event_id = :event_id";
        $stmt = $this->conn->prepare($checkDuplicate);
        $stmt->execute([
            ':email' => $data['email'],
            ':event_id' => $data['event_id']
        ]);

        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'You are already registered for this event'];
        }

        $sql = "INSERT INTO attendees (event_id, name, email, phone, registration_date, created_at)
                VALUES (:event_id, :name, :email, :phone, NOW(), Now())";

        try {
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':event_id' => $data['event_id'],
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':phone' => $data['phone']
            ]);

            if ($result) {
                return ['success' => true, 'message' => 'Registration successful'];
            } else {
                return ['success' => false, 'message' => 'Registration failed'];
            }
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Registration failed'];
        }
    }

    public function getEventAttendees($event_id)
    {
        $sql = "SELECT * FROM attendees WHERE event_id = :event_id ORDER BY registration_date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':event_id' => $event_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateAttendeeReport($event_id)
    {
        $sql = "SELECT a.*, e.title as event_name
                FROM attendees a
                JOIN events e ON a.event_id = e.id
                WHERE a.event_id = :event_id
                ORDER BY a.registration_date";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':event_id' => $event_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function getTotalAttendees()
    {
        $sql = "SELECT COUNT(id) as total_attendees FROM attendees";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_attendees'];
    }
}