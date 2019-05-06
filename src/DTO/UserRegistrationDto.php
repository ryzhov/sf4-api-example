<?php


namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;


class UserRegistrationDto implements ValidatedDTOInterface
{
    /**
     * @var string
     *
     * @Assert\NotNull
     * @Assert\Email
     */
    public $email;

    /**
     * @var string
     *
     * @Assert\NotNull
     * @Assert\Length(min = 8, minMessage = "Password must be at least {{ limit }} characters long")
     */
    public $password;
}