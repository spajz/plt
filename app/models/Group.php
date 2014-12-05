<?php
use Cartalyst\Sentry\Groups\Eloquent\Group as SentryGroupModel;

class Group extends SentryGroupModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sentry_groups';
}
 
 