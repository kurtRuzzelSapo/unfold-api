<?php
require_once "global.php";
header('Content-Type: application/json');
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

    public function get_service($id = null)
    {
        $condition = null;
        if ($id != null) {
            $condition = "studentID=$id";
        }
        return $this->get_records("service", $condition);
    }

    public function get_interest($id = null)
    {
        $condition = null;
        if ($id != null) {
            $condition = "studentID=$id";
        }
        return $this->get_records("interest", $condition);
    }

    public function get_accomplishment($data) {
        $id = $data['accomID'];
        if ($id === null) {
            // Handle case where $id is not provided
            return array(
                "error" => "Error: Project ID is required"
            );
        }
    
        try {
            $payload = [];
    
            $sql_project = "SELECT * FROM accomplishments WHERE accomID = :id";
            $stmt_project = $this->pdo->prepare($sql_project);
            $stmt_project->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_project->execute();
            
            $project = $stmt_project->fetch(PDO::FETCH_ASSOC);
            if ($project) {
                $payload['competition'] = $project;
            } else {
                return array(
                    "error" => "Error: Project not found"
                );
            }
    
            return $payload;
        } catch (\PDOException $e) {
            // Handle database errors
            return array(
                "error" => "Error: " . $e->getMessage()
            );
        }
    }
    public function get_about($data) {
        $id = $data['aboutID'];
        if ($id === null) {
            // Handle case where $id is not provided
            return array(
                "error" => "Error: Project ID is required"
            );
        }
    
        try {
            $payload = [];
    
            $sql_project = "SELECT * FROM aboutme WHERE aboutID = :id";
            $stmt_project = $this->pdo->prepare($sql_project);
            $stmt_project->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_project->execute();
            
            $project = $stmt_project->fetch(PDO::FETCH_ASSOC);
            if ($project) {
                $payload['about'] = $project;
            } else {
                return array(
                    "error" => "Error: About not found"
                );
            }
    
            return $payload;
        } catch (\PDOException $e) {
            // Handle database errors
            return array(
                "error" => "Error: " . $e->getMessage()
            );
        }
    }

    public function view_portfolio($data)
    {
        $id = $data['id'];
        if ($id === null) {
            // Handle case where $id is not provided
            return array(
                "error" => "Error: Student ID is required"
            );
        }

        try {
            // Initialize an empty array to store the portfolio data
            $portfolio = [];

            // Query for 'students' data
            $sql_studentinfo = "SELECT * FROM students WHERE studentID = :id LIMIT 1";
            $stmt_studentinfo = $this->pdo->prepare($sql_studentinfo);
            $stmt_studentinfo->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_studentinfo->execute();
            $portfolio['student'] = $stmt_studentinfo->fetch(PDO::FETCH_ASSOC);

            // Query for 'aboutme' data
            // $sql_aboutme = "SELECT * FROM aboutme WHERE studentID = :id LIMIT 1";
            // $stmt_aboutme = $this->pdo->prepare($sql_aboutme);
            // $stmt_aboutme->bindParam(':id', $id, PDO::PARAM_INT);
            // $stmt_aboutme->execute();
            // $portfolio['about'] = $stmt_aboutme->fetch(PDO::FETCH_ASSOC);

            $sql_aboutme = "SELECT * FROM aboutme WHERE studentID = :id";
            $stmt_aboutme = $this->pdo->prepare($sql_aboutme);
            $stmt_aboutme->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_aboutme->execute();
            $portfolio['about'] = $stmt_aboutme->fetchAll(PDO::FETCH_ASSOC);

            // Query for 'accomplishments' data
            $sql_accomplishments = "SELECT * FROM accomplishments WHERE studentID = :id";
            $stmt_accomplishments = $this->pdo->prepare($sql_accomplishments);
            $stmt_accomplishments->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_accomplishments->execute();
            $portfolio['accomplishment'] = $stmt_accomplishments->fetchAll(PDO::FETCH_ASSOC);

            $sql_accomplishments = "SELECT * FROM contact WHERE studentID = :id";
            $stmt_accomplishments = $this->pdo->prepare($sql_accomplishments);
            $stmt_accomplishments->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_accomplishments->execute();
            $portfolio['contact'] = $stmt_accomplishments->fetchAll(PDO::FETCH_ASSOC);

            // Query for 'interest' data
            $sql_interest = "SELECT * FROM interest WHERE studentID = :id";
            $stmt_interest = $this->pdo->prepare($sql_interest);
            $stmt_interest->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_interest->execute();
            $portfolio['interest'] = $stmt_interest->fetchAll(PDO::FETCH_ASSOC);

            // Query for 'project' data
            $sql_project = "SELECT * FROM project WHERE studentID = :id";
            $stmt_project = $this->pdo->prepare($sql_project);
            $stmt_project->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_project->execute();
            $portfolio['project'] = $stmt_project->fetchAll(PDO::FETCH_ASSOC);

            // Query for 'service' data
            $sql_service = "SELECT * FROM service WHERE studentID = :id";
            $stmt_service = $this->pdo->prepare($sql_service);
            $stmt_service->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_service->execute();
            $portfolio['service'] = $stmt_service->fetchAll(PDO::FETCH_ASSOC);

            // Query for 'skills' data
            $sql_skills = "SELECT * FROM skills WHERE studentID = :id";
            $stmt_skills = $this->pdo->prepare($sql_skills);
            $stmt_skills->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_skills->execute();
            $portfolio['skill'] = $stmt_skills->fetchAll(PDO::FETCH_ASSOC);

            $sql_skills = "SELECT * FROM testimony WHERE studentID = :id";
            $stmt_skills = $this->pdo->prepare($sql_skills);
            $stmt_skills->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_skills->execute();
            $portfolio['testimony'] = $stmt_skills->fetchAll(PDO::FETCH_ASSOC);

             // Count projects, skills, and accomplishments
        $portfolio['counts'] = [
            'projects' => count($portfolio['project']),
            'technologies' => count($portfolio['skill']),
            'competitions' => count($portfolio['accomplishment']),
            'contacts' => count($portfolio['contact']),
            'about' => count($portfolio['about']),
            'testimony' => count($portfolio['testimony']),
        ];
            // Return the portfolio data
            return $portfolio;
        } catch (\PDOException $e) {
            // Handle database errors
            return "Error: " . $e->getMessage();
        }
    }

    public function get_project($data) {
        $id = $data['projectID'];
        if ($id === null) {
            // Handle case where $id is not provided
            return array(
                "error" => "Error: Project ID is required"
            );
        }
    
        try {
            $payload = [];
    
            $sql_project = "SELECT * FROM project WHERE projectID = :id";
            $stmt_project = $this->pdo->prepare($sql_project);
            $stmt_project->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_project->execute();
            
            $project = $stmt_project->fetch(PDO::FETCH_ASSOC);
            if ($project) {
                $payload['project'] = $project;
            } else {
                return array(
                    "error" => "Error: Project not found"
                );
            }
    
            return $payload;
        } catch (\PDOException $e) {
            // Handle database errors
            return array(
                "error" => "Error: " . $e->getMessage()
            );
        }
    }

    public function get_skill($data) {
        $id = $data['skillID'];
        if ($id === null) {
            // Handle case where $id is not provided
            return array(
                "error" => "Error: Project ID is required"
            );
        }
    
        try {
            $payload = [];
    
            $sql_project = "SELECT * FROM skills WHERE skillID = :id";
            $stmt_project = $this->pdo->prepare($sql_project);
            $stmt_project->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_project->execute();
            
            $project = $stmt_project->fetch(PDO::FETCH_ASSOC);
            if ($project) {
                $payload['skill'] = $project;
            } else {
                return array(
                    "error" => "Error: Project not found"
                );
            }
    
            return $payload;
        } catch (\PDOException $e) {
            // Handle database errors
            return array(
                "error" => "Error: " . $e->getMessage()
            );
        }
    }
    public function get_contact($data) {
        $id = $data['contID'];
        if ($id === null) {
            // Handle case where $id is not provided
            return array(
                "error" => "Error: Project ID is required"
            );
        }
    
        try {
            $payload = [];
    
            $sql_project = "SELECT * FROM contact WHERE contID = :id";
            $stmt_project = $this->pdo->prepare($sql_project);
            $stmt_project->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_project->execute();
            
            $project = $stmt_project->fetch(PDO::FETCH_ASSOC);
            if ($project) {
                $payload['contact'] = $project;
            } else {
                return array(
                    "error" => "Error: Project not found"
                );
            }
    
            return $payload;
        } catch (\PDOException $e) {
            // Handle database errors
            return array(
                "error" => "Error: " . $e->getMessage()
            );
        }
    }
    

    // public function get_all_students()
    // {
    //     $sql = "SELECT s.*, a.aboutText
    //         FROM students s
    //         LEFT JOIN (
    //             SELECT studentID, MAX(aboutText) AS aboutText
    //             FROM aboutme
    //             GROUP BY studentID
    //         ) a ON s.studentID = a.studentID
    //         WHERE s.is_admin = 0";
    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->execute();
    //     $students = $stmt->fetchAll(PDO::FETCH_ASSOC);


    //     foreach ($students as &$student) {
    //         unset($student['password']);
    //     }

    //     return $students;
    // }

