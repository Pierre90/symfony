<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User_vote
 *
 * @ORM\Table(name="user_vote")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\User_voteRepository")
 */
class User_vote
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_voter", type="integer")
     */
    private $idVoter;

    /**
     * @var int
     *
     * @ORM\Column(name="id_noted", type="integer")
     */
    private $idNoted;

    /**
     * @var int
     *
     * @ORM\Column(name="note", type="integer")
     */
    private $note;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idVoter
     *
     * @param integer $idVoter
     *
     * @return User_vote
     */
    public function setIdVoter($idVoter)
    {
        $this->idVoter = $idVoter;

        return $this;
    }

    /**
     * Get idVoter
     *
     * @return int
     */
    public function getIdVoter()
    {
        return $this->idVoter;
    }

    /**
     * Set idNoted
     *
     * @param integer $idNoted
     *
     * @return User_vote
     */
    public function setIdNoted($idNoted)
    {
        $this->idNoted = $idNoted;

        return $this;
    }

    /**
     * Get idNoted
     *
     * @return int
     */
    public function getIdNoted()
    {
        return $this->idNoted;
    }

    /**
     * Set note
     *
     * @param integer $note
     *
     * @return User_vote
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return int
     */
    public function getNote()
    {
        return $this->note;
    }
}

