<?php

namespace Altum\Routing;

use Altum\Language;

class Router {
    public static $params = [];
    public static $original_request = '';
    public static $language_code = '';
    public static $path = '';
    public static $controller_key = 'index';
    public static $controller = 'Index';
    public static $controller_settings = [
        'menu_no_margin'        => false,
        'wrapper'               => 'wrapper',
        'no_authentication_check' => false,

        /* Should we see a view for the controller? */
        'has_view'              => true,

        /* If set on yes, ads wont show on these pages at all */
        'no_ads'                => false
    ];
    public static $method = 'index';

    public static $routes = [
        '' => [
            'index' => [
                'controller' => 'Index'
            ],

            'login' => [
                'controller' => 'Login',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_ads' => true
                ]
            ],

            'register' => [
                'controller' => 'Register',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_ads' => true
                ]
            ],


            'pages' => [
                'controller' => 'Pages'
            ],

            'page' => [
                'controller' => 'Page'
            ],

            'help' => [
                'controller' => 'Help'
            ],

            'activate-user' => [
                'controller' => 'ActivateUser'
            ],

            'lost-password' => [
                'controller' => 'LostPassword',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_ads' => true
                ]
            ],

            'reset-password' => [
                'controller' => 'ResetPassword',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_ads' => true
                ]
            ],

            'resend-activation' => [
                'controller' => 'ResendActivation',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_ads' => true
                ]
            ],

            'logout' => [
                'controller' => 'Logout'
            ],

            'notfound' => [
                'controller' => 'NotFound'
            ],

            /* Logged in */
            'dashboard' => [
                'controller' => 'Dashboard',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'dashboard-ajax-normal' => [
                'controller' => 'DashboardAjaxNormal',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'dashboard-ajax-lightweight' => [
                'controller' => 'DashboardAjaxLightweight',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'goals-ajax' => [
                'controller' => 'GoalsAjax',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'realtime' => [
                'controller' => 'Realtime',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'visitors' => [
                'controller' => 'Visitors',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'visitors-ajax' => [
                'controller' => 'VisitorsAjax',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'visitor' => [
                'controller' => 'Visitor',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'session-ajax' => [
                'controller' => 'SessionAjax',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'heatmaps' => [
                'controller' => 'Heatmaps',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'heatmaps-ajax' => [
                'controller' => 'HeatmapsAjax',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'heatmap' => [
                'controller' => 'Heatmap',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'replays' => [
                'controller' => 'Replays',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'replays-ajax' => [
                'controller' => 'ReplaysAjax',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'replay' => [
                'controller' => 'Replay',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'websites' => [
                'controller' => 'Websites',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'websites-ajax' => [
                'controller' => 'WebsitesAjax',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'teams' => [
                'controller' => 'Teams',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'teams-ajax' => [
                'controller' => 'TeamsAjax',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'team' => [
                'controller' => 'Team',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'teams-associations-ajax' => [
                'controller' => 'TeamsAssociationsAjax',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'account' => [
                'controller' => 'Account',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'account-plan' => [
                'controller' => 'AccountPlan',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'account-payments' => [
                'controller' => 'AccountPayments',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'account-logs' => [
                'controller' => 'AccountLogs',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'account-delete' => [
                'controller' => 'AccountDelete',
                'settings' => [
                    'wrapper'   => 'app_wrapper',
                    'no_ads'    => true
                ]
            ],

            'invoice' => [
                'controller' => 'Invoice',
                'settings' => [
                    'wrapper' => 'invoice/invoice_wrapper',
                    'no_ads' => true
                ]
            ],

            'plan' => [
                'controller' => 'Plan',
                'settings' => [
                    'no_ads' => true
                ]
            ],

            'pay' => [
                'controller' => 'Pay',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'no_ads' => true
                ]
            ],

            'pay-thank-you' => [
                'controller' => 'PayThankYou',
                'settings' => [
                    'wrapper' => 'app_wrapper',
                    'no_ads' => true
                ]
            ],

            'pixel' => [
                'controller' => 'Pixel',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],

            'pixel-track' => [
                'controller' => 'PixelTrack',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],


            /* Webhooks */
            'webhook-paypal' => [
                'controller' => 'WebhookPaypal',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],

            'webhook-stripe' => [
                'controller' => 'WebhookStripe',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],

            /* Others */
            'get-captcha' => [
                'controller' => 'GetCaptcha',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],

            'sitemap' => [
                'controller' => 'Sitemap',
                'settings' => [
                    'no_authentication_check' => true
                ]
            ],

            'cron' => [
                'controller' => 'Cron',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],
        ],

        /* Admin Panel */
        'admin' => [
            'index' => [
                'controller' => 'AdminIndex'
            ],

            'users' => [
                'controller' => 'AdminUsers'
            ],

            'user-create' => [
                'controller' => 'AdminUserCreate'
            ],

            'user-view' => [
                'controller' => 'AdminUserView'
            ],

            'user-update' => [
                'controller' => 'AdminUserUpdate'
            ],

            'websites' => [
                'controller' => 'AdminWebsites'
            ],

            'pages-categories' => [
                'controller' => 'AdminPagesCategories'
            ],

            'pages-category-create' => [
                'controller' => 'AdminPagesCategoryCreate'
            ],

            'pages-category-update' => [
                'controller' => 'AdminPagesCategoryUpdate'
            ],

            'pages' => [
                'controller' => 'AdminPages'
            ],

            'page-create' => [
                'controller' => 'AdminPageCreate'
            ],

            'page-update' => [
                'controller' => 'AdminPageUpdate'
            ],


            'plans' => [
                'controller' => 'AdminPlans'
            ],

            'plan-create' => [
                'controller' => 'AdminPlanCreate'
            ],

            'plan-update' => [
                'controller' => 'AdminPlanUpdate'
            ],


            'codes' => [
                'controller' => 'AdminCodes'
            ],

            'code-create' => [
                'controller' => 'AdminCodeCreate'
            ],

            'code-update' => [
                'controller' => 'AdminCodeUpdate'
            ],


            'taxes' => [
                'controller' => 'AdminTaxes'
            ],

            'tax-create' => [
                'controller' => 'AdminTaxCreate'
            ],

            'tax-update' => [
                'controller' => 'AdminTaxUpdate'
            ],


            'payments' => [
                'controller' => 'AdminPayments'
            ],


            'statistics' => [
                'controller' => 'AdminStatistics'
            ],


            'settings' => [
                'controller' => 'AdminSettings'
            ],
        ]
    ];



    public static function parse_url() {

        $params = self::$params;

        if(isset($_GET['altum'])) {
            $params = explode('/', filter_var(rtrim($_GET['altum'], '/'), FILTER_SANITIZE_URL));
        }

        self::$params = $params;

        return $params;

    }

    public static function get_params() {

        return self::$params = array_values(self::$params);
    }

    public static function parse_language() {

        /* Check for potential language set in the first parameter */
        if(!empty(self::$params[0]) && array_key_exists(self::$params[0], Language::$languages)) {

            /* Set the language */
            Language::set_by_code(self::$params[0]);
            self::$language_code = self::$params[0];

            /* Unset the parameter so that it wont be used further */
            unset(self::$params[0]);
            self::$params = array_values(self::$params);

        }

    }

    public static function parse_controller() {

        self::$original_request = implode('/', self::$params);

        /* Check for potential other paths than the default one (admin panel) */
        if(!empty(self::$params[0])) {

            if(in_array(self::$params[0], ['admin'])) {
                self::$path = self::$params[0];

                unset(self::$params[0]);

                self::$params = array_values(self::$params);
            }

        }

        if(!empty(self::$params[0])) {

            if(array_key_exists(self::$params[0], self::$routes[self::$path]) && file_exists(APP_PATH . 'controllers/' . (self::$path != '' ? self::$path . '/' : null) . self::$routes[self::$path][self::$params[0]]['controller'] . '.php')) {

                self::$controller_key = self::$params[0];

                unset(self::$params[0]);

            } else {

                /* Not found controller */
                self::$path = '';
                self::$controller_key = 'notfound';

            }

        }

        /* Save the current controller */
        self::$controller = self::$routes[self::$path][self::$controller_key]['controller'];

        /* Make sure we also save the controller specific settings */
        if(isset(self::$routes[self::$path][self::$controller_key]['settings'])) {
            self::$controller_settings = array_merge(self::$controller_settings, self::$routes[self::$path][self::$controller_key]['settings']);
        }

        return self::$controller;

    }

    public static function get_controller($controller_ame, $path = '') {

        require_once APP_PATH . 'controllers/' . ($path != '' ? $path . '/' : null) . $controller_ame . '.php';

        /* Create a new instance of the controller */
        $class = 'Altum\\Controllers\\' . $controller_ame;

        /* Instantiate the controller class */
        $controller = new $class;

        return $controller;
    }

    public static function parse_method($controller) {

        $method = self::$method;

        /* Make sure to check the class method if set in the url */
        if(isset(self::get_params()[0]) && method_exists($controller, self::get_params()[0])) {

            /* Make sure the method is not private */
            $reflection = new \ReflectionMethod($controller, self::get_params()[0]);
            if($reflection->isPublic()) {
                $method = self::get_params()[0];

                unset(self::$params[0]);
            }

        }

        return $method;

    }

}
