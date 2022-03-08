<?php

namespace application\controllers;

use application\core\Controller,
    application\models\Tasks;

class MainController extends Controller {

    protected $title = 'Список задач';
    private $page_delta = 2;
    private $rows_per_page = 5;
    private $current_page = 1;

    public function indexAction()
    {
        $this->title = 'Список задач';

        if(isset($this->config['paging']['page_delta'])) {
            $this->page_delta = intval($this->config['paging']['page_delta']);
        }
        if(isset($this->config['paging']['rows_per_page'])) {
            $this->rows_per_page = intval($this->config['paging']['rows_per_page']);
        }

        $task = new Tasks();

        $order_change = [];
        $order_arrows = [];
        $task_orders = array_merge(get_object_vars($task), ['id' => null]);
        foreach($task_orders as $k => $v) {
            if(isset($_GET[$k])) {
                $task_orders[$k] = $_GET[$k];
                switch($_GET[$k]) {
                    case 'asc':
                        $order_change[$k]  = 'desc';
                        $order_arrows[$k] = '&nbsp;&#8595;';
                        break;
                    case 'desc':
                        $order_change[$k]  = '';
                        $order_arrows[$k] = '&nbsp;&#8593;';
                        break;
                    default:
                        $order_change[$k]  = 'asc';
                        $order_arrows[$k] = '';
                        break;
                }
            } else {
                $order_change[$k]  = 'asc';
                $order_arrows[$k] = '';
            }
        }

        // т.к. возможна сортировка по нескольким столбцам, то будем соблюдать очередность сортировки
        $_task_orders = $task_orders;
        $task_orders = [];
        foreach(array_keys($_GET) as $key) {
            if(isset($_task_orders[$key])) {
                $task_orders[$key] = $_task_orders[$key];
                unset($_task_orders[$key]);
            }
        }
        $task_orders = array_merge($task_orders, $_task_orders);

        if(isset($_GET['page'])) {
            $this->current_page = intval($_GET['page']);
        }
        $total_count_tasks = $task->getCountTasks();
        if($this->current_page < 1 || $total_count_tasks < $this->current_page) {
            $this->current_page = 1;
        }

        $params = [
            'limit' => $this->rows_per_page,
            'offset' => ($this->current_page - 1) * $this->rows_per_page,
            'orders' => $task_orders,
        ];
        $tasks_list = $task->getTasksList($params);
        $status_list = Tasks::getStatusList();

        $flash_created = isset($_GET['created'])?$_GET['created']:'';

        $view_vars = [
            'paginator' => $this->getPaginator($total_count_tasks),
            'tasks_list' => $tasks_list,
            'total_count_tasks' => $total_count_tasks,
            'status_list' => $status_list,
            'page_current' => $this->current_page,
            'rows_per_page' => $this->rows_per_page,
            'order_change' => $order_change,
            'order_arrows' => $order_arrows,
            'flash_created' => $flash_created,
        ];
        $this->view->render($this->title, $view_vars);
    }

    public function createAction()
    {
        $errors = $params = [];
        if($_POST) {
            $params = $_POST;
            $task = new Tasks();
            foreach(array_keys(get_object_vars($task)) as $prop){
                if(isset($_POST[$prop]))
                    $task->$prop = $_POST[$prop];
            }
            if(!$task->save()) {
                $errors = $task->getErrors();
            } else {
                if(isset($this->config['paging']['rows_per_page'])) {
                    $this->rows_per_page = intval($this->config['paging']['rows_per_page']);
                }
                $total_count_tasks = $task->getCountTasks();
                $page = ceil($total_count_tasks / $this->rows_per_page);
                header("Location: /?page={$page}&created=$task->id");
            }
        }

        $this->title = 'Создать задачу';
        $status_list = Tasks::getStatusList();
        $view_vars = [
            'params' => $params,
            'errors' => $errors,
            'status_list' => $status_list,
            'submit_title' => 'Создать',
        ];
        $this->view->render($this->title, $view_vars);
    }

    public function editAction()
    {
        $errors = $params = [];
        $task = new Tasks($_GET['id']);
        if($_POST) {
            foreach(array_keys(get_object_vars($task)) as $prop){
                if(isset($_POST[$prop])) {
                    if($prop == 'task_text' && $task->$prop != $_POST[$prop])
                    {
                        $task->changed_by_user = $this->user->name;
                    }
                    $task->$prop = $_POST[$prop];
                }
            }
            if(!$task->save()) {
                $errors = $task->getErrors();
            } else {
                if(isset($this->config['paging']['rows_per_page'])) {
                    $this->rows_per_page = intval($this->config['paging']['rows_per_page']);
                }
                $total_count_tasks = $task->getCountTasks();
                $page = ceil($task->id / $this->rows_per_page);
                header("Location: /?page={$page}&created=$task->id");
            }
        }

        $this->view->setView('main/create');
        $task_data = get_object_vars($task);
        $task_data['id'] = $_GET['id'];
        $this->title = 'Редактировать задачу';
        $status_list = Tasks::getStatusList();
        $view_vars = [
            'params' => $task_data,
            'errors' => $errors,
            'status_list' => $status_list,
            'submit_title' => 'Сохранить',
        ];
        $this->view->render($this->title, $view_vars);
    }

    private function getPaginator($cnt_rows)
    {
        $pages_cnt = ceil($cnt_rows / $this->rows_per_page);

        $view = new \application\core\View(['action' => "pagination", 'controller' => 'main']);
        $view_vars = [
            'page_current' => $this->current_page,
            'page_delta' => $this->page_delta,
            'pages_cnt' => $pages_cnt,
            'get_params' => $_GET,
        ];
        return $view->render_part($view_vars);
    }
}
