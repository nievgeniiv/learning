<?php


class ServiceSearch
{
  private $rowTheory;
  private $rowPractic;
  private $rowHelp;

  public function Search(array $data )
  {
    $db = new ModelAdmTheory();
    $this->rowTheory = $db->searchData($data);
    $db = new ModelAdmPractic();
    $this->rowPractic = $db->searchData($data);
    $db = new ModelAdmHelp();
    $this->rowHelp = $db->searchData($data);
  }

  public function getDataSearch() : array
  {
    return [
      'Theory' => $this->rowTheory,
      'Practic' => $this->rowPractic,
      'Help' => $this->rowHelp];
  }
}