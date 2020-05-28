<?php


class ServiceAddTable
{
  protected static $html;

  public static function addTable(string $atr)
  {
    self::$html = '<table '.$atr.'>';
  }

  public static function addHead(string $atr)
  {
    self::$html .= '<thead '.$atr.'>';
  }

  public static function addBody(string $atr)
  {
    self::$html .= '<tbody '.$atr.'>';
  }

  public static function addTR(string $atr)
  {
    self::$html .= '<tr '.$atr.'>';
  }

  public static function addTH(string $atr, string $data)
  {
    self::$html .= '<th '.$atr.'>'.$data.'</th>';
  }

  public static function addTD(string $atr, string $data)
  {
    self::$html .= '<td '.$atr.'>'.$data.'</td>';
  }

  public static function closeTR()
  {
    self::$html .= '</tr>';
  }

  public static function closeHead()
  {
    self::$html .= '</thead>';
  }

  public static function closeBody()
  {
    self::$html .= '</tbody>';
  }

  public static function closeTable()
  {
    self::$html .= '</table>';
  }

  public static function getData() : string
  {
    return self::$html;
  }
}