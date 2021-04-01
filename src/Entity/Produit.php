<?php

namespace App\Entity;

use DateTimeInterface;
use App\Entity\Categorie;
use App\Entity\Commentaires;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contenu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prix;

   
    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categories;

   
    /**
     * @ORM\OneToMany(targetEntity=CommentProduit::class, mappedBy="produits")
     */
    private $commentProduits;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="produits")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity=Boutiques::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $boutiques;





    public function __construct()
    {
        
        $this->commentProduits = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }
    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDateCreation(): ?DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }



    public function getCategories(): ?Categorie
    {
        return $this->categories;
    }

    public function setCategories(?Categorie $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Collection|CommentProduit[]
     */
    public function getCommentProduits(): Collection
    {
        return $this->commentProduits;
    }

    public function addCommentProduit(CommentProduit $commentProduit): self
    {
        if (!$this->commentProduits->contains($commentProduit)) {
            $this->commentProduits[] = $commentProduit;
            $commentProduit->setProduits($this);
        }

        return $this;
    }

    public function removeCommentProduit(CommentProduit $commentProduit): self
    {
        if ($this->commentProduits->removeElement($commentProduit)) {
            // set the owning side to null (unless already changed)
            if ($commentProduit->getProduits() === $this) {
                $commentProduit->setProduits(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProduits($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProduits() === $this) {
                $comment->setProduits(null);
            }
        }

        return $this;
    }

    public function getBoutiques(): ?Boutiques
    {
        return $this->boutiques;
    }

    public function setBoutiques(?Boutiques $boutiques): self
    {
        $this->boutiques = $boutiques;

        return $this;
    }


   

   
}
