<?php

require_once 'global.php';


class Delete extends GlobalMethods
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Add a new employee with the provided data.
     *
     * @param $Id
     *   id of student the admin wants to delete
     */

    public function delete_student($data)
    {
        header('Content-Type: application/json');

        // Extract student ID from the request data
        $id = $data->id;
        $is_admin = $data->is_admin;

        try {
            if (!$is_admin) {
                return array(
                    "error" => "Action is not allowed"
                );
            }
            // Check if the student exists
            $sql = "SELECT * FROM students 
                     WHERE studentID = :id AND is_admin = 0  
                     LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            // validation
            if (!$student) {
                return array(
                    "error" => "Student not found"
                );
            }

            // If student exists, proceed with deletion
            $sql_delete = "DELETE FROM students WHERE studentID = :id";
            $stmt_delete = $this->pdo->prepare($sql_delete);
            $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_delete->execute();

            return array(
                "message" => "Student deleted successfully"
            );
        } catch (\PDOException $e) {
            // Handle database errors
            $errmsg = $e->getMessage();
            $code = 400;
            // You might want to log the error for debugging purposes
            // Log::error("Database Error: " . $errmsg);
            // Return an error response
            $error = array(
                "error" => "Database error"
            );
            return $error;
        }
    }
}
