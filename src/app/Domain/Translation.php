<?php

namespace BBDO\Cms\Domain;


class Translation
{
    /**
     * @param $lang
     * @return array
     * @throws \Exception
     */
    public function getTranslationsByLang($lang) {
        $translations = $this->fetchTranslations($lang);
        return $translations;
    }

    /**
     * @return array
     */
    public function getAvailableLang() {
        return array_keys($this->getLangDirectory());
    }


    /**
     * @return array
     */
    protected function getLangDirectory() {
        $langDirectory = [];

        foreach(glob(resource_path('lang').'/*') as $item) {
            if(is_dir($item)) {
                $lang = explode('/', $item);
                $langDirectory[end($lang)] = $item;
            }
        }

        return $langDirectory;
    }

    /**
     * @param $lang
     * @return array
     * @throws \Exception
     */
    protected function fetchTranslations($lang) {
        $translations = [];

        if(!isset($this->getLangDirectory()[$lang])) {
            Throw new \Exception('Lang ' . $lang . ' is not in the list. Use one of them : ' . var_export($this->getAvailableLang()));
        }

        $langDirectory = $this->getLangDirectory()[$lang];

        foreach( glob($langDirectory.'/*') as $item) {
            if(is_dir($item)) {
                $translations[$this->cleanName(dirname($item))] = $this->fetchTranslations($item);
            } else {
                $translations[$this->cleanName(basename($item))] = trans($this->cleanName(basename($item)), [], $lang);
            }

        }
        return $translations;
    }

    /**
     * @param $name
     * @return bool|string
     */
    protected function cleanName($name) {
        return substr($name, 0, strpos($name, '.'));
    }

    /**
     * @param $lang
     * @param $file
     * @param $data
     * @throws \Exception
     */
    public function pushTranslation($lang, $file, $data) {
        $x = $this->getPathForFile($lang, $file);

        var_dump($x);
    }

    /**
     * @param $lang
     * @param $file
     * @throws \Exception
     */
    protected function getPathForFile($lang, $file, $subDir = '') {
        if(!isset($this->getLangDirectory()[$lang])) {
            Throw new \Exception('Lang ' . $lang . ' is not in the list. Use one of them : ' . var_export($this->getAvailableLang()));
        }

        foreach(glob($this->getLangDirectory()[$lang].''.$subDir.'/*') as $item) {
            if(is_file($item) && $this->cleanName($item) == $file) {
                return $item;
            } elseif(is_dir($item)) {
                $subDir = dirname($item);
                return $this->getPathForFile($lang, $file, '/'.$subDir);
            }
        }

    }

}