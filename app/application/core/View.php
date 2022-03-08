<?php

namespace application\core;

class View {
	public $path; // путь к вьюхе
	public $route;
	public $user;
	public $layout = 'default';
    public $view_path;
    public $app_root_path = '/'; // рутовый путь к сайту

	public function __construct($route) {

        $config = require __DIR__ . '/../config/config.php';
        if(
            isset($config['site_root_path'])
            && $config['site_root_path'] != ''
        ) {
            $this->app_root_path = $config['site_root_path'];
        }

		$this->route = $route;
		$this->user = new User(); // данные по пользователю во вьюхе (для блока header)
        // определяем вьюху
		$this->path = $route['controller'].'/'.$route['action'];
        $this->setView($this->path);
	}

    /**
     * Отрисовка HTML вьюхи
     *
     */
	public function render($title, $vars = []) {
		extract($vars); // делаем переданные переменные доступными во вьюхе
		if (file_exists($this->view_path)) {
			ob_start();
			require $this->view_path;
			$content = ob_get_clean();
            require __DIR__ . '/../views/layouts/'.$this->layout.'.php';
		}
	}

    /**
     * Выставляем определенную вьюху, если нужна отличная от дефолтной
     * params string $view_path - путь к вьюхе
     *
     */
    public function setView($view_path)
    {
        $path = __DIR__ . '/../views/' . $view_path . '.php';
        $this->view_path = $path;
    }

    /**
     * Частичный рендер
     *
     */
    public function render_part($vars = []) {
		extract($vars);
		$path = __DIR__ . '/../views/'.$this->path.'.php';
		if (file_exists($path)) {
			ob_start();
			require $path;
			$content = ob_get_clean();
			return $content;
		}
        return;
	}

    /**
     * Редирект на себя же или по указанному пути
     *
     */
	public function redirect($url=null) {
	    if(is_null($url)) {
	        $url = $this->getRedirectUrl();
	    }
		header('location: ' . $url);
		exit;
	}

    /**
     * Возвращает путь текущей страницы
     *
     */
	public function getRedirectUrl() {
	    return $this->app_root_path . $this->route['controller'].'/'.$this->route['action'];
	}

    /**
     * Рендер страницы с HTTP ошибками
     *
     */
	public static function errorCode($code) {
		http_response_code($code);
		$path = __DIR__ . '/../views/errors/'.$code.'.php';
		if (file_exists($path)) {
			require $path;
		}
		exit;
    }
}