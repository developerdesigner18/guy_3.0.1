<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;
use Altum\Models\Plan;
use Altum\Routing\Router;

class Realtime extends Controller {

    public function index() {

        Authentication::guard();

        if(!$this->website) {
            redirect('websites');
        }

        /* Prepare the View */
        $data = [];

        $view = new \Altum\Views\View('realtime/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
