<?php


class ServiceLoadFile
{
  public function LoadFile() : bool {
    $path = '/home/oksana/www/Learning1/files/';
    return move_uploaded_file($_FILES['image']['tmp_name'], $path  . basename($_FILES['image']['name']));
  }
}