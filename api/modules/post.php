<?php
require_once "global.php";

class Post extends GlobalMethods
{

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // public function uploadImage($file, $uploadDirectory)
    // {
    //     // Generate a unique filename to prevent overwriting existing files
    //     $filename = uniqid() . '_' . $file['name'];
    //     $targetPath = $uploadDirectory . $filename;

    //     // Check if the file is an image
    //     $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
    //     $allowedTypes = array('.jpg', '.jpeg', '.png', '.gif');
    //     if (!in_array($fileType, $allowedTypes)) {
    //         return "Error: Only JPG, JPEG, PNG, and GIF files are allowed.";
    //     }

    //     // Move the uploaded file to the specified directory
    //     if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    //         return "Error: Failed to move uploaded file.";
    //     }

    //     // Return the uploaded filename
    //     return $filename;
    // }
    public function uploadImage($file, $uploadDirectory)
    {
        // Generate a unique filename to prevent overwriting existing files
        $filename = uniqid() . '_' . $file['name'];
        $targetPath = $uploadDirectory . $filename;
    
        // Check if the file is an image
        $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif'); // Use lowercase extensions without dot
        if (!in_array($fileType, $allowedTypes)) {
            return false; // Return false if file type is not allowed
        }
    
        // Move the uploaded file to the specified directory
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return false; // Return false if moving the file failed
        }
    