//     public function get_all_students()
// {
//     $sql = "SELECT s.*, a.aboutText, a.aboutImg
//             FROM students s
//             LEFT JOIN (
//                 SELECT studentID, 
//                        MAX(aboutText) AS aboutText,
//                        MAX(aboutImg) AS aboutImg
//                 FROM aboutme
//                 GROUP BY studentID
//             ) a ON s.studentID = a.studentID
//             WHERE s.is_admin = 0";
//     $stmt = $this->pdo->prepare($sql);
//     $stmt->execute();
//     $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     foreach ($students as &$student) {
//         unset($student['password']);
//     }

//     return $students;
// }


// public function get_all_students()
// {
//     $sql = "SELECT s.*, a.aboutText, a.aboutImg, GROUP_CONCAT(sk.skillTitle) as skills
//             FROM students s
//             LEFT JOIN (
//                 SELECT studentID, 
//                        MAX(aboutText) AS aboutText,
//                        MAX(aboutImg) AS aboutImg
//                 FROM aboutme
//                 GROUP BY studentID
//             ) a ON s.studentID = a.studentID
//             LEFT JOIN skills sk ON s.studentID = sk.studentID
//             WHERE s.is_admin = 0
//             GROUP BY s.studentID";
//     $stmt = $this->pdo->prepare($sql);
//     $stmt->execute();
//     $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     foreach ($students as &$student) {
//         unset($student['password']);
//         // Convert comma-separated skills into an array
//         $student['skills'] = $student['skills'] ? explode(',', $student['skills']) : [];
//     }

