<?php
require_once "global.php";

class Post extends GlobalMethods
{

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
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

            $sql = "INSERT INTO students (firstName, lastName, email, password, address, contacts, course,sex,birthdate, school) VALUES (?,?,?,?,?,?,?,?,?,?)";

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
            $sql = "UPDATE students SET firstName = ?, lastName = ?, email = ?, password = ?, address = ?, contacts = ?, course = ?, aboutme = ? WHERE studentID = ?";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                $data->firstName,
                $data->lastName,
                $data->email,
                $data->password,
                $data->address,
                $data->contacts,
                $data->course,
                $data->aboutme,
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
            $sql = "INSERT INTO skills (nameOfSkill, studentID) VALUES (?,?,?)";

            $statement = $this->pdo->prepare($sql);

            $statement->execute(
                [

                    $data->nameOfSkill,
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
            $sql = "UPDATE skills SET nameOfSkill = ? WHERE studentID = ?";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([

                $data->nameOfSkill,
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
            $sql = " DELETE FROM skill WHERE studentID = ?";


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
                    $data->percentage,
                    $data->description,
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


    public function add_accomplishments($data)
    {
        try {
            $sql = "INSERT INTO accomplishments (highSchool, SeniorHS, college, addText, studentID) VALUES (?,?,?,?,?)";

            $statement = $this->pdo->prepare($sql);

            $statement->execute(
                [
                    $data->highSchool,
                    $data->SeniorHS,
                    $data->college,
                    $data->addText,
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

    public function edit_accomplishments($data, $id)
    {

        try {
            $sql = "UPDATE accomplishments SET highschool = ?, SeniorHS = ?, college = ?, addText = ?, description = ? WHERE studentID = ?";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                $data->highSchool,
                $data->SeniorHS,
                $data->college,
                $data->addText,
                $data->description,
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
            $sql = " DELETE FROM accomplishments WHERE studentID = ?";


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
        try {
            $sql = "INSERT INTO aboutme (text,studentID) VALUES (?,?)";

            $statement = $this->pdo->prepare($sql);

            $statement->execute(
                [
                    $data->text,
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

    public function edit_aboutme($data, $id)
    {

        try {
            $sql = "UPDATE aboutme SET text = ?WHERE studentID = ?";
            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                $data->text,
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


    public function login($data)
    {
        try {
            $sql = "SELECT * FROM students WHERE email = :email";
            $statement = $this->pdo->prepare($sql);

            // Bind email parameter
            $statement->bindParam(':email', $data->email);

            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_OBJ);

            if ($user) {
                // Check if password matches
                if (password_verify($data->password, $user->password)) {
                    return $this->sendPayload(null, "success", "Login successful.", null);
                } else {
                    return $this->sendPayload(null, "error", "Incorrect password.", null);
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
