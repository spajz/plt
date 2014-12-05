<?

use Spajz\Modval\Modval;

class Base extends Modval
{

    protected $statusColumn = 'status';
    protected $modulConfig = null;
    protected $usePreFilters = true;

    public function getProperty($property = 'primaryKey')
    {
        return $this->$property;
    }

    public function toggleStatus($id)
    {
        $this->usePreFilters = false;

        $item = $this->find($id);

        $statusColumn = $this->statusColumn;

        if ($item->$statusColumn == 1)
            $item->$statusColumn = 0;
        else
            $item->$statusColumn = 1;

        $item->save();

        return $item->$statusColumn;
    }

    public function sortModel($sortArray = array())
    {
        if (empty($sortArray)) return;

        foreach ($sortArray as $sort => $id) {
            $model = $this->find($id);
            $model->sort = $sort + 1;
            $model->save();
        }
    }

    public function getModulConfig($modul = null)
    {
        if (is_object($modul)) {
            return $modul->modulConfig;
        }

        if (is_string($modul)) {
            $item = new $modul();
            return $item->getModulConfig();
        }

        return $this->modulConfig;
    }

    public function newQuery($excludeDeleted = true)
    {
        $builder = parent::newQuery($excludeDeleted);

        if ($this->usePreFilters)
            return $this->preFilters($builder);

        return $builder;
    }

    public function preFilters($builder)
    {
        return $builder;
    }

    public function setUsePreFilters($bool)
    {
        $this->usePreFilters = $bool;
    }

    public function getRules()
    {
        return self::$rules;
    }

    public function scopeFilter($query, $value = null)
    {
        return $query;
    }

    public function modelList($column, $key = null)
    {
        if (is_null($key)) {
            $out = $this->lists($column, $this->getProperty());
        } elseif ($key == '') {
            $out = $this->lists($column);
        } else {
            $out = $this->lists($column, $key);
        }
        return $out;
    }

}