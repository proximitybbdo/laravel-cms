<?php
function saveFile($file, $destination)
{
    return \BBDO\Cms\Helpers\FileUpload::saveFile($file, $destination);
}

function isRunning($startDate, $endDate)
{
    return \BBDO\Cms\Helpers\General::IsRunning($startDate, $endDate);
}

function generateKey()
{
    return \BBDO\Cms\Helpers\General::generateKey();
}

function keyOutput($key)
{
    return \BBDO\Cms\Helpers\General::keyOutput($key);
}

function cleanSegments()
{
    return \BBDO\Cms\Helpers\Helpers::cleanSegments();
}

function activeClass($check, $strict = true)
{
    \   BBDO\Cms\Helpers\Helpers::activeClass($check, $strict);
}

function isUrl($check, $strict = true)
{
    return \BBDO\Cms\Helpers\Helpers::isUrl($check, $strict);
}

function urlLang($parts)
{
    return \BBDO\Cms\Helpers\Helpers::urlLang($parts);
}

function arrayToObject($d)
{
    return \BBDO\Cms\Helpers\Helpers::arrayToObject($d);
}

function inputArray($config, $type, $model, $multiple_index = null, $block_type = '', $index = '')
{
    return \BBDO\Cms\Helpers\Input::inputArray($config, $type, $model, $multiple_index, $block_type, $index);
}

function linksArray($module, $item, $lang, $block_type = null, $version = 1, $index = 0)
{
    return \BBDO\Cms\Helpers\Input::linksArray($module, $item, $lang, $block_type, $version, $index);
}

function formatBlockType($input)
{
    return \BBDO\Cms\Helpers\Input::formatBlockType($input);
}

function indexBlockType($input)
{
    return \BBDO\Cms\Helpers\Input::indexBlockType($input);
}

function logAction($module, $action, $itemId = null, $lang = null, $data = null)
{
    \BBDO\Cms\Helpers\Log::action($module, $action, $itemId, $lang, $data);
}

