<?php
use Cartalyst\Sentry\Users\Eloquent\User as SentryUserModel;

class User extends SentryUserModel
{
    public function data()
    {
        return $this->hasOne('UserData');
    }

    public function getFullName()
    {
        $out = $this->first_name;
        if ($this->last_name != '') $out .= ' ' . $this->last_name;
        if (!$out != '') $out = $this->email;
        return $out;
    }
}