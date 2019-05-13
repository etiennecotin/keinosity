<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectTypeRepository")
 */
class ProjectType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="type")
     */
    private $projects;

    private $createAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return ProjectType
     */
    public function setName(string $name): ProjectType
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName():? string
    {
        return $this->name;
    }
}
