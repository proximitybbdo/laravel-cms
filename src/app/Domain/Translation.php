<?php

namespace BBDO\Cms\Domain;


class Translation
{
    /**
     * @return array
     */
    public function getAllTranslations() {

        $lang = $this->getAvailableLang();

        return [];
    }

    protected function getAvailableLang() {

        foreach(glob($this->getLangDirectory()) as $item) {
            var_dump($item);
        }
    }

    public function updateTranslationByKey() {

    }

    protected function getLangDirectory() {
        return resource_path('lang');
    }



}