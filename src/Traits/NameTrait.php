<?php

namespace Yjh94\StandardCodeGenerator\Traits;

use Illuminate\Support\Str;

trait NameTrait
{
    protected $folderName;
    protected $folderNamespace;

    /**
     * ************************************
     * Get folder name
     * ************************************
     */

    protected function setFolderName($folder)
    {
        if (!is_null($folder) && $folder != '') {
            $names = $this->convertFolderName($folder);

            $this->folderName = '/' . $names[0];
            $this->folderNamespace = '\\' . trim($names[1], '\\');
        } else {
            $this->folderName = '';
            $this->folderNamespace = '';
        }
    }

    protected function convertFolderName($folder)
    {
        $folders = explode('/', $folder);
        $folderName = '';
        $folderNamespace = '';

        foreach ($folders as $name) {
            $name = Str::pluralStudly($name);

            $folderName .= $name . '/';
            $folderNamespace .= $name . '\\';
        }

        return [$folderName, $folderNamespace];
    }

    protected function getFolderName()
    {
        return $this->folderName;
    }

    protected function getFolderNamespace()
    {
        return $this->folderNamespace;
    }

    protected function getMigrationFolderName()
    {
        return '';
    }

    protected function getRouteFolderName()
    {
        return Str::lower($this->getFolderName());
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
        return 'App\Http\Services' . $this->getFolderNamespace();
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
        return $this->getModelNamespace() . '\\' . $this->getModelName();
    }

    protected function getControllerFullName()
    {
        return $this->getControllerNamespace() . '\\' .  $this->getControllerName();
    }

    protected function getServiceFullName()
    {
        return $this->getServiceNamespace() . '\\' . $this->getServiceName();
    }

    protected function getStoreRequestFullName()
    {
        return $this->getStoreRequestNamespace() . '\\' . $this->getStoreRequestName();
    }

    protected function getUpdateRequestFullName()
    {
        return $this->getUpdateRequestNamespace() . '\\' . $this->getUpdateRequestName();
    }

    /**
     * ************************************
     * Get dir name
     * ************************************
     */

    protected function getModelDir()
    {
        return 'app/Models';
    }

    protected function getControllerDir()
    {
        return 'app/Http/Controllers';
    }

    protected function getServiceDir()
    {
        return 'app/Http/Services';
    }

    protected function getRequestDir()
    {
        return 'app/Http/Requests';
    }

    protected function getStoreRequestDir()
    {
        return 'app/Http/Requests';
    }

    protected function getUpdateRequestDir()
    {
        return 'app/Http/Requests';
    }

    protected function getMigrationDir()
    {
        return 'database/migrations';
    }

    protected function getRouteDir()
    {
        return 'routes/api';
    }



    /**
     * ************************************
     * Get file name
     * ************************************
     */

    protected function getModelFileName()
    {
        return $this->getModelName();
    }

    protected function getControllerFileName()
    {
        return $this->getControllerName();
    }

    protected function getServiceFileName()
    {
        return $this->getServiceName();
    }

    protected function getStoreRequestFileName()
    {
        return $this->getStoreRequestName();
    }

    protected function getUpdateRequestFileName()
    {
        return $this->getUpdateRequestName();
    }

    protected function getMigrationFileName()
    {
        return date('Y_m_d_his') . '_create_' . $this->getPluralSnakeName() . '_table';
    }

    protected function getRouteFileName()
    {
        return $this->getSingularKebabName();
    }
}