        // Return the uploaded filename
        return $filename;
    }
    

    /**
     * Add a new employee with the provided data.
     *
     * @param array|object $data
     *   The data representing the new employee.
     *
     * @return array|object
     *   The added employee data.
     */


    //  public function add_faculty($data)
    //  {
    //      $facFirstname = $data['facFirstname'];
    //      $facLastname = $data['facLastname'];
    //      $facEmail = $data['facEmail'];
    //      $facPassword = $data['facPassword'];
    //      $facPosition = $data['facPosition'];
    //      $facImg = 'http://localhost/unfold/unfold-api/files/profile/faculty_profile.png';
     
    //      try {
    //          $sql = "INSERT INTO faculty (facFirstname, facLastname, facEmail, facPassword, facPosition, facImg) VALUES (?, ?, ?, ?, ?,?)";
    //          $statement = $this->pdo->prepare($sql);
    //          $statement->execute([$facFirstname, $facLastname, $facEmail, $facPassword, $facPosition, $facImg]);
     
    //          return $this->sendPayload(null, "success", "Successfully added records.", null);
    //      } catch (\PDOException $e) {
    //          $errmsg = $e->getMessage();
    //          return $this->sendPayload(null, "error", $errmsg, null);
    //      }
    //  }

    // public function add_faculty($data)
    // {
    //     $facFirstname = $data['facFirstname'];
    //     $facLastname = $data['facLastname'];
    //     $facEmail = $data['facEmail'];
    //     $facPassword = password_hash($data['facPassword'], PASSWORD_BCRYPT); // Hash the password
    //     $facPosition = $data['facPosition'];
    //     $facImg = '/files/profile/faculty_profile.png';
    //     $isFaculty = 1; // Set is_faculty to 1
    
    //     try {
    //         $sql = "INSERT INTO faculty (facFirstname, facLastname, facEmail, facPassword, facPosition, facImg, is_faculty) VALUES (?, ?, ?, ?, ?, ?, ?)";
    //         $statement = $this->pdo->prepare($sql);
    //         $statement->execute([$facFirstname, $facLastname, $facEmail, $facPassword, $facPosition, $facImg, $isFaculty]);
    
    //         return $this->sendPayload(null, "success", "Successfully added records.", null);
    //     } catch (\PDOException $e) {
    //         $errmsg = $e->getMessage();
    //         return $this->sendPayload(null, "error", $errmsg, null);
    //     }
    // }

    public function add_faculty($data)
{
    $facFirstname = $data['facFirstname'];
    $facLastname = $data['facLastname'];
    $facEmail = $data['facEmail'];
    $facPassword = password_hash($data['facPassword'], PASSWORD_BCRYPT); // Hash the password
    $facPosition = $data['facPosition'];
    $facImg = '/files/profile/faculty_profile.png'; // Default image for faculty members
    $isFaculty = 1; // Set is_faculty to 1

    try {
        $sql = "INSERT INTO faculty (facFirstname, facLastname, facEmail, facPassword, facPosition, facImg, is_faculty) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$facFirstname, $facLastname, $facEmail, $facPassword, $facPosition, $facImg, $isFaculty]);

        return $this->sendPayload(null, "success", "Successfully added records.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        return $this->sendPayload(null, "error", $errmsg, null);
    }
}

    





    public function add_students($data)
    {
        $password = $data->password;
        $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
        try {

            $sql = "INSERT INTO students (firstName, lastName, email, password, address, contacts, course, sex, birthdate, school) VALUES (?,?,?,?,?,?,?,?,?,?)";

            $statement = $this->pdo->prepare($sql);

            $statement->execute(
                [
                    $data->firstName,
                    $data->lastName,
                    $data->email,
                    $hashedpassword,
                    $data->address,
                    $data->contacts,
                    $data->course,
                    $data->sex,
                    $data->birthdate,
                    $data->school,
                ]
            );


            return $this->sendPayload(null, "success", "Successfully add records.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }


        return $this->sendPayload(null, "Unsuccessfully", $errmsg, null);
    }
    public function edit_students($data, $id)
    {
        
        try {
            $sql = "UPDATE students 
            SET firstName = ?, lastName = ?, email = ?, password = ?, address = ?, contacts = ?, course = ?
            WHERE studentID = ?";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                $data->firstName,
                $data->lastName,
                $data->email,
                $data->password,
                $data->address,
                $data->contacts,
                $data->course,
                $id
            ]);

            return $this->sendPayload(null, "success", "Successfully updated.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }
    }
    public function delete_students($id)
    {
        try {
            $sql = " DELETE FROM students WHERE studentID = ?";


            $statement = $this->pdo->prepare($sql);

            $statement->execute(
                [
                    $id
                ]
            );


            return $this->sendPayload(null, "success", "Successfully deleted.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }


        return $this->sendPayload(null, "Unsuccessfully", $errmsg, null);
    }

    /**
     * Add a new job with the provided data.
     *
     * @param array|object $data
     *   The data representing the new job.
     *
     * @return array|object
     *   The added job data.
     */
    public function add_skill($data)
    {

        $skillTitle = $data['skillTitle'];
        $skillDesc = $data['skillDesc'];
        $studentId =  $data['studentID'];
            try{

            

            $sql = "INSERT INTO skills (skillTitle, skillDesc, studentID) VALUES (?, ?, ?)";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$skillTitle, $skillDesc, $studentId]);

            return $this->sendPayload(null, "success", "Successfully added records.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            return $this->sendPayload(null, "error", $errmsg, null);
        }




    }

    public function add_contact($data)
    {

        $contLinkedin = $data['contLinkedin'];
        $contFB = $data['contFB'];
        $contIG = $data['contIG'];
        $contGithub = $data['contGithub'];
        $studentId =  $data['studentID'];
            try{

            

            $sql = "INSERT INTO contact (contLinkedin, contFB,contIG,contGithub, studentID) VALUES (?,?,?,?,?)";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$contLinkedin, $contFB,$contIG,$contGithub, $studentId]);

            return $this->sendPayload(null, "success", "Successfully added records.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            return $this->sendPayload(null, "error", $errmsg, null);
        }




    }
    
   
    
    public function edit_skill($data)
{
    $skillID = $data['skillID']; // Assuming you pass the skill ID for editing
    $skillTitle = $data['skillTitle'];
    $skillDesc = $data['skillDesc'];
  

    try {
        // Check if the skill ID is provided for editing
        if (!empty($skillID)) {
            // If skill ID is provided, update the existing skill
            $sql = "UPDATE skills SET skillTitle=?, skillDesc=? WHERE skillID=?";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$skillTitle, $skillDesc, $skillID]);
        } else {
            // If skill ID is not provided, it's a new skill, so insert it
            $sql = "INSERT INTO skills (skillTitle, skillDesc) VALUES ( ?, ?)";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$skillTitle, $skillDesc]);
        }

        return $this->sendPayload(null, "success", "Successfully updated/added records.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        return $this->sendPayload(null, "error", $errmsg, null);
    }
}
public function edit_contact($data)
{
    $contLinkedin = $data['contLinkedin'];
    $contFB = $data['contFB'];
    $contIG = $data['contIG'];
    $contGithub = $data['contGithub'];
    $contID = $data['contID']; // Using contID as the primary key

    try {
        $sql = "UPDATE contact SET contLinkedin = ?, contFB = ?, contIG = ?, contGithub = ? WHERE contID = ?";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$contLinkedin, $contFB, $contIG, $contGithub, $contID]);

        return $this->sendPayload(null, "success", "Successfully updated records.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        return $this->sendPayload(null, "error", $errmsg, null);
    }
}



