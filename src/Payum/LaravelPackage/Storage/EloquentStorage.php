<?php
namespace Payum\LaravelPackage\Storage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Payum\Core\Model\Identity;
use Payum\Core\Storage\AbstractStorage;

class EloquentStorage extends AbstractStorage
{
    /**
     * {@inheritDoc}
     *
     * @param Model $model
     */
    protected function doUpdateModel($model)
    {
        $model->save();
    }

    /**
     * {@inheritDoc}
     *
     * @param Model $model
     */
    protected function doDeleteModel($model)
    {
        $model->delete();
    }

    /**
     * {@inheritDoc}
     *
     * @param Model $model
     */
    protected function doGetIdentity($model)
    {
        return new Identity($model->{$model->getKeyName()}, $model);
    }

    /**
     * {@inheritDoc}
     *
     * @return Model|null
     */
    protected function doFind($id)
    {
        $modelClass = $this->modelClass;

        return $modelClass::find($id);
    }

    /**
     * {@inheritDoc}
     *
     * @return Model[]
     */
    public function findBy(array $criteria)
    {
        if (empty($criteria)) {
            return [];
        }

        $modelClass = $this->modelClass;

        $query = $modelClass::query();
        foreach ($criteria as $name => $value) {
            $query->where($name, '=', $value);
        }

        return iterator_to_array($query->get());
    }
}