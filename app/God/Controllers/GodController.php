<?php

namespace App\God\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Route, View, DB, Illuminate\Support\Facades\Input;
use Gate;
use App;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;

class GodController extends \App\Http\Controllers\Controller
{
    const VIEW_NAMESPACE = 'GOD';
    const AUTH_CREATE    = 'create';
    const AUTH_RETRIEVE  = 'retrieve';
    const AUTH_UPDATE    = 'update';
    const AUTH_DELETE    = 'delete';
    const AUTH_APPROVE   = 'approve';

    protected $trans = null;

    // The view name of the controller.
    protected $viewTitle = 'Untitled';

    // Items be displayed per index view.
    protected $perView = 5;

    // The database table name;
    protected $table;
    protected $rowStatus;

    // All the fields in the above model.
    protected $fields_all;

    // The fields displayed on index view.
    protected $fields_index;

    // The fields displayed on show view.
    protected $fields_show;

    // The fields displayed on create view.
    protected $fields_create;

    // The fields displayed on edit view.
    protected $fields_edit;

    public function __construct()
    {
        try {
            $route = Route::currentRouteAction();
            $this->controller = '\\'.explode('@', $route)[0];
            View::share('G_controller', $this->controller);
            View::share('G_input', Input::all());
            View::share('G_viewTitle', $this->viewTitle);
            View::share('G_isDingtalkClient', false);//TODO

            // Load view files.
            //view()->addLocation(__DIR__.'/../Views');
            view()->addNamespace(self::VIEW_NAMESPACE, __DIR__.'/../Views');
            View::share('G_viewNS', self::VIEW_NAMESPACE);

            // Load language files.
            $this->trans = function ($id, $parameters = [], $domain = null, $locale = null) {
                static $translator = null;
                if ($translator === null) {
                    $locale = App::getLocale();
                    $langfile = __DIR__.'/../Lang/'.$locale.'.php';
                    if (!file_exists($langfile)) {
                        return $id;
                    }
                    $translator = new Translator($locale);
                    $translator->addLoader('array', new ArrayLoader());
                    $translator->setLocale($locale);
                    $translator->addResource('array', require __DIR__.'/../Lang/'.$locale.'.php', $locale);
                }
                return $translator->trans($id, $parameters, $domain, $locale);
            };
            View::share('G_trans', $this->trans);
        } catch (\Exception $e) {
            dd($e);
        } finally {
            ;
        }
    }