public function delete_skill($id)
{
    try {
        $sql = "DELETE FROM skills WHERE skillID = ?";

        $statement = $this->pdo->prepare($sql);

        $statement->execute([$id]);

        return $this->sendPayload(null, "success", "Successfully deleted.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        // You can choose to handle errors differently here
    }

    return $this->sendPayload(null, "Unsuccessfully", $errmsg, null);
}
public function delete_contact($id)
{
    try {
        $sql = "DELETE FROM contact WHERE contID = ?";

        $statement = $this->pdo->prepare($sql);

        $statement->execute([$id]);

        return $this->sendPayload(null, "success", "Successfully deleted.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        // You can choose to handle errors differently here
    }

    return $this->sendPayload(null, "Unsuccessfully", $errmsg, null);
}




  


    


    public function add_accomplishments($data)
    {

        $accomTitle = $data['accomTitle'];
        $accomDesc = $data['accomDesc'];
        $accomLink = $data['accomLink'];
        $accomDate = $data['accomDate'];
        $studentId =  $data['studentID'];

        $uploadDirectory = "../files/accomplishments/";
        try {
            // Check if a file was uploaded
            if (isset($_FILES['accomImg']) && $_FILES['accomImg']['error'] === UPLOAD_ERR_OK) {
                // Call the uploadImage function to handle the file up`load
                $filename = $this->uploadImage($_FILES['accomImg'], $uploadDirectory);
                if (!$filename) {
                    return $this->sendPayload(null, "error", "Failed to upload image.", null);
                }
            }

            $sql = "INSERT INTO accomplishments (accomTitle, accomDesc, accomLink, accomDate, accomImg, studentID) VALUES (?, ?, ?, ?, ?, ?)";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$accomTitle, $accomDesc,$accomLink, $accomDate, "/files/accomplishments/$filename", $studentId]);

            return $this->sendPayload(null, "success", "Successfully added records.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            return $this->sendPayload(null, "error", $errmsg, null);
        }
    }

    // $uploadDirectory = "../files/accomplishments/";
    public function edit_accomplishments($data)
    {
        $accomId = $data['accomID'];
        $accomTitle = $data['accomTitle'];
        $accomLink = $data['accomLink'];
        $accomDate = $data['accomDate'];
        $accomDesc = $data['accomDesc'];
      
    
        $uploadDirectory = "../files/accomplishments/";
        $filename = null;
    
        try {
            // Check if a file was uploaded
            if (isset($_FILES['accomImg']) && $_FILES['accomImg']['error'] === UPLOAD_ERR_OK) {
                // Call the uploadImage function to handle the file upload
                $filename = $this->uploadImage($_FILES['accomImg'], $uploadDirectory);
                if (!$filename) {
                    return $this->sendPayload(null, "error", "Failed to upload image.", null);
                }
            }
    
            // If a new image was uploaded, include it in the update query
            if ($filename) {
                $sql = "UPDATE accomplishments SET accomTitle = ?, accomDesc = ?, accomImg = ?, accomLink = ?, accomDate = ? WHERE accomID = ?";
                $params = [$accomTitle, $accomDesc, "/files/accomplishments/$filename",  $accomLink, $accomDate, $accomId];
            } else {
                // If no new image was uploaded, do not update the accomImg field
                $sql = "UPDATE accomplishments SET accomTitle = ?, accomDesc = ?, accomLink = ?, accomDate = ? WHERE accomID = ?";
                $params = [$accomTitle, $accomDesc,  $accomLink, $accomDate, $accomId];
            }
    
            $statement = $this->pdo->prepare($sql);
            $statement->execute($params);
    
            return $this->sendPayload(null, "success", "Successfully updated records.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            return $this->sendPayload(null, "error", $errmsg, null);
        }
    }

 

public function delete_accomplishment($id)
{
    try {
        $sql = "DELETE FROM accomplishments WHERE accomID = ?";

        $statement = $this->pdo->prepare($sql);

        $statement->execute([$id]);

        return $this->sendPayload(null, "success", "Successfully deleted.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        // You can choose to handle errors differently here
    }

    return $this->sendPayload(null, "Unsuccessfully", $errmsg, null);
}





    public function add_aboutme($data)
    {

        $aboutText = $data['aboutText'];
        $studentId =  $data['studentID'];

        $uploadDirectory = "../files/about-me/";
        try {
            // Check if a file was uploaded
            if (isset($_FILES['aboutImg']) && $_FILES['aboutImg']['error'] === UPLOAD_ERR_OK) {
                // Call the uploadImage function to handle the file up`load
                $filename = $this->uploadImage($_FILES['aboutImg'], $uploadDirectory);
                if (!$filename) {
                    return $this->sendPayload(null, "error", "Failed to upload image.", null);
                }
            }

            $sql = "INSERT INTO aboutme (aboutText,studentID,aboutImg) VALUES (?,?,?)";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$aboutText, $studentId, "/files/about-me/$filename"]);

            return $this->sendPayload(null, "success", "Successfully added records.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            return $this->sendPayload(null, "error", $errmsg, null);
        }
    }


    public function edit_aboutme($data)
    {
        // Assuming you pass the about ID for editing
        $aboutText = $data['aboutText'];
        $aboutId = $data['aboutID'];
        
        $uploadDirectory = "../files/about-me/";
    $filename = null;

    try {
        // Check if a file was uploaded
        if (isset($_FILES['aboutImg']) && $_FILES['aboutImg']['error'] === UPLOAD_ERR_OK) {
            // Call the uploadImage function to handle the file upload
            $filename = $this->uploadImage($_FILES['aboutImg'], $uploadDirectory);
            if (!$filename) {
                return $this->sendPayload(null, "error", "Failed to upload image.", null);
            }
        }

        // If a new image was uploaded, include it in the update query
        if ($filename) {
            $sql = "UPDATE aboutme SET aboutText = ?, aboutImg = ? WHERE aboutID = ?";
            $params = [$aboutText, "/files/about-me/$filename", $aboutId];
        } else {
            // If no new image was uploaded, do not update the aboutImg field
            $sql = "UPDATE aboutme SET aboutText = ?, aboutImg = ? WHERE aboutID = ?";
            $params = [$aboutText, "/files/about-me/$filename", $aboutId];
        }

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $this->sendPayload(null, "success", "Successfully updated records.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        return $this->sendPayload(null, "error", $errmsg, null);
    }

    
    }
    
   
    public function delete_aboutme($id)
    {
        try {
            $sql = " DELETE FROM aboutme WHERE studentID = ?";


            $statement = $this->pdo->prepare($sql);

            $statement->execute(
                [
                    $id
                ]
            );


            return $this->sendPayload(null, "success", "Successfully deleted.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }


        return $this->sendPayload(null, "Unsuccessfully", $errmsg, null);
    }


    public function add_project($data)
    {

        $projectTitle = $data['projectTitle'];
        $projectLink = $data['projectLink'];
        $projectDate = $data['projectDate'];
        $projectDesc = $data['projectDesc'];
        $studentId =  $data['studentID'];

        $uploadDirectory = "../files/projects/";
        try {
            // Check if a file was uploaded
            if (isset($_FILES['projectImg']) && $_FILES['projectImg']['error'] === UPLOAD_ERR_OK) {
                // Call the uploadImage function to handle the file up`load
                $filename = $this->uploadImage($_FILES['projectImg'], $uploadDirectory);
                if (!$filename) {
                    return $this->sendPayload(null, "error", "Failed to upload image.", null);
                }
            }

            $sql = "INSERT INTO project (projectTitle, projectDesc, projectImg, studentID, projectLink, projectDate) VALUES (?, ?, ?, ?, ?, ?)";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$projectTitle, $projectDesc, "/files/projects/$filename", $studentId, $projectLink, $projectDate]);

            return $this->sendPayload(null, "success", "Successfully added records.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            return $this->sendPayload(null, "error", $errmsg, null);
        }
    }



    public function edit_project($data)
{
    $projectId = $data['projectID'];
    $projectTitle = $data['projectTitle'];
    $projectLink = $data['projectLink'];
    $projectDate = $data['projectDate'];
    $projectDesc = $data['projectDesc'];
  

    $uploadDirectory = "../files/projects/";
    $filename = null;

    try {
        // Check if a file was uploaded
        if (isset($_FILES['projectImg']) && $_FILES['projectImg']['error'] === UPLOAD_ERR_OK) {
            // Call the uploadImage function to handle the file upload
            $filename = $this->uploadImage($_FILES['projectImg'], $uploadDirectory);
            if (!$filename) {
                return $this->sendPayload(null, "error", "Failed to upload image.", null);
            }
        }

        // If a new image was uploaded, include it in the update query
        if ($filename) {
            $sql = "UPDATE project SET projectTitle = ?, projectDesc = ?, projectImg = ?, projectLink = ?, projectDate = ? WHERE projectID = ?";
            $params = [$projectTitle, $projectDesc, "/files/projects/$filename",  $projectLink, $projectDate, $projectId];
        } else {
            // If no new image was uploaded, do not update the projectImg field
            $sql = "UPDATE project SET projectTitle = ?, projectDesc = ?, projectLink = ?, projectDate = ? WHERE projectID = ?";
            $params = [$projectTitle, $projectDesc,  $projectLink, $projectDate, $projectId];
        }

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $this->sendPayload(null, "success", "Successfully updated records.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        return $this->sendPayload(null, "error", $errmsg, null);
    }
}

public function delete_project($id){
    try {
        $sql = " DELETE FROM project WHERE projectID = ?";


        $statement = $this->pdo->prepare($sql);

        $statement->execute(
            [
                $id
            ]
        );


        return $this->sendPayload(null, "success", "Successfully deleted.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        $code = 400;
    }


    return $this->sendPayload(null, "Unsuccessfully", $errmsg, null);
}

// public function add_testimony($data)
// {
//     $testDesc = $data['testDesc'];
//     $studentId = $data['studentID'];
//     $testFirstname = $data['testFirstname'];
//     $testLastname = $data['testLastname'];
//     $testPosition = $data['testPosition'];
    
//     $uploadDirectory = "../files/projects/";

//     try {
//         // Check if a file was uploaded
//         if (isset($_FILES['testImg']) && $_FILES['testImg']['error'] === UPLOAD_ERR_OK) {
//             // Call the uploadImage function to handle the file upload
//             $filename = $this->uploadImage($_FILES['testImg'], $uploadDirectory);
//             if (!$filename) {
//                 return $this->sendPayload(null, "error", "Failed to upload image.", null);
//             }
//         }

//         $sql = "INSERT INTO testimony (testDesc, studentID, testFirstname, testLastname, testPosition, testImg) VALUES (?, ?, ?, ?, ?, ?)";
//         $statement = $this->pdo->prepare($sql);
//         $statement->execute([$testDesc, $studentId, $testFirstname, $testLastname, $testPosition, "/files/projects/$filename"]);

//         return $this->sendPayload(null, "success", "Successfully added records.", null);
//     } catch (\PDOException $e) {
//         $errmsg = $e->getMessage();
//         return $this->sendPayload(null, "error", $errmsg, null);
//     }
// }

// public function add_testimony($data)
// {
//     $testDesc = $data['testDesc'];
//     $studentId = $data['studentID'];
//     $testFirstname = $data['testFirstname'];
//     $testLastname = $data['testLastname'];
//     $testPosition = $data['testPosition'];
    
//     $uploadDirectory = "../files/projects/";
//     $filename = null; // Initialize the $filename variable

//     try {
//         // Check if a file was uploaded
//         if (isset($_FILES['testImg']) && $_FILES['testImg']['error'] === UPLOAD_ERR_OK) {
//             // Call the uploadImage function to handle the file upload
//             $filename = $this->uploadImage($_FILES['testImg'], $uploadDirectory);
//             if (!$filename) {
//                 return $this->sendPayload(null, "error", "Failed to upload image.", null);
//             }
//         }

//         // If no file is uploaded, set $filename to a default value (e.g., empty string or null)
//         $filePath = $filename ? "/files/projects/$filename" : null;

//         $sql = "INSERT INTO testimony (testDesc, studentID, testFirstname, testLastname, testPosition, testImg) VALUES (?, ?, ?, ?, ?, ?)";
//         $statement = $this->pdo->prepare($sql);
//         $statement->execute([$testDesc, $studentId, $testFirstname, $testLastname, $testPosition, $filePath]);

//         return $this->sendPayload(null, "success", "Successfully added records.", null);
//     } catch (\PDOException $e) {
//         $errmsg = $e->getMessage();
//         return $this->sendPayload(null, "error", $errmsg, null);
//     }
// }

public function add_testimony($data)
{
    $testDesc = $data['testDesc'];
    $studentId = $data['studentID'];
    $testFirstname = $data['testFirstname'];
    $testLastname = $data['testLastname'];
    $testPosition = $data['testPosition'];
    
    $uploadDirectory = "../files/projects/";
    $defaultImagePath = '/files/profile/faculty_profile.png'; // Default image path

    try {
        $filename = null;

        // Check if a file was uploaded
        if (isset($_FILES['testImg']) && $_FILES['testImg']['error'] === UPLOAD_ERR_OK) {
            // Call the uploadImage function to handle the file upload
            $filename = $this->uploadImage($_FILES['testImg'], $uploadDirectory);
            if (!$filename) {
                return $this->sendPayload(null, "error", "Failed to upload image.", null);
            }
        }

        // Use the uploaded file path or default image path
        $filePath = $filename ? "/files/projects/$filename" : $defaultImagePath;

        $sql = "INSERT INTO testimony (testDesc, studentID, testFirstname, testLastname, testPosition, testImg) VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$testDesc, $studentId, $testFirstname, $testLastname, $testPosition, $filePath]);

        return $this->sendPayload(null, "success", "Successfully added records.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        return $this->sendPayload(null, "error", $errmsg, null);
    }
}


public function add_views($id)
{
    try {
        $sql = "UPDATE students SET portfolioView = portfolioView + 1 WHERE studentID = ?";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);

        return $this->sendPayload(null, "success", "Successfully incremented portfolio view count.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        return $this->sendPayload(null, "error", $errmsg, null);
    }
}
public function add_approve($id)
{
    try {
        $sql = "UPDATE students SET approved = approved + 1 WHERE studentID = ?";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);

        return $this->sendPayload(null, "success", "Successfully incremented portfolio view count.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        return $this->sendPayload(null, "error", $errmsg, null);
    }
}













//     public function login($data)
// {
//     try {
//         // Check in students table
//         $sql = "SELECT * FROM students WHERE email = :email";
//         $statement = $this->pdo->prepare($sql);
//         $statement->bindParam(':email', $data->email);
//         $statement->execute();
//         $user = $statement->fetch(PDO::FETCH_OBJ);

//         // If not found in students, check in faculty table
//         if (!$user) {
//             $sql = "SELECT * FROM faculty WHERE facEmail = :facEmail";
//             $statement = $this->pdo->prepare($sql);
//             $statement->bindParam(':facEmail', $data->facEmail);
//             $statement->execute();
//             $user = $statement->fetch(PDO::FETCH_OBJ);
//         }

//         if ($user) {
//             // Check if password matches
//             if (password_verify($data->facPassword, $user->facPassword)) {
//                 return $this->sendPayload($user, "success", "Login successful.", null);
//             } else {
//                 return $this->sendPayload(null, "error", "Incorrect password", null);
//             }
//         } else {
//             return $this->sendPayload(null, "error", "Incorrect email", null);
//         }
//     } catch (\PDOException $e) {
//         $errmsg = $e->getMessage();
//         $code = 400;
//         // Handle error
//         // You may want to log the error or return an appropriate response
//         return $this->sendPayload(null, "error", $errmsg, null);
//     }
// }


public function login($data)
{
    try {
        $sql = "SELECT * FROM students WHERE email = :email";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':email', $data->email);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_OBJ);

        // Check if user is found in the students table
        if ($user) {
            $passwordHash = $user->password; // Assuming the password field in students table is named 'password'
        } else {
            // If not found, check in the faculty table
            $sql = "SELECT * FROM faculty WHERE facEmail = :email";
            $statement = $this->pdo->prepare($sql);
            $statement->bindParam(':email', $data->email);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_OBJ);

            if ($user) {
                $passwordHash = $user->facPassword; // Assuming the password field in faculty table is named 'facPassword'
            }
        }

        if ($user) {
            // Check if password matches
            if (password_verify($data->password, $passwordHash)) { // Use $data->password to verify against the hashed password
                return $this->sendPayload($user, "success", "Login successful.", null);
            } else {
                return $this->sendPayload(null, "error", "Incorrect password", null);
            }
        } else {
            return $this->sendPayload(null, "error", "Incorrect email", null);
        }
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        return $this->sendPayload(null, "error", $errmsg, null);
    }
}





}
