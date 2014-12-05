<?php namespace App\Modules\Admin\Controllers;

use BaseController;
use Input;
use Redirect;
use Sentry;
use View;
use Notification;

class AuthController extends BaseController
{

    public function getLogin()
    {
        return View::make('admin::auth.login');
    }

    public function postLogin()
    {
        $credentials = array(
            'email' => Input::get('email'),
            'password' => Input::get('password')
        );

        try {
            $user = Sentry::authenticate($credentials, false);

            if ($user) {
                Notification::success('You have been successfully logged in.');
                return Redirect::route('admin.dashboard');
            }
        } catch (\Exception $e) {
            Notification::error('There was an error.');
            return Redirect::route('admin.login')->withErrors(array('login' => $e->getMessage()));
        }
    }

    public function getLogout()
    {
        Sentry::logout();
        Notification::success('You have been successfully logged out.');
        return Redirect::route('admin.login');
    }

    public function sentryAction($action = false)
    {

        if ($action == 'creategroup') {
            try {
                // Create the group
                $group = Sentry::getGroupProvider()->create(array(
                    'name' => 'Administrators',
                    'permissions' => array(
                        'admin' => 1,
                        'users' => 1,
                    ),
                ));
            } catch (Cartalyst\Sentry\Groups\NameRequiredException $e) {
                echo 'Name field is required';
            }
            catch (Cartalyst\Sentry\Groups\GroupExistsException $e) {
                echo 'Group already exists';
            }
        }

        if ($action == 'createuser') {
            try {
                // Create the user
                $user = Sentry::getUserProvider()->create(array(
                    'email' => 'admin@admin.com',
                    'password' => 'admin123',
                ));

                // Find the group using the group id
                $adminGroup = Sentry::getGroupProvider()->findById(1);

                // Assign the group to the user
                $user->addGroup($adminGroup);
            } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
                echo 'Login field is required.';
            }
            catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
                echo 'Password field is required.';
            }
            catch (Cartalyst\Sentry\Users\UserExistsException $e) {
                echo 'User with this login already exists.';
            }
            catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
                echo 'Group was not found.';
            }
        }
    }

}