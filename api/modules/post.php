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

        $contName = $data['contName'];
        $contNumber = $data['contNumber'];
        $contEmail = $data['contEmail'];
        $contHome = $data['contHome'];
        $studentId =  $data['studentID'];
            try{

            

            $sql = "INSERT INTO contact (contName, contNumber,contEmail,contHome, studentID) VALUES (?,?,?,?,?)";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$contName, $contNumber,$contEmail,$contHome, $studentId]);

            return $this->sendPayload(null, "success", "Successfully added records.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            return $this->sendPayload(null, "error", $errmsg, null);
        }




    }
    
    // try {
    //     $sql = "INSERT INTO skills (skillTitle,skillDesc, studentID) VALUES (?,?,?)";

    //     $statement = $this->pdo->prepare($sql);

    //     $statement->execute(
    //         [
    //             $data->skillTitle,
    //             $data->skillDesc,
    //             $data->studentID,
    //         ]
    //     );

    //     return $this->sendPayload(null, "success", "Successfully add records.", null);
    // } catch (\PDOException $e) {
    //     $errmsg = $e->getMessage();
    //     $code = 400;
    // }


    // return $this->sendPayload(null, "Unsuccessfully", $errmsg, null);
    
    public function edit_skill($data)
{
    $skillID = $data['skillID']; // Assuming you pass the skill ID for editing
    $skillTitle = $data['skillTitle'];
    $skillDesc = $data['skillDesc'];
    $studentId =  $data['studentID'];

    try {
        // Check if the skill ID is provided for editing
        if (!empty($skillID)) {
            // If skill ID is provided, update the existing skill
            $sql = "UPDATE skills SET skillTitle=?, skillDesc=? WHERE skillID=?";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$skillTitle, $skillDesc, $skillID]);
        } else {
            // If skill ID is not provided, it's a new skill, so insert it
            $sql = "INSERT INTO skills (skillTitle, skillDesc, studentID) VALUES (?, ?, ?)";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$skillTitle, $skillDesc, $studentId]);
        }

        return $this->sendPayload(null, "success", "Successfully updated/added records.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        return $this->sendPayload(null, "error", $errmsg, null);
    }
}



    // Add this method to post.php

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


    public function add_interest($data)
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

    public function edit_interest($data, $id)
    {

        try {
            $sql = "UPDATE interest SET title = ?, description = ? WHERE studentID = ?";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                $data->title,
                $data->description,
                $id
            ]);

            return $this->sendPayload(null, "success", "Successfully updated.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }
    }

    public function delete_interest($id)
    {
        try {
            $sql = " DELETE FROM interest WHERE studentID = ?";


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


    // public function add_accomplishments($data)
    // {
    //     try {
    //         $sql = "INSERT INTO accomplishments ( accomTitle, accomDesc, accomImg, studentID) VALUES (?,?,?,?)";
    //         $statement = $this->pdo->prepare($sql);
    //         $statement->execute(
    //             [
    //                 $data->accomTitle,
    //                 $data->accomDesc,
    //                 $data->accomImg,
    //                 $data->studentID
    //             ]
    //         );

    //         return $this->sendPayload(null, "success", "Successfully add records.", null);
    //     } catch (\PDOException $e) {
    //         $errmsg = $e->getMessage();
    //         $code = 400;
    //     }


    //     return $this->sendPayload(null, "Unsuccessfully", $errmsg, null);
    // }

    public function add_accomplishments($data)
    {

        $accomTitle = $data['accomTitle'];
        $accomDesc = $data['accomDesc'];
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

            $sql = "INSERT INTO accomplishments (accomTitle, accomDesc, accomImg, studentID) VALUES (?, ?, ?, ?)";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$accomTitle, $accomDesc, "/files/accomplishments/$filename", $studentId]);

            return $this->sendPayload(null, "success", "Successfully added records.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            return $this->sendPayload(null, "error", $errmsg, null);
        }
    }

    // public function edit_accomplishments($data, $id)
    // {

    //     try {
    //         $sql = "UPDATE accomplishments SET accomTitle = ?, accomDesc = ?, accomImg = ?  WHERE studentID = ?";
    //         $statement = $this->pdo->prepare($sql);
    //         $statement->execute([
    //             $data->accomTitle,
    //             $data->accomDesc,
    //             $data->accomImg,
    //             $data->studentID,
    //             $id
    //         ]);

    //         return $this->sendPayload(null, "success", "Successfully updated.", null);
    //     } catch (\PDOException $e) {
    //         $errmsg = $e->getMessage();
    //         $code = 400;
    //     }
    // }
    public function edit_accomplishments($data)
    {
        $accomId = $data['accomID']; // Assuming you pass the project ID for editing
        $accomTitle = $data['accomTitle'];
        $accomDesc = $data['accomDesc'];
        $studentId =  $data['studentID'];
    
        $uploadDirectory = "../files/accomplishments/";
        try {
            // Check if a file was uploaded
            if (isset($_FILES['accomImg']) && $_FILES['accomImg']['error'] === UPLOAD_ERR_OK) {
                // Call the uploadImage function to handle the file upload
                $filename = $this->uploadImage($_FILES['accomImg'], $uploadDirectory);
                if (!$filename) {
                    return $this->sendPayload(null, "error", "Failed to upload image.", null);
                }
            }
    
            // Check if the project ID is provided for editing
            if (!empty($accomId)) {
                // If project ID is provided, update the existing project
                $sql = "UPDATE accomplishments SET accomTitle=?, accomDesc=?, accomImg=? WHERE accomID=?";
                $statement = $this->pdo->prepare($sql);
                $statement->execute([$accomTitle, $accomDesc, "/files/accomplishments/$filename", $accomId]);
            } else {
                // If project ID is not provided, it's a new project, so insert it
                $sql = "UPDATE accomplishments SET accomTitle=?, accomDesc=?, accomImg=? WHERE accomID=?";
                $statement = $this->pdo->prepare($sql);
                $statement->execute([$accomTitle, $accomDesc, "/files/accomplishments/$filename", $accomId]);
            }
    
            return $this->sendPayload(null, "success", "Successfully updated/added records.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            return $this->sendPayload(null, "error", $errmsg, null);
        }
    }

    // Add this code to post.php

// Add this method to post.php

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



    public function edit_aboutme($data, $id)
    {

        try {
            $sql = "UPDATE aboutme SET aboutText = ?,aboutImg = ?, WHERE studentID = ?";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                $data->aboutText,
                $data->aboutImg,
                $id
            ]);

            return $this->sendPayload(null, "success", "Successfully updated.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
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

            $sql = "INSERT INTO project (projectTitle, projectDesc, projectImg, studentID) VALUES (?, ?, ?, ?)";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$projectTitle, $projectDesc, "/files/projects/$filename", $studentId]);

            return $this->sendPayload(null, "success", "Successfully added records.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            return $this->sendPayload(null, "error", $errmsg, null);
        }
    }


    public function edit_project($data)
{
    $projectId = $data['projectID']; // Assuming you pass the project ID for editing
    $projectTitle = $data['projectTitle'];
    $projectDesc = $data['projectDesc'];
    $studentId =  $data['studentID'];

    $uploadDirectory = "../files/projects/";
    try {
        // Check if a file was uploaded
        if (isset($_FILES['projectImg']) && $_FILES['projectImg']['error'] === UPLOAD_ERR_OK) {
            // Call the uploadImage function to handle the file upload
            $filename = $this->uploadImage($_FILES['projectImg'], $uploadDirectory);
            if (!$filename) {
                return $this->sendPayload(null, "error", "Failed to upload image.", null);
            }
        }

        // Check if the project ID is provided for editing
        if (!empty($projectId)) {
            // If project ID is provided, update the existing project
            $sql = "UPDATE project SET projectTitle=?, projectDesc=?, projectImg=? WHERE projectId=?";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$projectTitle, $projectDesc, "/files/projects/$filename", $projectId]);
        } else {
            // If project ID is not provided, it's a new project, so insert it
            $sql = "INSERT INTO project (projectTitle, projectDesc, projectImg, studentID) VALUES (?, ?, ?, ?)";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$projectTitle, $projectDesc, "/files/projects/$filename", $studentId]);
        }

        return $this->sendPayload(null, "success", "Successfully updated/added records.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        return $this->sendPayload(null, "error", $errmsg, null);
    }
}


    // public function edit_project($data, $id)
    // {

    //     try {
    //         $sql = "UPDATE project SET projectTitle = ?,projectDesc = ?,projectImg = ?, WHERE studentID = ?";
    //         $statement = $this->pdo->prepare($sql);
    //         $statement->execute([
    //             $data->projectTitle,
    //             $data->projectDesc,
    //             $data->projectImg,
    //             $id
    //         ]);

    //         return $this->sendPayload(null, "success", "Successfully updated.", null);
    //     } catch (\PDOException $e) {
    //         $errmsg = $e->getMessage();
    //         $code = 400;
    //     }
    // }


    // Add this code to post.php

