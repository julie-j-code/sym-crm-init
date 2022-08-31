<?php

namespace App\Entity;

class PropertySearch
{

   private $name;
   private $tel;

   
   public function getName(): ?string
   {
       return $this->name;
   }

   public function setName(string $name): self
   {
       $this->name = $name;

       return $this;
   }

   public function getTel(): ?string
   {
       return $this->tel;
   }

   public function setTel(string $tel): self
   {
       $this->tel = $tel;

       return $this;
   }

   
}