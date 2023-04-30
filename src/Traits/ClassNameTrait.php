<?php

namespace Yjh94\StandardCodeGenerator\Traits;

trait ClassNameTrait
{
    /**
     * ************************************
     * Get class name
     * ************************************
     */

    protected function getModelName()
    {
        return $this->getSingularStudyName();
    }

    protected function getControllerName()
    {
        return $this->getSingularStudyName() . 'Controller';
    }

    protected function getServiceName()
    {
        return $this->getSingularStudyName() . 'Service';
    }

    protected function getStoreRequestName()
    {
        return 'Store' . $this->getSingularStudyName() . 'Request';
    }

    protected function getUpdateRequestName()
    {
        return 'Update' . $this->getSingularStudyName() . 'Request';
    }

    /**
     * ************************************
     * Get namespace
     * ************************************
     */

    protected function getModelNamespace()
    {
        return 'App\Models';
    }

    protected function getControllerNamespace()
    {
        return 'App\Http\Controllers';
    }

    protected function getServiceNamespace()
    {
        return 'App\Services';
    }

    protected function getStoreRequestNamespace()
    {
        return 'App\Http\Requests';
    }

    protected function getUpdateRequestNamespace()
    {
        return 'App\Http\Requests';
    }
}
