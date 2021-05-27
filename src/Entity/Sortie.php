<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Donne un nom à ta sortie !")
     * @Assert\Length(max=255, maxMessage="Donne un nom un peu plus court à ta sortie!")
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @Assert\NotBlank(message="La date et heure de la sortie sont obligatoires.")
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDebut;

    /**
     * @Assert\Type(
     *     type="integer",
     *     message="Donne la durée de ta sortie en minutes, sans virgule !"
     * )
     * @Assert\NotBlank(message="La durée est obligatoire.")
     * @ORM\Column(type="integer")
     */
    private $duree;

    /**
     * @Assert\LessThan(
     *     propertyPath="dateHeureDebut",
     *     message="La date limite d'inscription doit être antérieure à la date de la sortie !"
     * )
     * @Assert\Expression(
     *     "this.getDateLimiteInscription() >= this.getCurrentDate()",
     *     message="La date limite d'inscription doit être postérieure à la date du jour !"
     * )
     * @Assert\NotBlank(message="La date limite d'inscriptions est obligatoire.")
     * @ORM\Column(type="datetime")
     */
    private $dateLimiteInscription;

    /**
     * @Assert\Type(
     *     type="integer",
     *     message="Difficile de se couper en deux ! Rentre un chiffre entier !"
     * )
     * @Assert\NotBlank(message="Le nombre max d'inscriptions est obligatoire.")
     * @ORM\Column(type="integer")
     */
    private $nbInscriptionsMax;

    /**
     * @Assert\Length(min=2, minMessage="Tu peux nous en dire un peu plus ?")
     * @ORM\Column(type="text", nullable=true)
     */
    private $infosSortie;

    /**
     * @Assert\Length(min=2, minMessage="Tu peux nous en dire un peu plus ?")
     * @ORM\Column(type="text", nullable=true)
     */
    private $motifAnnulation;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, inversedBy="sorties", cascade="persist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class, inversedBy="sorties")
     */
    private $organisateur;

    /**
     * @ORM\ManyToMany(targetEntity=Participant::class, inversedBy="inscriptions")
     */
    private $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getCurrentDate(){
        return new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(int $nbInscriptionsMax): self
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(?string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }

    public function getEtat(): ?etat
    {
        return $this->etat;
    }

    public function setEtat(?etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getMotifAnnulation(): ?string
    {
        return $this->motifAnnulation;
    }

    public function setMotifAnnulation(?string $motifAnnulation): self
    {
        $this->motifAnnulation = $motifAnnulation;

        return $this;
    }


    public function getOrganisateur(): ?Participant
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Participant $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }

}