    public function isAllow($action, $id = null)
    {
        if ($action == self::AUTH_APPROVE) {
            return false;
        }
        else {
            return true;//DEBUG
            return Gate::allows($action.'-'.str_singular($this->table));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = response(__FUNCTION__);
        try {
            DB::beginTransaction();

            if (!$this->isAllow(SELF::AUTH_RETRIEVE)) {
                throw new \Exception("No permission!");
            }

            $builder = DB::table($this->table)->select($this->table.'.*');
            foreach (Input::all() as $key => $value) {
                $key   = trim($key);
                $value = trim($value);
                if (empty($value)) {
                    continue;
                }
                if (!array_key_exists($key, $this->fields_all)) {
                    continue;
                }
                if (!array_key_exists('search', $this->fields_all[$key])) {
                    continue;
                }
                $sql = sprintf($this->fields_all[$key]['search'], $value);
                $op  = Input::get('op-'.$key);
                if (array_key_exists('foreign_values', $this->fields_all[$key])) {
                    $builder->whereIn($key, function ($query) use($sql) {
                         $query->selectRaw($sql);
                    }, $op);
                }
                else {
                    $builder->whereRaw($sql, array(), $op);
                }
            }
            $models = $builder->orderBy('id', 'desc')->paginate($this->perView);

            $fields = array();
            foreach ($this->fields_index as $field) {
                $fields[$field] = $this->fields_all[$field];
            }

            $allows = [
                'create'  => $this->isAllow(SELF::AUTH_CREATE),
                'retrieve'=> $this->isAllow(SELF::AUTH_RETRIEVE),
                'update'  => $this->isAllow(SELF::AUTH_UPDATE),
                'delete'  => $this->isAllow(SELF::AUTH_DELETE),
                'approve' => $this->isAllow(SELF::AUTH_APPROVE),
            ];

            $status = $this->rowStatus;

            $viewfile = self::VIEW_NAMESPACE.'::god.'.__FUNCTION__;
            $response = response()->view($viewfile, compact('models', 'fields', 'allows', 'status'));
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
            $response = response($e->getMessage());
        }
        finally {
            ;
        }
        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = response(__FUNCTION__);
        try {
            DB::beginTransaction();

            if (!$this->isAllow(SELF::AUTH_CREATE)) {
                throw new \Exception("No permission!");
            }

            $fields = array();
            foreach ($this->fields_create as $field) {
                $fields[$field] = $this->fields_all[$field];
            }

            $allows = [
                'create'  => $this->isAllow(SELF::AUTH_CREATE),
                'retrieve'=> $this->isAllow(SELF::AUTH_RETRIEVE),
                'update'  => $this->isAllow(SELF::AUTH_UPDATE),
                'delete'  => $this->isAllow(SELF::AUTH_DELETE),
                'approve' => $this->isAllow(SELF::AUTH_APPROVE),
            ];

            $viewfile = self::VIEW_NAMESPACE.'::god.'.__FUNCTION__;
            $response = response()->view($viewfile, compact('fields', 'allows'));
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
            $response = response($e->getMessage());
        }
        finally {
            ;
        }
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = response(__FUNCTION__);
        try {
            DB::beginTransaction();

            if (!$this->isAllow(SELF::AUTH_CREATE)) {
                throw new \Exception("No permission!");
            }

            $values = array();
            foreach ($request->all() as $key => $value) {
                $key   = trim($key);
                $value = trim($value);
                if (array_key_exists($key, $this->fields_all)) {
                    $values[$key] = $value;
                }
            }
            if (array_key_exists('created_at', $this->fields_all)) {
                $values['created_at'] = date('Y-m-d H:i:s');
            }
            if (array_key_exists('updated_at', $this->fields_all)) {
                $values['updated_at'] = date('Y-m-d H:i:s');
            }
            DB::table($this->table)->insert($values);

            $response = redirect()->action($this->controller.'@index');
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
            $response = redirect()->back()
                        ->with('god.log', $e->getMessage())
                        ->withInput();
        }
        finally {
            ;
        }
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = response(__FUNCTION__);
        try {
            DB::beginTransaction();

            if (!$this->isAllow(SELF::AUTH_RETRIEVE, $id)) {
                throw new \Exception("No permission!");
            }

            $model = DB::table($this->table)->where('id', '=', $id)->get()[0];

            $fields = array();
            foreach ($this->fields_show as $field) {
                $fields[$field] = $this->fields_all[$field];
            }

            $allows = [
                'create'  => $this->isAllow(SELF::AUTH_CREATE, $id),
                'retrieve'=> $this->isAllow(SELF::AUTH_RETRIEVE, $id),
                'update'  => $this->isAllow(SELF::AUTH_UPDATE, $id),
                'delete'  => $this->isAllow(SELF::AUTH_DELETE, $id),
                'approve' => $this->isAllow(SELF::AUTH_APPROVE, $id),
            ];

            $viewfile = self::VIEW_NAMESPACE.'::god.'.__FUNCTION__;
            $response = response()->view($viewfile, compact('model', 'fields', 'allows'));
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
            $response = response($e->getMessage());
        }
        finally {
            ;
        }
        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = response(__FUNCTION__);
        try {
            DB::beginTransaction();

            if (!$this->isAllow(SELF::AUTH_UPDATE, $id)) {
                throw new \Exception("No permission!");
            }

            $model = DB::table($this->table)->where('id', '=', $id)->get()[0];

            $fields = array();
            foreach ($this->fields_edit as $field) {
                $fields[$field] = $this->fields_all[$field];
            }

            $allows = [
                'create'  => $this->isAllow(SELF::AUTH_CREATE, $id),
                'retrieve'=> $this->isAllow(SELF::AUTH_RETRIEVE, $id),
                'update'  => $this->isAllow(SELF::AUTH_UPDATE, $id),
                'delete'  => $this->isAllow(SELF::AUTH_DELETE, $id),
                'approve' => $this->isAllow(SELF::AUTH_APPROVE, $id),
            ];

            $viewfile = self::VIEW_NAMESPACE.'::god.'.__FUNCTION__;
            $response = response()->view($viewfile, compact('model', 'fields', 'allows'));
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
            $response = response($e->getMessage());
        }
        finally {
            ;
        }
        return $response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = response(__FUNCTION__);
        try {
            DB::beginTransaction();

            if (!$this->isAllow(SELF::AUTH_UPDATE, $id)) {
                throw new \Exception("No permission!");
            }

            $values = array();
            foreach ($request->all() as $key => $value) {
                $key   = trim($key);
                $value = trim($value);
                if (array_key_exists($key, $this->fields_all)) {
                    $values[$key] = $value;
                }
            }
            if (array_key_exists('updated_at', $this->fields_all)) {
                $values['updated_at'] = date('Y-m-d H:i:s');
            }
            DB::table($this->table)->where('id', '=', $id)->update($values);

            $response = redirect()->action($this->controller.'@index');
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
            $response = redirect()->back()
                        ->with('god.log', $e->getMessage())
                        ->withInput();
        }
        finally {
            ;
        }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = response(__FUNCTION__);
        try {
            DB::beginTransaction();

            if (!$this->isAllow(SELF::AUTH_DELETE, $id)) {
                throw new \Exception("No permission!");
            }

            DB::table($this->table)->where('id', '=', $id)->delete();

            $response = redirect()->action($this->controller.'@index');
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
            $response = redirect()->back()
                        ->with('god.log', $e->getMessage());
        }
        finally {
            ;
        }
        return $response;
    }
}
