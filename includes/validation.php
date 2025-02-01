<?php
class Validation
{
    public static function validateUsername($username)
    {
        if (empty($username)) {
            return "Username is required";
        }

        if (strlen($username) < 3 || strlen($username) > 50) {
            return "Username must be between 3 and 50 characters";
        }

        if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
            return "Username can only contain letters, numbers, and underscores";
        }

        return "";
    }

    public static function validateEmail($email)
    {
        if (empty($email)) {
            return "Email is required";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        }

        return "";
    }

    public static function validatePassword($password, $confirmPassword = null)
    {
        if (empty($password)) {
            return "Password is required";
        }

        if (strlen($password) < 8) {
            return "Password must be at least 8 characters long";
        }

        if (empty($confirmPassword)) {
            return "Confirm password is required";
        }

        if ($confirmPassword !== null && $password !== $confirmPassword) {
            return "Passwords do not match";
        }

        return "";
    }

    public static function validateLogin($email, $password)
    {
        $errors = [];

        if (empty($email)) {
            $errors['email'] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format";
        }

        if (empty($password)) {
            $errors['password'] = "Password is required";
        } elseif (strlen($password) < 8) {
            $errors['password'] = "Password must be at least 8 characters long";
        }

        return $errors;
    }
}
