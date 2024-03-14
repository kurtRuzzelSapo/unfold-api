<?php
require_once "global.php";

class Get extends GlobalMethods
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function executeQuery($sql)
    {
        $data = array(); //place to store records retrieved for db
        $errmsg = ""; //initialized error message variable
        $code = 0; //initialize status code variable

        try {
            if ($result = $this->pdo->query($sql)->fetchAll()) { //retrieved records from db, returns false if no records found
                foreach ($result as $record) {
                    array_push($data, $record);
                }
                $code = 200;
                $result = null;
                return array("code" => $code, "data" => $data);
            } else {
                //if no record found, assign corresponding values to error messages/status
                $errmsg = "No records found";
                $code = 404;
            }
        } catch (\PDOException $e) {
            //PDO errors, mysql errors
            $errmsg = $e->getMessage();
            $code = 403;
        }
        return array("code" => $code, "errmsg" => $errmsg);
    }

    public function get_records($table, $condition = null)
    {
        $sqlString = "SELECT * FROM $table";
        if ($condition != null) {
            $sqlString .= " WHERE " . $condition;
        }

        $result = $this->executeQuery($sqlString);

        if ($result['code'] == 200) {
            return $this->sendPayload($result['data'], "success", "Successfully retrieved records.", $result['code']);
        }

        return $this->sendPayload(null, "failed", "Failed to retrieve records.", $result['code']);
    }



    /**
     * Retrieve a list of employees.
     *
     * @return string
     *   A string representing the list of employees.
     */
    public function get_students($id = null)
    {
        $condition = null;
        if ($id != null) {
            $condition = "studentID=$id";
        }
        return $this->get_records("students", $condition);
    }

    /**
     * Retrieve a list of jobs.
     *
     * @return string
     *   A string representing the list of jobs.
     */
    public function get_skill($id = null)
    {
        $condition = null;
        if ($id != null) {
            $condition = "studentID=$id";
        }
        return $this->get_records("skills", $condition);
    }

    public function get_interest($id = null)
    {
        $condition = null;
        if ($id != null) {
            $condition = "studentID=$id";
        }
        return $this->get_records("interest", $condition);
    }

    public function get_accomplishment($id = null)
    {
        $condition = null;
        if ($id != null) {
            $condition = "studentID=$id";
        }
        return $this->get_records("accomplishments", $condition);
    }

    public function get_aboutme($id = null)
    {
        $condition = null;
        if ($id != null) {
            $condition = "studentID=$id";
        }
        return $this->get_records("aboutme", $condition);
    }
}
