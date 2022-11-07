<?php

namespace services;

class EmailService {

  /**
   * Send emails to users.
   */
  public function sendEmail($users) {
    foreach ($users as $user) {
      $message = 'Account has beed created. You can log in as <b>' . $user['login'] . '</b>';
      if ($user['email']) {
        Mail::to($user['email'])
          ->cc('support@company.com')
          ->subject('New account created')
          ->queue($message);
      }
    }
    return TRUE;
  }

}
