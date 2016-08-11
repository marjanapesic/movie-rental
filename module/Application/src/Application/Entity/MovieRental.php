<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovieRental
 *
 * @ORM\Table(name="movie_rentals", indexes={@ORM\Index(name="dates", columns={"start_date", "end_date", "return_date"}), @ORM\Index(name="fk_movie_rentals_movie", columns={"movie_id"}), @ORM\Index(name="fk_movie_rentals_user_id", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="Application\Repository\MovieRental")
 */
class MovieRental
{
    /**
     * @var integer
     *
     * @ORM\Column(name="movie_rental_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $movieRentalId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date", nullable=false)
     */
    private $endDate;

    /**
     * @var float
     *
     * @ORM\Column(name="price_amount", type="float", precision=10, scale=0, nullable=false)
     */
    private $priceAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="price_currency", type="string", length=7, nullable=false)
     */
    private $priceCurrency;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="return_date", type="date", nullable=true)
     */
    private $returnDate;

    /**
     * @var float
     *
     * @ORM\Column(name="subcharge_amount", type="float", precision=10, scale=0, nullable=true)
     */
    private $subchargeAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="subcharge_currency", type="string", length=7, nullable=true)
     */
    private $subchargeCurrency;

    /**
     * @var integer
     *
     * @ORM\Column(name="bonus_points", type="integer", nullable=true)
     */
    private $bonusPoints = '0';

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
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;



    /**
     * Get movieRentalId
     *
     * @return integer
     */
    public function getMovieRentalId()
    {
        return $this->movieRentalId;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return MovieRental
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
     * @return MovieRental
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
     * Set priceAmount
     *
     * @param float $priceAmount
     *
     * @return MovieRental
     */
    public function setPriceAmount($priceAmount)
    {
        $this->priceAmount = $priceAmount;

        return $this;
    }

    /**
     * Get priceAmount
     *
     * @return float
     */
    public function getPriceAmount()
    {
        return $this->priceAmount;
    }

    /**
     * Set priceCurrency
     *
     * @param string $priceCurrency
     *
     * @return MovieRental
     */
    public function setPriceCurrency($priceCurrency)
    {
        $this->priceCurrency = $priceCurrency;

        return $this;
    }

    /**
     * Get priceCurrency
     *
     * @return string
     */
    public function getPriceCurrency()
    {
        return $this->priceCurrency;
    }

    /**
     * Set returnDate
     *
     * @param \DateTime $returnDate
     *
     * @return MovieRental
     */
    public function setReturnDate($returnDate)
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    /**
     * Get returnDate
     *
     * @return \DateTime
     */
    public function getReturnDate()
    {
        return $this->returnDate;
    }

    /**
     * Set subchargeAmount
     *
     * @param float $subchargeAmount
     *
     * @return MovieRental
     */
    public function setSubchargeAmount($subchargeAmount)
    {
        $this->subchargeAmount = $subchargeAmount;

        return $this;
    }

    /**
     * Get subchargeAmount
     *
     * @return float
     */
    public function getSubchargeAmount()
    {
        return $this->subchargeAmount;
    }

    /**
     * Set subchargeCurrency
     *
     * @param string $subchargeCurrency
     *
     * @return MovieRental
     */
    public function setSubchargeCurrency($subchargeCurrency)
    {
        $this->subchargeCurrency = $subchargeCurrency;

        return $this;
    }

    /**
     * Get subchargeCurrency
     *
     * @return string
     */
    public function getSubchargeCurrency()
    {
        return $this->subchargeCurrency;
    }

    /**
     * Set bonusPoints
     *
     * @param integer $bonusPoints
     *
     * @return MovieRental
     */
    public function setBonusPoints($bonusPoints)
    {
        $this->bonusPoints = $bonusPoints;

        return $this;
    }

    /**
     * Get bonusPoints
     *
     * @return integer
     */
    public function getBonusPoints()
    {
        return $this->bonusPoints;
    }

    /**
     * Set movie
     *
     * @param \Application\Entity\Movie $movie
     *
     * @return MovieRental
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

    /**
     * Set user
     *
     * @param \Application\Entity\User $user
     *
     * @return MovieRental
     */
    public function setUser(\Application\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
