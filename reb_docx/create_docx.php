<?
class CreateDocxFromTemplate {

    public static function copyDirect($source, $dest, $over = false) {
        if (!is_dir($dest))
            mkdir($dest);
        if ($handle = opendir($source)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != '.' && $file != '..') {
                    $path = $source . '/' . $file;
                    if (is_file($path)) {
                        if (!is_file($dest . '/' . $file || $over))
                            if (!@copy($path, $dest . '/' . $file)) {
                                echo "('.$path.') Ошибка!!! ";
                            }
                    } elseif (is_dir($path)) {
                        if (!is_dir($dest . '/' . $file))
                            mkdir($dest . '/' . $file);
                        self::copyDirect($path, $dest . '/' . $file, $over);
                    }
                }
            }
            closedir($handle);
        }
    }

    public static function zip($source, $destination) {
        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }

        $zip = new ZipArchive();
        if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
            return false;
        }

        $source = str_replace('\\', '/', realpath($source));

        if (is_dir($source) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file) {
                $file = str_replace('\\', '/', $file);

                // Ignore "." and ".." folders
                if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..')))
                    continue;

                $file = realpath($file);
                $file = str_replace('\\', '/', $file);

                if (is_dir($file) === true) {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                } else if (is_file($file) === true) {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        } else if (is_file($source) === true) {
            $zip->addFromString(basename($source), file_get_contents($source));
        }
        return $zip->close();
    }

    public static function unZip($source, $destination) {
        $zip = new ZipArchive();
        if ($zip->open($source) === true) {
            $zip->extractTo($destination);
            $zip->close();
            return true;
        } else
            return false;
    }

    public static function removeDirectory($dir) {
        if ($objs = glob($dir . "/*")) {
            foreach ($objs as $obj) {
                is_dir($obj) ? self::removeDirectory($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }

    public static function fileForceDownload($file) {
        if (file_exists($file)) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header ("Accept-Ranges: bytes");
            header ("Content-Length: ".filesize($file));
            header('Content-Disposition: attachment; filename=' . basename($file)); 
            header('X-SendFile: ' . realpath($file));
            readfile($file);
            exit;
        }
    }
    
    public static function fileWriteFields($file, $arFields) {
        $patterns = array();
        $replacements = array();
        foreach ($arFields as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $k => $v) {
                    $patterns[] = '/#' . $key . $k . '#/';
                    $replacements[] = $v == 'Y' ? '☑' : '☐';
                }
            } else {
                $patterns[] = '/#' . $key . '#/';
                if ($val === 'Y' || $val === 'N') {
                    $replacements[] = $val == 'Y' ? '☑' : '☐';
                } else {
                    $replacements[] = $val;
                }
            }
        }
        $xml = file_get_contents($path . '\word\document.xml');
        $xml = preg_replace($patterns, $replacements, $xml);
        $xml = preg_replace('!#[a-zA-Z0-9-]{1,}#!', '', $xml);

        $fp = fopen($path . '\word\document.xml', 'w');
        fwrite($fp, $xml);
        fclose($fp);
    }

    public static function createDocx($templateDocx, $arFields, $newFileName = 'new.docx', $tmpPath = __DIR__ . '\tmp') {
        if(!file_exists($templateDocx) || !is_array($arFields)) return false;

        $info = pathinfo($templateDocx);
        $filename = basename($templateDocx,'.'.$info['extension']);
        $path = $tmpPath . '\\' . $filename;
        self::unZip($templateDocx, $path);

        $patterns = array();
        $replacements = array();
        foreach ($arFields as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $k => $v) {
                    $patterns[] = '/#' . $key . $k . '#/';
                    $replacements[] = $v == 'Y' ? '☑' : '☐';
                }
            } else {
                $patterns[] = '/#' . $key . '#/';
                if ($val === 'Y' || $val === 'N') {
                    $replacements[] = $val == 'Y' ? '☑' : '☐';
                } else {
                    $replacements[] = $val;
                }
            }
        }
        $xml = file_get_contents($path . '\word\document.xml');
        $xml = preg_replace($patterns, $replacements, $xml);
        
        $xml = preg_replace('!#[a-zA-Z0-9]{1,}[0-9]{1,2}#!', '☐', $xml);
        $xml = preg_replace('!#[a-zA-Z0-9-]{1,}#!', '', $xml);

        $fp = fopen($path . '\word\document.xml', 'w');
        fwrite($fp, $xml);
        fclose($fp);

        self::zip($path, $tmpPath . '\\' . $newFileName);

        rename($path . '\_rels\.rels', $path . '\_rels\rels.tmp');
        self::removeDirectory($path);

        self::fileForceDownload($tmpPath . '\\' . $newFileName);
    }
}

$arFields = $_REQUEST;
$arFields['FIO'] = $arFields['FIRST_NAME'].' '.$arFields['NAME'].' '.$arFields['SECOND_NAME'];
foreach($arFields as $key => $val) {
    if(substr_count($val, $key)) {
        $arFields[$val] = 'Y';
    }
}

CreateDocxFromTemplate::createDocx($_SERVER['DOCUMENT_ROOT'] . '\default\app3.docx', $arFields);

?>