<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Project", mappedBy="type")
     */
    private $projects;


    /**
     * @var \DateTime|null
     * @Gedmo\Timestampable(on="create")
     * @Groups({"project"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createAt;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->name;
    }

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

    /**
     * @return ArrayCollection
     */
    public function getProjects(): ArrayCollection
    {
        return $this->projects;
    }

    /**
     * @param ArrayCollection $projects
     * @return ProjectType
     */
    public function setProjects(ArrayCollection $projects): ProjectType
    {
        $this->projects = $projects;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreateAt(): ?\DateTime
    {
        return $this->createAt;
    }

    /**
     * @param \DateTime|null $createAt
     * @return ProjectType
     */
    public function setCreateAt(?\DateTime $createAt): ProjectType
    {
        $this->createAt = $createAt;
        return $this;
    }
}
