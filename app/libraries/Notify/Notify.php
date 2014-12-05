<?php namespace Notify;

use Session;
use View;

class Notify extends \Illuminate\Support\MessageBag
{
    public $view = 'notify.container';

    public function show()
    {
        return $this->render('flash');
    }

    public function showInstant()
    {
        return $this->render('instant');
    }

    public function success($message)
    {
        $this->flash('success', $message);
    }

    public function info($message)
    {
        $this->flash('info', $message);
    }

    public function warning($message)
    {
        $this->flash('warning', $message);
    }

    public function danger($message)
    {
        $this->flash('danger', $message);
    }

    protected function flash($key, $message)
    {
        $this->add($key, $message);
        Session::flash('notify', $this->messages);
    }

    protected function render($type)
    {
        $out = '';

        if($type == 'flash')
            $messages = Session::get('notify');
        else
            $messages = $this->messages;

        if (empty($messages)) return;

        foreach ($messages as $key => $group) {
            $out .= View::make($this->view, $vars = array('key' => $key, 'group' => $group));
        }

        return $out;
    }
}