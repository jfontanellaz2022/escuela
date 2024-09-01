<?php 

class Core
{

  public function __construct()
  {
    $this->getUrl();
  }

  public function getUrl()
  {
    echo $_GET['url'];
  }
}