if (!function_exists('xcopy')) {
    /**
     * Copy recursivly a directory and his files
     * @param $source
     * @param $dest
     * @param int $permissions
     * @return bool
     */
    function xcopy($source, $dest, $permissions = 0755)
    {
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }
        if (is_file($source)) {
            return copy($source, $dest);
        }
        if (!is_dir($dest)) {
            mkdir($dest, $permissions);
        }
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            xcopy("$source/$entry", "$dest/$entry", $permissions);
        }
        $dir->close();
        return true;
    }
}
if (!function_exists('extractExtension')) {
    /**
     * Extract the last extension
     * @param $string
     * @return mixed
     */
    function extractExtension($string)
    {
        $explode = explode('.', $string);
        return end($explode);
    }
}
if (!function_exists('getDateDifferenceFromNow')) {
    /**
     * @param $date
     * @return array
     */
    function getDateDifferenceFromNow($date)
    {
        $datetime1 = new DateTime();
        $datetime2 = new DateTime($date);
        $difference = $datetime1->diff($datetime2);
        return (array)$difference;
    }
}
if (!function_exists('formatDateToUser')) {
    /**
     * @param $date
     * @param string $format
     * @return false|string
     */
    function formatDateToUser($date, $format = 'd/m/Y H:i')
    {
        return date($format, strtotime($date));
    }
}
if (!function_exists('generateRandomString')) {
    /**
     * @param $lenght
     * @param string $charInput
     * @return string
     */
    function generateRandomString($lenght, $charInput = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
    {
        srand((double)microtime() * 1000000);
        $string = '';
        for ($i = 0; $i < $lenght; $i++) {
            $string .= $charInput[rand() % strlen($charInput)];
        }
        return $string;
    }
}
if (!function_exists('reversibleEncryption')) {
    /**
     * @param array $data
     * @param null $key
     * @return string
     */
    function reversibleEncryption(array $data, $key = null)
    {
        $hashids = new \Hashids\Hashids(is_null($key) ? env('APP_KEY') : $key);
        return $hashids->encode($data);
    }
}
if (!function_exists('reversibleDecryption')) {
    /**
     * @param $encryptedString
     * @param null $key
     * @return array
     */
    function reversibleDecryption($encryptedString, $key = null)
    {
        $hashids = new \Hashids\Hashids(is_null($key) ? env('APP_KEY') : $key);
        return $hashids = $hashids->decode($encryptedString);
    }
}
if (!function_exists('antiSpamEmailEncode')) {
    /**
     * @param string $email
     * @param string $linkText
     * @param string $attrs
     * @return string
     */
    function antiSpamEmailEncode($email = 'info@domain.com', $linkText = 'Contact Us', $attrs = 'class="emailencoder"')
    {
        $email = str_replace('@', '&#64;', $email);
        $email = str_replace('.', '&#46;', $email);
        $email = str_split($email, 5);
        $linkText = str_replace('@', '&#64;', $linkText);
        $linkText = str_replace('.', '&#46;', $linkText);
        $linkText = str_split($linkText, 5);
        $part1 = '<a href="ma';
        $part2 = 'ilto&#58;';
        $part3 = '" ' . $attrs . ' >';
        $part4 = '</a>';
        $encoded = '<script type="text/javascript">';
        $encoded .= "document.write('$part1');";
        $encoded .= "document.write('$part2');";
        foreach ($email as $e) {
            $encoded .= "document.write('$e');";
        }
        $encoded .= "document.write('$part3');";
        foreach ($linkText as $l) {
            $encoded .= "document.write('$l');";
        }
        $encoded .= "document.write('$part4');";
        $encoded .= '</script>';
        return $encoded;
    }
}
if (!function_exists('listDirectory')) {
    /**
     * @param $directory
     * @param array $fileExclusion
     */
    function listDirectory($directory, array $fileExclusion = ['.', '..'])
    {
        $list = [];
        if (is_dir($directory)) {
            if ($handle = opendir($directory)) {
                while (($file = readdir($handle)) !== false) {
                    if (!in_array($file, $fileExclusion)) {
                        $list[] = $directory . $file;
                    }
                }
                closedir($handle);
            }
        }
    }
}
if (!function_exists('cleanString')) {
    function cleanString($text)
    {
        $utf8 = array(
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            '/ñ/' => 'n',
            '/Ñ/' => 'N',
            '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u' => ' ', // Literally a single quote
            '/[“”«»„]/u' => ' ', // Double quote
            '/ /' => ' ', // nonbreaking space (equiv. to 0x160)
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }
}
if (!function_exists('getIp')) {
    function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }
}
if (!function_exists('geocode')) {
    function geocode($address)
    {
        $lat = $lng = 0;
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=' . env('GOOGLE_KEY');
        $gps = file_get_contents($url);
        if (!empty($gps)) {
            $gps = json_decode($gps, true);
            try {
                $lat = $gps['results'][0]['geometry']['location']['lat'];
                $lng = $gps['results'][0]['geometry']['location']['lng'];
            } catch (\Exception $e) {
                \Log::error('Unable to get lat/lng for ' . $address . ' with error ' . $e->getMessage());
                \Log::error('Raw response from ' . $url . ': ' . json_encode($gps));
                $lat = 50.8427501;
                $lng = 4.3515499;
            }
        }
        return [
            'lat' => $lat,
            'lng' => $lng
        ];
    }
}
if (!function_exists('xml2array')) {
    function xml2array($xmlObject, $out = array())
    {
        foreach ((array)$xmlObject as $index => $node) {
            $out[$index] = (is_object($node)) ? xml2array($node) : $node;
        }
        return $out;
    }
}

if (!function_exists('is_countable')) {
    function is_countable($var)
    {
        return (is_array($var) || $var instanceof Countable);
    }
}

/**
 * Add cms namespace for view if view is not overrided.
 * @param $viewName
 * @return string
 */
function viewPrefixCmsNamespace($viewName) {
    if(view()->exists($viewName)) {
        return $viewName;
    } elseif(view()->exists('bbdocms::'.$viewName)) {
        return 'bbdocms::'.$viewName;
    } else {
        Throw new \http\Exception\InvalidArgumentException('View ' . $viewName . ' not found in your views neither in the bbdocms namespace');
    }
}

/**
 * @param null $view
 * @param array $data
 * @param array $mergeData
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 */
function bbdoview($view = null, $data = [], $mergeData = []) {
    return view( viewPrefixCmsNamespace($view), $data, $mergeData);
}