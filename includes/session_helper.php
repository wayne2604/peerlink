<?php
/**
 * Database Session Handler
 * Stores PHP session data in the MySQL database to ensure persistence in serverless environments (like Vercel).
 */

// Ensure we have the database connection available
require_once __DIR__ . '/db_connect.php';

class DatabaseSessionHandler implements SessionHandlerInterface {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    #[\ReturnTypeWillChange]
    public function open(string $savePath, string $sessionName): bool {
        return true;
    }

    #[\ReturnTypeWillChange]
    public function close(): bool {
        return true;
    }

    #[\ReturnTypeWillChange]
    public function read(string $id): string|false {
        if (!$this->db) {
            return '';
        }
        
        $stmt = $this->db->prepare("SELECT data FROM sessions WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                $stmt->close();
                return $row['data'];
            }
            $stmt->close();
        }
        return '';
    }

    #[\ReturnTypeWillChange]
    public function write(string $id, string $data): bool {
        if (!$this->db) {
            return false;
        }

        $access = time();
        $stmt = $this->db->prepare("REPLACE INTO sessions (id, access, data) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sis", $id, $access, $data);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }

    #[\ReturnTypeWillChange]
    public function destroy(string $id): bool {
        if (!$this->db) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM sessions WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("s", $id);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }

    #[\ReturnTypeWillChange]
    public function gc(int $maxlifetime): int|false {
        if (!$this->db) {
            return false;
        }

        $old = time() - $maxlifetime;
        $stmt = $this->db->prepare("DELETE FROM sessions WHERE access < ?");
        if ($stmt) {
            $stmt->bind_param("i", $old);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }
}

// Register the handler with the global connection object $conn defined in db_connect.php
if (isset($conn) && $conn instanceof mysqli) {
    $handler = new DatabaseSessionHandler($conn);
    session_set_save_handler($handler, true);
}
?>
