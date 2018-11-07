<?php

namespace BBDO\Cms\Domain;


use Symfony\Component\Yaml\Yaml;

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
    protected function fetchTranslations($lang, $subDir = '') {
        $translations = [];

        if(!isset($this->getLangDirectory()[$lang])) {
            Throw new \Exception('Lang ' . $lang . ' is not in the list. Use one of them : ' . var_export($this->getAvailableLang()));
        }

        $langDirectory = $this->getLangDirectory()[$lang] . $subDir;

        foreach( scandir($langDirectory) as $item) {
            if($item == '.' || $item == '..')
                continue;

            $pathItem = $langDirectory.'/'.$item;
            if(is_dir($pathItem)) {
                $translations[$item] = array_dot($this->fetchTranslations($lang, '/'.$item));
            } else {
                $translations[$this->cleanName($item)] = trans($this->cleanName($item), [], $lang);
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
     * @return int
     * @throws \Exception
     */
    public function pushTranslation($lang, $file, $data) {

        $filePath = $this->getPathForFile($lang, $file);
        $extension = extractExtension(basename($filePath));

        if( $extension == 'php' ) {
            $content = '<?php return ' . var_export($data, true) . ';';
            return \File::put($filePath, $content);
        } elseif( in_array($extension, ['yml','yaml']) ) {
            $yaml = Yaml::dump($data);
            return \File::put($filePath, $yaml);
        } else {
            return false;
        }
    }

    /**
     * @param $lang
     * @param $file
     * @return
     * @throws \Exception
     */
    protected function getPathForFile($lang, $file, $subDir = '') {
        if(!isset($this->getLangDirectory()[$lang])) {
            Throw new \Exception('Lang ' . $lang . ' is not in the list. Use one of them : ' . var_export($this->getAvailableLang()));
        }

        foreach(glob($this->getLangDirectory()[$lang].''.$subDir.'/*') as $item) {
            if(is_file($item) && $this->cleanName(basename($item)) == $file) {
                return $item;
            } elseif(is_dir($item)) {
                $subDir = dirname($item);
                return $this->getPathForFile($lang, $file, '/'.$subDir);
            }
        }

        Throw new \Exception('No file found for page ' . $file);

    }

}