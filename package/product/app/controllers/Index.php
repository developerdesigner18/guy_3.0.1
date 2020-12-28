<?php

namespace Altum\Controllers;


class Index extends Controller {

    public function index() {

        /* Custom index redirect if set */
        if(!empty($this->settings->index_url)) {
            header('Location: ' . $this->settings->index_url);
            die();
        }

        /* Plans View */
        $data = [];

        $view = new \Altum\Views\View('partials/plans', (array) $this);

        $this->add_view_content('plans', $view->run($data));


        /* Main View */
        $data = [];

        $view = new \Altum\Views\View('index/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