public function delete_project($id)
{
    try {
        $sql = "DELETE FROM project WHERE projectID = ?";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);

        return $this->sendPayload(null, "success", "Successfully deleted project.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        return $this->sendPayload(null, "error", $errmsg, null);
    }
}


    
    public function add_service($data)
    {
        $serviceTitle = $data['serviceTitle'];
        $serviceDesc = $data['serviceDesc'];
        $studentId =  $data['studentID'];
            try{

            

            $sql = "INSERT INTO service (serviceTitle, serviceDesc, studentID) VALUES (?, ?, ?)";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$serviceTitle, $serviceDesc, $studentId]);

            return $this->sendPayload(null, "success", "Successfully added records.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            return $this->sendPayload(null, "error", $errmsg, null);
    }

}

public function edit_service($data)
{
    $serviceID = $data['serviceID']; // Assuming you pass the service ID for editing
    $serviceTitle = $data['serviceTitle'];
    $serviceDesc = $data['serviceDesc'];
    $studentId =  $data['studentID'];

    try {
        // Check if the service ID is provided for editing
        if (!empty($serviceID)) {
            // If service ID is provided, update the existing service
            $sql = "UPDATE service SET serviceTitle=?, serviceDesc=? WHERE serviceID=?";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$serviceTitle, $serviceDesc, $serviceID]);
        } else {
            // If service ID is not provided, it's a new service, so insert it
            $sql = "INSERT INTO service (serviceTitle, serviceDesc, studentID) VALUES (?, ?, ?)";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([$serviceTitle, $serviceDesc, $studentId]);
        }

        return $this->sendPayload(null, "success", "Successfully updated/added records.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        return $this->sendPayload(null, "error", $errmsg, null);
    }
}



    // Add this method to post.php

