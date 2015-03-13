<?php

namespace IntraworQ\Controllers;

class notesController extends BaseController {

	public function noteslist() {
		/** sample mesages to debugbar log4pp tab * */
		$this->app->log->debug("/notes route debug");
		$this->app->log->error("error");
		$this->app->log->fatal("fatal error ");
		$this->app->log->warn("warning");
		$this->app->log->info("info");

		//database log query example
		$stmt = $this->app->db->prepare("SELECT * FROM notes");
		$stmt->execute();
		$notes = $stmt->fetchAll();
		$this->app->log->debug(json_encode($notes));
		$this->app->render('notes.tpl', ['notes' => $notes]);
	}

}
