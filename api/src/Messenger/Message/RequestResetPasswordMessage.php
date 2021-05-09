<?php

namespace App\Messenger\Message;

class RequestResetPasswordMessage
{

    private string $id;
    private string $email;
    private string $resetPasswordToken;

    public function __construct(string $id, string $email, string $resetPasswordToken)
    {
        $this->id = $id;
        $this->email = $email;
        $this->resetPasswordToken = $resetPasswordToken;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getResetPasswordToken(): string
    {
        return $this->resetPasswordToken;
    }
}
