<?php

namespace Altum;

use Altum\Database\Database;

class Filters {

    public $allowed_filters = [];
    public $allowed_order_by = [];
    public $allowed_search_by = [];

    public $filters = [];
    public $search = '';
    public $search_by = '';
    public $order_by = '';
    public $order_type = '';

    public $get = [];

    public function __construct($allowed_filters = [], $allowed_search_by = [], $allowed_order_by = []) {

        $this->allowed_filters = $allowed_filters;
        $this->allowed_order_by = $allowed_order_by;
        $this->allowed_search_by = $allowed_search_by;

        $this->process();
    }

    public function process() {

        /* Filters */
        foreach($this->allowed_filters as $filter) {

            if(isset($_GET[$filter]) && $_GET[$filter] != '') {
                $this->filters[$filter] = Database::clean_string($_GET[$filter]);
                $this->get[$filter] = $_GET[$filter];
            }

        }

        /* Search */
        if(count($this->allowed_search_by) && isset($_GET['search']) && isset($_GET['search_by']) && in_array($_GET['search_by'], $this->allowed_search_by)) {

            $_GET['search'] = Database::clean_string($_GET['search']);
            $_GET['search_by'] = Database::clean_string($_GET['search_by']);

            $this->search = $_GET['search'];
            $this->search_by = $_GET['search_by'];

            $this->get['search'] = $_GET['search'];
            $this->get['search_by'] = $_GET['search_by'];
        }

        /* Order by */
        if(count($this->allowed_order_by) && isset($_GET['order_by']) && in_array($_GET['order_by'], $this->allowed_order_by)) {

            $_GET['order_by'] = Database::clean_string($_GET['order_by']);
            $order_type = isset($_GET['order_type']) && in_array($_GET['order_type'], ['ASC', 'DESC']) ? Database::clean_string($_GET['order_type']) : 'ASC';

            $this->order_by = $_GET['order_by'];
            $this->order_type = $order_type;

            $this->get['order_by'] = $_GET['order_by'];
            $this->get['order_type'] = $_GET['order_type'];
        }

    }

    public function get_sql_where($table_prefix = null) {
        $where = '';

        $table_prefix = $table_prefix ? "`{$table_prefix}`." : null;

        /* Filters */
        foreach($this->filters as $key => $value) {
            $where .= " AND {$table_prefix}`{$key}` = '{$value}'";
        }

        /* Search */
        if($this->search && $this->search_by) {
            $where .= " AND {$table_prefix}`{$this->search_by}` LIKE '%{$this->search}%'";
        }

        return $where;
    }

    public function get_sql_order_by($table_prefix = null) {
        $order_by = '';

        $table_prefix = $table_prefix ? "`{$table_prefix}`." : null;

        /* Order By */
        if($this->order_by && $this->order_type) {
            $order_by .= " ORDER BY {$table_prefix}`{$this->order_by}` {$this->order_type}";
        }

        return $order_by;
    }

    public function get_get() {
        $get = [];

        foreach($this->get as $key => $value) {
            $get[] = $key . '=' . $value;
        }

        return implode('&', $get);
    }
}
