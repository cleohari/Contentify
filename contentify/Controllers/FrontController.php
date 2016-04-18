<?php namespace Contentify\Controllers;

use URL, Input, View, Redirect, Config;

abstract class FrontController extends BaseController {

    /**
     * The layout that should be used for responses.
     * If null the layout of the chosen theme will be loaded.
     * @var string
     */
    protected $layout = null;

    /**
     * Setup the layout used by the controller.
     * 
     * param string $layoutName The name of the layout template file
     * @return void
     */
    protected function setupLayout($layoutName = null)
    {
        if (! $layoutName) {
            $layoutName = $this->layout? $this->layout : lcfirst(Config::get('app.theme')).'::layout';
        }

        parent::setupLayout($layoutName);

        View::composer($layoutName, function($view)
        { 
            $view->with('moduleName',       $this->moduleName);
            $view->with('controllerName',   $this->controllerName);
        });
    }

    /**
     * Helper action method for searching. All we do here is to redirect with the input.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search()
    {
        $url = URL::previous();

        // Alternative:
        //$class = static::class;
        //$url = URL::action($class.'@index');

        return Redirect::to($url)->withInput(Input::only('search'));
    }
    
}