<?php

namespace services;

use services\EmailService;

class UsersManagerService
{

  /**
   *  Array with users.
   *
   * @param array $users
   */

    public function __construct(array $users)
    {
        $this->users = $users;
    }

  /**
   * Update users data.
   *
   * @param array $users
   */
    public function updateUsers(array $users)
    {
        foreach ($users as $user) {
            try {
                if ($this->checkLength()) {
                    $this->updateUser($user);
                }
            } catch (Exception $e) {
                return Redirect::back()->withErrors([
                'error',
                'We couldn\'t update user: ' . $e->getMessage(),
                ]);
            }
        }
        return Redirect::back()->with(['success', 'All users updated.']);
    }

  /**
   * Store users data.
   *
   * @param $users
   *
   * @return mixed
   */
    public function storeUsers($users)
    {
        foreach ($users as $user) {
            try {
                if ($this->checkLength()) {
                    $this->insertUser($user);
                }
            } catch (Exception $e) {
                return Redirect::back()->withErrors([
                'error',
                ['We couldn\'t store user: ' . $e->getMessage()],
                ]);
            }
        }
        $sendEmail = new EmailService();
        $sendEmail->sendEmail($users);

        return Redirect::back()->with(['success', 'All users created.']);
    }

  /**
   * Check user's length.
   *
   * @param $user
   *
   * @return boolean
   *
   */
    private function checkLength($user)
    {
        foreach ($user as $length) {
            if (strlen($length) >= 10) {
                continue;
            } else {
                return false;
            }
            return true;
        }
    }

  /**
   * Update single user data.
   *
   * @param $user
   *
   * @return void
   */
    private function updateUser($user)
    {
        DB::table('users')->where('id', $user['id'])->update([
        'name' => $user['name'],
        'login' => $user['login'],
        'email' => $user['email'],
        'password' => md5($user['password']),
        ]);
    }

  /**
   * Insert single user data.
   *
   * @param $user
   *
   * @return void
   */
    private function insertUser($user)
    {
        DB::table('users')->insert([
        'name' => $user['name'],
        'login' => $user['login'],
        'email' => $user['email'],
        'password' => md5($user['password']),
        ]);
    }
}