//     return $students;
// }


public function get_all_students()
{
    $sql = "SELECT s.*, 
                   a.aboutText, 
                   a.aboutImg, 
                   GROUP_CONCAT(DISTINCT sk.skillTitle) as skills, 
                   GROUP_CONCAT(DISTINCT p.projectTitle) as projects
            FROM students s
            LEFT JOIN (
                SELECT studentID, 
                       MAX(aboutText) AS aboutText,
                       MAX(aboutImg) AS aboutImg
                FROM aboutme
                GROUP BY studentID
            ) a ON s.studentID = a.studentID
            LEFT JOIN skills sk ON s.studentID = sk.studentID
            LEFT JOIN project p ON s.studentID = p.studentID
            WHERE s.is_admin = 0
            GROUP BY s.studentID";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($students as &$student) {
        unset($student['password']);
        // Convert comma-separated skills into an array
        $student['skills'] = $student['skills'] ? explode(',', $student['skills']) : [];
        // Convert comma-separated projects into an array
        $student['projects'] = $student['projects'] ? explode(',', $student['projects']) : [];
    }

    return $students;
}

public function get_all_projects()
{
    $sql = "SELECT p.projectID, 
                   p.projectTitle, 
                   p.projectDesc,
                   p.projectImg, 
                   p.projectDate, 
                   p.projectLink,
                   p.studentID,
                   CONCAT(st.firstName, ' ', st.lastName) as studentName,
                   st.position as studentPosition,
                   st.course as studentCourse,
                   a.aboutImg
            FROM project p
            LEFT JOIN students st ON p.studentID = st.studentID
            LEFT JOIN aboutme a ON st.studentID = a.studentID
            WHERE st.is_admin = 0";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $this->shuffle_projects($projects);
}

private function shuffle_projects($projects)
{
    // Group projects by studentID
    $grouped_projects = [];
    foreach ($projects as $project) {
        $grouped_projects[$project['studentID']][] = $project;
    }

    // Flatten the grouped projects array while ensuring no two projects of the same student are adjacent
    $shuffled_projects = [];
    $keys = array_keys($grouped_projects);
    shuffle($keys);

    while (!empty($keys)) {
        foreach ($keys as $key) {
            if (!empty($grouped_projects[$key])) {
                $shuffled_projects[] = array_shift($grouped_projects[$key]);
            }
        }

        // Remove empty keys
        $keys = array_filter($keys, function($key) use ($grouped_projects) {
            return !empty($grouped_projects[$key]);
        });

        // Shuffle keys to maintain randomness in subsequent iterations
        shuffle($keys);
    }

    return $shuffled_projects;
}















public function get_all_faculty(){
    $sql = "SELECT * FROM faculty";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    $faculty = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $faculty;
}

}
