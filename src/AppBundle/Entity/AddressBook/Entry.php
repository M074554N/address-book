<?php

namespace AppBundle\Entity\AddressBook;

use AppBundle\Validator\Constraints\Phone;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Entry
 *
 * @ORM\Table(name="address_book_entries")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AddressBook\EntryRepository")
 */
class Entry
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
	 * @var string
	 *
	 * @ORM\Column(name="firstname", type="string", length=50)
	 */
	private $firstname;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="lastname", type="string", length=50)
	 */
	private $lastname;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="street", type="string", length=255)
	 */
	private $street;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="zip", type="string", length=12)
	 */
	private $zip;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="city", type="string", length=120)
	 */
	private $city;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="country", type="string", length=2)
	 */
	private $country;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="phone", type="string", length=25)
	 */
	private $phone;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="birthday", type="date")
	 */
	private $birthday;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=255, unique=true)
	 */
	private $email;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="image", type="string", length=255, nullable=true)
	 */
	private $image;

	public static function loadValidatorMetadata(ClassMetadata $metadata)
	{
		$metadata->addPropertyConstraint('firstname', new NotBlank());
		$metadata->addPropertyConstraint('lastname', new NotBlank());
		$metadata->addPropertyConstraint('street', new NotBlank());
		$metadata->addPropertyConstraint('zip', new NotBlank());

		$metadata->addPropertyConstraint('city', new NotBlank());

		$metadata->addPropertyConstraint('country', new NotBlank());
		$metadata->addPropertyConstraint('country', new Country());

		$metadata->addPropertyConstraint('birthday', new NotBlank());
		$metadata->addPropertyConstraint(
			'birthday',
			new Type(\DateTime::class)
		);

		$metadata->addPropertyConstraint('phone', new NotBlank());
		$metadata->addPropertyConstraint('phone', new Phone());

		$metadata->addPropertyConstraint('email', new NotBlank());
		$metadata->addPropertyConstraint('email', new Email());

		$metadata->addPropertyConstraint('image', new Image());
	}

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
	 * Set firstname
	 *
	 * @param string $firstname
	 *
	 * @return Entry
	 */
	public function setFirstname($firstname)
	{
		$this->firstname = $firstname;

		return $this;
	}

	/**
	 * Get firstname
	 *
	 * @return string
	 */
	public function getFirstname()
	{
		return $this->firstname;
	}

	/**
	 * Set lastname
	 *
	 * @param string $lastname
	 *
	 * @return Entry
	 */
	public function setLastname($lastname)
	{
		$this->lastname = $lastname;

		return $this;
	}

	/**
	 * Get lastname
	 *
	 * @return string
	 */
	public function getLastname()
	{
		return $this->lastname;
	}

	/**
	 * Set street
	 *
	 * @param string $street
	 *
	 * @return Entry
	 */
	public function setStreet($street)
	{
		$this->street = $street;

		return $this;
	}

	/**
	 * Get street
	 *
	 * @return string
	 */
	public function getStreet()
	{
		return $this->street;
	}

	/**
	 * Set zip
	 *
	 * @param string $zip
	 *
	 * @return Entry
	 */
	public function setZip($zip)
	{
		$this->zip = $zip;

		return $this;
	}

	/**
	 * Get zip
	 *
	 * @return string
	 */
	public function getZip()
	{
		return $this->zip;
	}

	/**
	 * Set city
	 *
	 * @param string $city
	 *
	 * @return Entry
	 */
	public function setCity($city)
	{
		$this->city = $city;

		return $this;
	}

	/**
	 * Get city
	 *
	 * @return string
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * Set country
	 *
	 * @param string $country
	 *
	 * @return Entry
	 */
	public function setCountry($country)
	{
		$this->country = $country;

		return $this;
	}

	/**
	 * Get country
	 *
	 * @return string
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * Set phone
	 *
	 * @param string $phone
	 *
	 * @return Entry
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
	 * Set birthday
	 *
	 * @param \DateTime $birthday
	 *
	 * @return Entry
	 */
	public function setBirthday($birthday)
	{
		$this->birthday = $birthday;

		return $this;
	}

	/**
	 * Get birthday
	 *
	 * @return \DateTime
	 */
	public function getBirthday()
	{
		return $this->birthday;
	}

	/**
	 * Set email
	 *
	 * @param string $email
	 *
	 * @return Entry
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
	 * Set image
	 *
	 * @param string $image
	 *
	 * @return Entry
	 */
	public function setImage($image)
	{
		$this->image = $image;

		return $this;
	}

	/**
	 * Get image
	 *
	 * @return string
	 */
	public function getImage()
	{
		return $this->image;
	}
}

