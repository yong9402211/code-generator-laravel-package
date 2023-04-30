<?php

namespace Yjh94\StandardCodeGenerator\Traits;

use Illuminate\Support\Str;

trait NameTrait
{
    protected $folderName;
    protected $folderNamespace;

    protected function setFolderName($folder)
    {
        if (!is_null($folder) && $folder != '') {
            $folders = explode('/', $folder);
            $folderName = '';
            $folderNamespace = '';
            foreach ($folders as $name) {
                $name = Str::pluralStudly($name);

                $folderName .= $name . '/';
                $folderNamespace .= $name . '\\';
            }

            $this->folderName = '/' . $folderName;
            $this->folderNamespace = '\\' . trim($folderNamespace, '\\');
        } else {
            $this->folderName = '';
            $this->folderNamespace = '';
        }
    }

    protected function getFolderName()
    {
        return $this->folderName;
    }

    protected function getFolderNamespace()
    {
        return $this->folderNamespace;
    }

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
        return 'App\Models' . $this->getFolderNamespace();
    }

    protected function getControllerNamespace()
    {
        return 'App\Http\Controllers' . $this->getFolderNamespace();
    }

    protected function getServiceNamespace()
    {
        return 'App\Services' . $this->getFolderNamespace();
    }

    protected function getStoreRequestNamespace()
    {
        return 'App\Http\Requests' . $this->getFolderNamespace();
    }

    protected function getUpdateRequestNamespace()
    {
        return 'App\Http\Requests' . $this->getFolderNamespace();
    }

    /**
     * ************************************
     * Get full name
     * ************************************
     */

    protected function getModelFullName()
    {
        return $this->getModelNamespace() . $this->getModelName();
    }

    protected function getControllerFullName()
    {
        return $this->getControllerNamespace() . $this->getControllerName();
    }

    protected function getServiceFullName()
    {
        return $this->getServiceNamespace() . $this->getServiceName();
    }

    protected function getStoreRequestFullName()
    {
        return $this->getStoreRequestNamespace() . $this->getStoreRequestName();
    }

    protected function getUpdateRequestFullName()
    {
        return $this->getUpdateRequestNamespace() . $this->getUpdateRequestName();
    }
}
