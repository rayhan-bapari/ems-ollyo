<?php
class Auth
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function register($username, $email, $password)
    {
        try {
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);

            if ($stmt->rowCount() > 0) {
                return ["success" => false, "message" => "Username or email already exists"];
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password]);

            return ["success" => true, "message" => "Registration successful"];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Registration failed: " . $e->getMessage()];
        }
    }

    public function login($email, $password)
    {
        try {
            $stmt = $this->conn->prepare("SELECT id, username, password, email FROM users WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];

                    return ["success" => true, "message" => "Login successful"];
                }
            }

            return ["success" => false, "message" => "Invalid email or password"];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Login failed: " . $e->getMessage()];
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        return ["success" => true, "message" => "Logout successful"];
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public function getUserById($user_id)
    {
        $sql = "SELECT * FROM users WHERE id = :user_id";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':user_id' => $user_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching user: " . $e->getMessage());
            return false;
        }
    }
}
