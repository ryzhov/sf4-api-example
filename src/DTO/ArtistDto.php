<?php


namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ArtistDto implements ValidatedDTOInterface
{
    /**
     * @var string
     *
     * @Assert\NotNull
     * @Assert\Length(min = 2, minMessage = "Artist name must be at least {{ limit }} characters long")
     */
    public $name;
}