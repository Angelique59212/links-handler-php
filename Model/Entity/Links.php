<?php


use App\Model\Entity\AbstractEntity;

class Links extends AbstractEntity
{
    private string $name;
    private string $image;
    private User $linksUser;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Links
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return Links
     */
    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return User
     */
    public function getLinksUser(): User
    {
        return $this->linksUser;
    }

    /**
     * @param User $linksUser
     * @return Links
     */
    public function setLinksUser(User $linksUser): self
    {
        $this->linksUser = $linksUser;
        return $this;
    }


}