<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Models\Plan;
use Altum\Routing\Router;

class Websites extends Controller {

    public function index() {

        Authentication::guard();

        /* Create Modal */
        $view = new \Altum\Views\View('websites/website_create_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Update Modal */
        $view = new \Altum\Views\View('websites/website_update_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Pixel Modal */
        $view = new \Altum\Views\View('websites/website_pixel_key_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\Views\View('websites/website_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'tracking_type'], ['name', 'host'], ['date', 'name']));

        /* Prepare the paginator */
        $total_rows = Database::$database->query("SELECT COUNT(*) AS `total` FROM `websites` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, 25, $_GET['page'] ?? 1, url('websites?' . $filters->get_get() . '&page=%d')));

        /* Get the websites list for the user */
        $websites = [];
        $websites_result = Database::$database->query("
            SELECT 
                `websites`.*, 
                COUNT(DISTINCT `websites_heatmaps`.`heatmap_id`) AS `heatmaps`, 
                COUNT(DISTINCT `websites_goals`.`goal_id`) AS `goals`
            FROM 
                 `websites`
            LEFT JOIN 
                `websites_heatmaps` ON `websites_heatmaps`.`website_id` = `websites`.`website_id` 
            LEFT JOIN 
                `websites_goals` ON `websites_goals`.`website_id` = `websites`.`website_id`
            WHERE 
                  `websites`.`user_id` = {$this->user->user_id}
                  {$filters->get_sql_where('websites')}
            GROUP BY 
                `websites`.`website_id`
            {$filters->get_sql_order_by('websites')}
            LIMIT 
                {$paginator->getSqlOffset()}, {$paginator->getItemsPerPage()}
        ");
        while($row = $websites_result->fetch_object()) $websites[] = $row;

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the View */
        $data = [
            'websites' => $websites,
            'pagination' => $pagination,
            'filters'   => $filters
        ];

        $view = new \Altum\Views\View('websites/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
