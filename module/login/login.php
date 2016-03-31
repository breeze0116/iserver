<?php
class Login {
	public function login() {

	}

	public function doLogin($get) {
		return "Login success:" . $get["pname"];
	}
}