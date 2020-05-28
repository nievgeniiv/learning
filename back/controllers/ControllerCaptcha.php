<?php


class ControllerCaptcha extends Controller
{

  public function run()
  {
    // TODO: Implement run() method.
    ServiceCapcha::CreateCaptcha();
  }
}