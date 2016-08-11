<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovieTypeAssignment
 *
 * @ORM\Table(name="movie_type_assignments", indexes={@ORM\Index(name="fk_movie", columns={"movie_id"}), @ORM\Index(name="fk_movie_type", columns={"movie_type_identifier"})})
 * @ORM\Entity(repositoryClass="Application\Repository\MovieTypeAssignment")
 */
class MovieTypeAssignment
{

    const NEW_TYPE = 'NEW';
    const REGULAR_TYPE = 'REGULAR';
    const OLD_TYPE = 'OLD';

    /**
     * @var integer
     *
     * @ORM\Column(name="movie_type_assignment_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $movieTypeAssignmentId;

    /**
     * @var string
     *
     * @ORM\Column(name="movie_type_identifier", type="string", nullable=false, columnDefinition="ENUM('NEW', 'REGULAR', 'OLD')"))
     */
    private $movieTypeIdentifier;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date", nullable=true)
     */
    private $endDate;

    /**
     * @var \Application\Entity\Movie
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Movie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="movie_id", referencedColumnName="movie_id")
     * })
     */
    private $movie;



    /**
     * Get movieTypeAssignmentId
     *
     * @return integer
     */
    public function getMovieTypeAssignmentId()
    {
        return $this->movieTypeAssignmentId;
    }

    /**
     * Set movieTypeIdentifier
     *
     * @param string $movieTypeIdentifier
     *
     * @return MovieTypeAssignment
     */
    public function setMovieTypeIdentifier($movieTypeIdentifier)
    {

        if (!in_array($movieTypeIdentifier, array(self::NEW_TYPE, self::REGULAR_TYPE, self::OLD_TYPE))) {
            throw new \InvalidArgumentException("Invalid movie type identifier");
        }

        $this->movieTypeIdentifier = $movieTypeIdentifier;

        return $this;
    }

    /**
     * Get movieTypeIdentifier
     *
     * @return string
     */
    public function getMovieTypeIdentifier()
    {
        return $this->movieTypeIdentifier;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return MovieTypeAssignment
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return MovieTypeAssignment
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set movie
     *
     * @param \Application\Entity\Movie $movie
     *
     * @return MovieTypeAssignment
     */
    public function setMovie(\Application\Entity\Movie $movie = null)
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * Get movie
     *
     * @return \Application\Entity\Movie
     */
    public function getMovie()
    {
        return $this->movie;
    }
}
