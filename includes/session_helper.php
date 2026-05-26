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
        
        // Self-Healing: Automatically create the sessions table if it is missing!
        if ($this->db instanceof mysqli) {
            try {
                $this->db->query("CREATE TABLE IF NOT EXISTS `sessions` (
                    `id` varchar(255) NOT NULL,
                    `access` int(11) NOT NULL,
                    `data` text NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
            } catch (\Exception $e) {
                // Ignore failure during auto-creation (will rely on try-catch in read/write)
            }
        }
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
        
        try {
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
        } catch (\Exception $e) {
            // Gracefully ignore database session read failures (e.g. if db is temporarily offline)
        }
        return '';
    }

    #[\ReturnTypeWillChange]
    public function write(string $id, string $data): bool {
        if (!$this->db) {
            return false;
        }

        try {
            $access = time();
            $stmt = $this->db->prepare("REPLACE INTO sessions (id, access, data) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sis", $id, $access, $data);
                $result = $stmt->execute();
                $stmt->close();
                return $result;
            }
        } catch (\Exception $e) {
            // Gracefully ignore database session write failures
        }
        return false;
    }

    #[\ReturnTypeWillChange]
    public function destroy(string $id): bool {
        if (!$this->db) {
            return false;
        }

        try {
            $stmt = $this->db->prepare("DELETE FROM sessions WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("s", $id);
                $result = $stmt->execute();
                $stmt->close();
                return $result;
            }
        } catch (\Exception $e) {
            // Gracefully ignore database session destroy failures
        }
        return false;
    }

    #[\ReturnTypeWillChange]
    public function gc(int $maxlifetime): int|false {
        if (!$this->db) {
            return false;
        }

        try {
            $old = time() - $maxlifetime;
            $stmt = $this->db->prepare("DELETE FROM sessions WHERE access < ?");
            if ($stmt) {
                $stmt->bind_param("i", $old);
                $result = $stmt->execute();
                $stmt->close();
                return $result;
            }
        } catch (\Exception $e) {
            // Gracefully ignore database session gc failures
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
