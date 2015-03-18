<?php

namespace IntraworQ\Controllers;
use Respect\Validation\Validator as v;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class userController extends BaseController {

	public function userForm() {
		$user = new \IntraworQ\Models\User($this->app->faker->lastName, $this->app->faker->firstNameMale);
		$this->app->render('user.tpl', ['user' => $user,'messages'=>array()]);
	}

	public function userAjax() {
		$renderer = $this->app->debugBar->getJavascriptRenderer();
		$this->app->render('user_ajax.tpl');
	}

	public function userList() {
		for($i=0;$i<=10;$i++) {
			$users[] = new \IntraworQ\Models\User($this->app->faker->lastName, $this->app->faker->firstNameMale,$this->app->faker->text);
		}
		$this->app->render('userList.tpl', ['users' => $users]);
	}

	public function userSave() {
		$name = $this->app->request->post('name');
		$firstName = $this->app->request->post('firstName');
		$user = new \IntraworQ\Models\User($name,$firstName);
		$messages = array();

		$userValidator = $this->app->v
				->attribute('name', v::string()->length(4,32))
				->attribute('firstName', v::string()->length(4,32));
		try {
			$userValidator->assert($user);
			$this->app->log->info("POST: \"{$name} {$firstName}\" created");
		} catch (Respect\Validation\Exceptions\NestedValidationExceptionInterface  $e) {
			$messages = $e->findMessages(
					[
						'firstName.length' =>'{{name}} invalid character length',
						'name.length' =>'{{name}} invalid character length'
					]
				);
			$this->app->log->error("USER validation FAIL: ".implode(', ',$messages));
		}

		if($this->app->request->isAjax()) {
			$stmt = $this->app->db->prepare("SELECT * FROM notes");
			$stmt->execute();
			$notes = $stmt->fetchAll();

			$this->app->log->info('got AJAX request');
			$a = ['user' => "{$name} {$firstName} created'"];

			$this->app->response->write(json_encode($a));
			$this->app->debugBar->sendDataInHeaders();
		} else {
			$this->app->render('user.tpl', ['messages'=>$messages, 'user' => $user]);
		}
	}

}
