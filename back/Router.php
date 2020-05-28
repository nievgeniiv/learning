<?php
/**
 * Created by IntelliJ IDEA.
 * User: svl
 * Date: 18.10.2018
 * Time: 10:45
 */

interface Router {
	public function route(Route $url): Controller;
}