public function delete_service($id)
{
    try {
        $sql = "DELETE FROM service WHERE serviceID = ?";

        $statement = $this->pdo->prepare($sql);

        $statement->execute([$id]);

        return $this->sendPayload(null, "success", "Successfully deleted.", null);
    } catch (\PDOException $e) {
        $errmsg = $e->getMessage();
        // You can choose to handle errors differently here
    }

    return $this->sendPayload(null, "Unsuccessfully", $errmsg, null);
}




    public function login($data)
    {
        try {
            $sql = "SELECT * FROM students WHERE email = :email";
            $statement = $this->pdo->prepare($sql);

            // Bind email parameter
            $statement->bindParam(':email', $data->email);

            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_OBJ);
            // $result = $this->executeQuery($sqlString);

            if ($user) {
                // Check if password matches
                if (password_verify($data->password, $user->password)) {
                    return $this->sendPayload($user, "success", "Login successful.", null);
                } else {
                    return $this->sendPayload(null, "error", "Incorrect password", null);
                }
            } else {
                return $this->sendPayload(null, "error", "Incorrect email", null);
            }
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
            // Handle error
            // You may want to log the error or return an appropriate response
        }
    }



    // public function login($data)
    // {


    //     try {
    //         $sql = "SELECT * FROM students WHERE email = :email";
    //         $statement = $this->pdo->prepare($sql);

    //         $statement->execute(
    //             [
    //                 $data
    //             ]
    //         );
    //         $enteredPassword = $data->password;
    //         $user = $statement->fetch(PDO::FETCH_OBJ);
    //         $hashedpassword = $user->password;

    //         if (password_verify($enteredPassword, $hashedpassword)) {
    //             return $this->sendPayload(null, "success", "Your data exist.", null);
    //         }
    //     } catch (\PDOException $e) {
    //         $errmsg = $e->getMessage();
    //         $code = 400;
    //     }
    // }
}
