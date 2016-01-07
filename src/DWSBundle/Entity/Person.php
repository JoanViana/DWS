<?php

namespace DWSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Person
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DWSBundle\Entity\PersonRepository")
 */
class Person
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     * 
     * @ORM\Column(name="age", type="integer", nullable=true)
     * 
     * @Assert\Range(
     *      min = 18,
     *      max = 90,
     *      minMessage = "You must be at least {{ limit }} years old",
     *      maxMessage = "You cannot be older than {{ limit }} years old")
     */
    private $age;

    /**
     * @var \Date
     * 
     * @Assert\NotBlank()
     * @Assert\Date()
     * @ORM\Column(name="birthDate", type="date")
     */
    private $birthDate;

    /**
     * @var integer
     *  
     * @ORM\Column(name="height", type="integer", nullable=true)
     * 
     * @Assert\Range(
     *      min = 100,
     *      max = 300,
     *      minMessage = "You must be at least {{ limit }}cm height",
     *      maxMessage = "You cannot be taller than {{ limit }}cm height")
     * 
     */
    private $height;

    /**
     * @var string
     * 
     * @Assert\NotBlank()
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.")
     */
    private $email;
    
    /**
     * @var string
     * 
     * @Assert\NotBlank()
     * @ORM\Column(name="phone", type="integer")
     * @Assert\Regex("/\d{9,12}/")
     */
    private $phone;

    /**
     * @var string
     * 
     * @Assert\NotBlank()
     * @ORM\Column(name="gender", type="string", length=255, nullable=true)
     * @Assert\Choice(
     * 			choices = { "m","f" })
     */
    private $gender;

    /**
     * @var integer
     *
     * @ORM\Column(name="descends", type="integer", nullable=true)
     * 
     * @Assert\Range(min = 0, max = 20)
     */
    private $descends;

    /**
     * @var boolean
     * 
     * @ORM\Column(name="vehicle", type="boolean", nullable=true)
     * 
     */
    private $vehicle;

    /**
     * @var string
     *
     * @ORM\Column(name="preferredLanguage", type="string", length=255, nullable=true)
     * 
     * @Assert\Choice(
     * 			choices = { "Java", "C", "C++", "Python", "C#", "PHP" })
     */
    private $preferredLanguage;

    /**
     * @var integer
     *
     * @ORM\Column(name="englishLevel", type="integer", nullable=true)
     * 
     * @Assert\Choice(choices = { 1,2,3,4,5,6 })
     */
    private $englishLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="personalWebSite", type="string", length=255, nullable=true)
     * 
     * @Assert\Url()
     */
    private $personalWebSite;

    /**
     * @var string
     *
     * @ORM\Column(name="cardNumber", type="string", length=255, nullable=true)
     * 
     * @Assert\CardScheme(
     * 			schemes={"VISA","MASTERCARD","MAESTRO"},
     *     		message="Your credit card number is invalid.")
     */
    private $cardNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="IBAN", type="string", length=255, nullable=true)
     * 
     * @Assert\Iban(
     * 			message="This is not a valid International Bank Account Number (IBAN)")
     */
    private $iBAN;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Person
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return Person
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set birthDate
     *
     * @param \Date $birthDate
     *
     * @return Person
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \Date
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return Person
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Person
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * Set phone
     *
     * @param integer $phone
     *
     * @return Person
     */
    public function setPhone($phone)
    {
    	$this->phone = $phone;
    
    	return $this;
    }
    
    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
    	return $this->phone;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Person
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set descends
     *
     * @param integer $descends
     *
     * @return Person
     */
    public function setDescends($descends)
    {
        $this->descends = $descends;

        return $this;
    }

    /**
     * Get descends
     *
     * @return integer
     */
    public function getDescends()
    {
        return $this->descends;
    }

    /**
     * Set vehicle
     *
     * @param boolean $vehicle
     *
     * @return Person
     */
    public function setVehicle($vehicle)
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * Get vehicle
     *
     * @return boolean
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }

    /**
     * Set preferredLanguage
     *
     * @param string $preferredLanguage
     *
     * @return Person
     */
    public function setPreferredLanguage($preferredLanguage)
    {
        $this->preferredLanguage = $preferredLanguage;

        return $this;
    }

    /**
     * Get preferredLanguage
     *
     * @return string
     */
    public function getPreferredLanguage()
    {
        return $this->preferredLanguage;
    }

    /**
     * Set englishLevel
     *
     * @param string $englishLevel
     *
     * @return Person
     */
    public function setEnglishLevel($englishLevel)
    {
        $this->englishLevel = $englishLevel;

        return $this;
    }

    /**
     * Get englishLevel
     *
     * @return string
     */
    public function getEnglishLevel()
    {
        return $this->englishLevel;
    }

    /**
     * Set personalWebSite
     *
     * @param string $personalWebSite
     *
     * @return Person
     */
    public function setPersonalWebSite($personalWebSite)
    {
        $this->personalWebSite = $personalWebSite;

        return $this;
    }

    /**
     * Get personalWebSite
     *
     * @return string
     */
    public function getPersonalWebSite()
    {
        return $this->personalWebSite;
    }

    /**
     * Set cardNumber
     *
     * @param string $cardNumber
     *
     * @return Person
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    /**
     * Get cardNumber
     *
     * @return string
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * Set iBAN
     *
     * @param string $iBAN
     *
     * @return Person
     */
    public function setIBAN($iBAN)
    {
        $this->iBAN = $iBAN;

        return $this;
    }

    /**
     * Get iBAN
     *
     * @return string
     */
    public function getIBAN()
    {
        return $this->iBAN;
    }
}

