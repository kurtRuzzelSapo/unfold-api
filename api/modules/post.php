<?php
require_once "global.php";

class Post extends GlobalMethods
{

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function uploadImage($file, $uploadDirectory)
    {
        // Generate a unique filename to prevent overwriting existing files
        $filename = uniqid() . '_' . $file['name'];
        $targetPath = $uploadDirectory . $filename;

        // Check if the file is an image
        $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (!in_array($fileType, $allowedTypes)) {
            return "Error: Only JPG, JPEG, PNG, and GIF files are allowed.";
        }

        // Move the uploaded file to the specified directory
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return "Error: Failed to move uploaded file.";
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
            // try {
            //     $sql = " UPDATE employees SET FIRST_NAME= ? WHERE EMPLOYEE_ID = ?";

            //     $statement = $this->pdo->prepare($sql);

            //     $statement->execute(
            //         [
            //             $data->FIRST_NAME,
            //             $id
            //         ]
            //     );

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
        try {
            $sql = "INSERT INTO skills (skillTitle,skillDesc, studentID) VALUES (?,?)";

            $statement = $this->pdo->prepare($sql);

            $statement->execute(
                [
                    $data->skillTitle,
                    $data->skillDesc,
                    $data->studentID,
                ]
            );

            return $this->sendPayload(null, "success", "Successfully add records.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }


        return $this->sendPayload(null, "Unsuccessfully", $errmsg, null);
    }


    public function edit_skill($data, $id)
    {

        try {
            $sql = "UPDATE skills SET skillTitle = ?,skillDesc = ?, WHERE studentID = ?";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([

                $data->skillTitle,
                $data->skillDesc,
                $id
            ]);

            return $this->sendPayload(null, "success", "Successfully updated.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }
    }

    public function delete_skill($id)
    {
        try {
            $sql = " DELETE FROM skills WHERE studentID = ?";


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

    public function add_interest($data)
    {
        try {
            $sql = "INSERT INTO interest (title, description, studentID) VALUES (?,?,?)";

            $statement = $this->pdo->prepare($sql);

            $statement->execute(
                [
                    $data->title,
                    $data->description,
                    $data->studentID
                ]
            );

            return $this->sendPayload(null, "success", "Successfully add records.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }


        return $this->sendPayload(null, "Unsuccessfully", $errmsg, null);
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

    public function edit_accomplishments($data, $id)
    {

        try {
            $sql = "UPDATE accomplishments SET accomTitle = ?, accomDesc = ?, accomImg = ?  WHERE studentID = ?";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                $data->accomTitle,
                $data->accomDesc,
                $data->accomImg,
                $data->studentID,
                $id
            ]);

            return $this->sendPayload(null, "success", "Successfully updated.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }
    }

    public function delete_accomplishments($id)
    {
        try {
            $sql = " DELETE FROM accomplishments WHERE accomplishmentID = ?";


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


    public function edit_project($data, $id)
    {

        try {
            $sql = "UPDATE project SET projectTitle = ?,projectDesc = ?,projectImg = ?, WHERE studentID = ?";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                $data->projectTitle,
                $data->projectDesc,
                $data->projectImg,
                $id
            ]);

            return $this->sendPayload(null, "success", "Successfully updated.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }
    }

    public function delete_project($id)
    {
        try {
            $sql = " DELETE FROM project WHERE studentID = ?";


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

    public function add_service($data)
    {
        try {
            $sql = "INSERT INTO service (serviceTitle,serviceDesc,studentID) VALUES (?,?,?)";

            $statement = $this->pdo->prepare($sql);

            $statement->execute(
                [
                    $data->serviceTitle,
                    $data->serviceDesc,
                    $data->studentID,

                ]
            );

            return $this->sendPayload(null, "success", "Successfully add records.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }


        return $this->sendPayload(null, "Unsuccessfully", $errmsg, null);
    }

    public function edit_service($data, $id)
    {

        try {
            $sql = "UPDATE service SET serviceTitle = ?,serviceDesc = ? WHERE studentID = ?";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                $data->serviceTitle,
                $data->serviceDesc,
                $id
            ]);

            return $this->sendPayload(null, "success", "Successfully updated.", null);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }
    }

    public function delete_service($id)
    {
        try {
            $sql = " DELETE FROM project WHERE studentID = ?";


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