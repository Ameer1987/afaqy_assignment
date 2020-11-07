<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Vehicle
 *
 * @ORM\Table(name="vehicles", uniqueConstraints={@ORM\UniqueConstraint(name="vehicles_plate_number_unique", columns={"plate_number"}), @ORM\UniqueConstraint(name="vehicles_vin_unique", columns={"vin"}), @ORM\UniqueConstraint(name="vehicles_name_unique", columns={"name"}), @ORM\UniqueConstraint(name="vehicles_imei_unique", columns={"imei"}), @ORM\UniqueConstraint(name="vehicles_license_unique", columns={"license"})})
 * @ORM\Entity(repositoryClass="App\Repository\VehicleRepository")
 */
class Vehicle
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="plate_number", type="string", length=255, nullable=true)
     */
    private $plateNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="imei", type="string", length=255, nullable=true)
     */
    private $imei;

    /**
     * @var string|null
     *
     * @ORM\Column(name="vin", type="string", length=255, nullable=true)
     */
    private $vin;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="year", type="string", length=255, nullable=true)
     */
    private $year;

    /**
     * @var string|null
     *
     * @ORM\Column(name="license", type="string", length=255, nullable=true)
     */
    private $license;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Service", mappedBy="vehicle")
     */
    private $services;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FuelEntry", mappedBy="vehicle")
     */
    private $fuelEntries;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InsurancePayment", mappedBy="vehicle")
     */
    private $insurancePayments;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->fuelEntries = new ArrayCollection();
        $this->insurancePayments = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPlateNumber(): ?string
    {
        return $this->plateNumber;
    }

    public function setPlateNumber(?string $plateNumber): self
    {
        $this->plateNumber = $plateNumber;

        return $this;
    }

    public function getImei(): ?string
    {
        return $this->imei;
    }

    public function setImei(?string $imei): self
    {
        $this->imei = $imei;

        return $this;
    }

    public function getVin(): ?string
    {
        return $this->vin;
    }

    public function setVin(?string $vin): self
    {
        $this->vin = $vin;

        return $this;
    }

    public function getYear(): ?\DateTimeInterface
    {
        return $this->year;
    }

    public function setYear(?\DateTimeInterface $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getLicense(): ?string
    {
        return $this->license;
    }

    public function setLicense(?string $license): self
    {
        $this->license = $license;

        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    /**
     * @return Collection|InsurancePayment[]
     */
    public function getInsurancePayments(): Collection
    {
        return $this->insurancePayments;
    }

    /**
     * @return Collection|FuelEntry[]
     */
    public function getFuelEntries(): Collection
    {
        return $this->fuelEntries;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getExpenses()
    {
        return new ArrayCollection(
            array_merge($this->fuelEntries->toArray(), $this->insurancePayments->toArray(), $this->services->toArray())
        );
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setVehicle($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getVehicle() === $this) {
                $service->setVehicle(null);
            }
        }

        return $this;
    }

    public function addFuelEntry(FuelEntry $fuelEntry): self
    {
        if (!$this->fuelEntries->contains($fuelEntry)) {
            $this->fuelEntries[] = $fuelEntry;
            $fuelEntry->setVehicle($this);
        }

        return $this;
    }

    public function removeFuelEntry(FuelEntry $fuelEntry): self
    {
        if ($this->fuelEntries->removeElement($fuelEntry)) {
            // set the owning side to null (unless already changed)
            if ($fuelEntry->getVehicle() === $this) {
                $fuelEntry->setVehicle(null);
            }
        }

        return $this;
    }

    public function addInsurancePayment(InsurancePayment $insurancePayment): self
    {
        if (!$this->insurancePayments->contains($insurancePayment)) {
            $this->insurancePayments[] = $insurancePayment;
            $insurancePayment->setVehicle($this);
        }

        return $this;
    }

    public function removeInsurancePayment(InsurancePayment $insurancePayment): self
    {
        if ($this->insurancePayments->removeElement($insurancePayment)) {
            // set the owning side to null (unless already changed)
            if ($insurancePayment->getVehicle() === $this) {
                $insurancePayment->setVehicle(null);
            }
        }

        return $this;
    }
}
