<?php

class UserValidation
{
    /**
     * Validate input
     */
    public function validateInput($input = []): array
    {
        $errorInputMessage = [];
        if (isset($input['password'])) {
            $password = $input['password'];
            $errorPassword = $this->validatePassword($password);
            if ($errorPassword) {
                $errorInputMessage['password'] = $errorPassword;
            }
        }

        $errorUsername = $this->validateUsername($input['username'], $input['existUsername']);
        if ($errorUsername) {
            $errorInputMessage['username'] = $errorUsername;
        }

        $errorLastname = $this->validateName($input['lastname']);
        $errorFirstname = $this->validateName($input['firstname']);
        if ($errorLastname || $errorFirstname) {
            $errorInputMessage['error_name'] = ($errorFirstname) ? $errorFirstname : $errorLastname;
        }

        return $errorInputMessage;
    }

    /**
     * Validate password
     */
    public function validatePassword($password): array
    {
        $errorMessage = [];

        if (!preg_match('@[a-zA-Z0-9\w]@', $password) || strlen($password) < 8) {
            $errorMessage['formatted'] = 'Password should be at least 8 characters in length and should include at least one special character, one upper case letter and one number.';
        }

        return $errorMessage;
    }

    /**
     * Validate username
     */
    public function validateUsername($username, $existUsername): array
    {
        $errorMessage = [];

        if (!preg_match("/^[A-Za-z0-9]{6,32}$/", $username)) {
            $errorMessage['formatted'] = 'Username is not include special character, include from 6 to 32 letters!';
        }

        if ($existUsername) {
            $errorMessage['exist'] = 'Username is used for another account!';
        }

        return $errorMessage;
    }

    /**
     * Validate lastname
     */
    public function validateName($stringName): array
    {
        $errorMessage = [];

        if (!preg_match("/^[A-Za-z0-9\s]{1,45}$/", $stringName)) {
            $errorMessage['formatted'] = 'Lastname, Firstname allow number, letters and include from 1 to 45 characters!';
        }

        return $errorMessage;
    }
}
