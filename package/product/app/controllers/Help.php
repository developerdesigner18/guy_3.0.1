<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Title;

class Help extends Controller {

    public function index() {

        $page = isset($this->params[0]) ? $this->params[0] : 'introduction';

        /* Check if page exists */
        if(file_exists(THEME_PATH . 'views/help/' . $page . '.php')) {
            $view = new \Altum\Views\View('help/' . $page, (array) $this);

            $this->add_view_content('page', $view->run());
        }

        /* Prepare the View */
        $data = [
            'page' => $page
        ];

        $view = new \Altum\Views\View('help/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
