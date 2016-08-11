<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Movie
 *
 * @ORM\Table(name="movies", indexes={@ORM\Index(name="idx_title", columns={"title"})})
 * @ORM\Entity(repositoryClass="Application\Repository\Movie")
 */
class Movie
{
    /**
     * @var integer
     *
     * @ORM\Column(name="movie_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $movieId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="release_date", type="date", nullable=true)
     */
    private $releaseDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="number_of_copies", type="integer", nullable=true)
     */
    private $numberOfCopies = '0';



    /**
     * Get movieId
     *
     * @return integer
     */
    public function getMovieId()
    {
        return $this->movieId;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Movie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set releaseDate
     *
     * @param \DateTime $releaseDate
     *
     * @return Movie
     */
    public function setReleaseDate($releaseDate)
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * Get releaseDate
     *
     * @return \DateTime
     */
    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    /**
     * Set numberOfCopies
     *
     * @param integer $numberOfCopies
     *
     * @return Movie
     */
    public function setNumberOfCopies($numberOfCopies)
    {
        $this->numberOfCopies = $numberOfCopies;

        return $this;
    }

    /**
     * Get numberOfCopies
     *
     * @return integer
     */
    public function getNumberOfCopies()
    {
        return $this->numberOfCopies;
    }


    public function toArray(){
        return [
            'movieId' => $this->getMovieId(),
            'title' => $this->getTitle(),
            'releaseDate' => ($this->getReleaseDate()) ? $this->getReleaseDate()->format("Y-m-d") : null,
            'numberOfCopies' => $this->getNumberOfCopies()
        ];
    }


    public function fromArray($data)
    {
        if(isset($data['movieId'])) $this->movieId = $data['movieId'];
        if(isset($data['title'])) $this->title = $data['title'];
        if(isset($data['releaseDate'])) $this->releaseDate = ($data['releaseDate'] instanceof \DateTime) ? $data['releaseDate'] : new \DateTime($data['releaseDate']);
        if(isset($data['numberOfCopies'])) $this->numberOfCopies = $data['numberOfCopies'];
    }
}
