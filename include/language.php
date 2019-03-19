<?php
use Inc\Claz\PdoDbException;
use Inc\Claz\SystemDefaults;

/*
 * Read language information
 * 1. reads default-language file
 * 2. reads requested language file
 * 3. make some editing (Upper-Case etc.)
 * Not in each translated file need to be each all translations, only in the default-lang-file (english)
 */
global $LANG, $databaseBuilt, $pdoDb;
unset($LANG);
$LANG = array();

if ($databaseBuilt) {
    $found = false;
    try {
        $tables = $pdoDb->query("SHOW TABLES");

        // if upgrading from old version then getDefaultLang wont work during install
        $tbl = TB_PREFIX . 'system_defaults';
        foreach ($tables as $table) {
            if ($table[0] == $tbl) {
                $found = true;
                break;
            }
        }
    } catch (PdoDbException $pde) {
        error_log("language.php: DB error performing SHOW TABLES. Error: " . $pde->getMessage());
    }

    if ($found) {
        $language = SystemDefaults::getDefaultLanguage();
    } else {
        $language = "en_US";
    }
} else {
    $language = "en_US";
}

function getLanguageArray($lang = '') {
    global $ext_names, $LANG;

    if (!empty($lang)) {
        $language = $lang;
    } else {
        global $language;
    }

    $langPath = "lang/";
    $langFile = "/lang.php";
    include ($langPath . "en_US" . $langFile);
    if (file_exists($langPath . $language . $langFile)) {
        include ($langPath . $language . $langFile);
    }

    foreach ($ext_names as $ext_name) {
        if (file_exists("extensions/$ext_name/lang/$language/lang.php")) {
            include_once ("extensions/$ext_name/lang/$language/lang.php");
        }
    }

    return $LANG;
}

function getLanguageList() {
    $xmlFile = "info.xml";
    $langPath = "lang/";
    $folders = null;

    if ($handle = opendir($langPath)) {
        for ($i = 0; $file = readdir($handle); $i++) {
            $folders[$i] = $file;
        }
        closedir($handle);
    }

    $languages = null;
    $i = 0;

    foreach ($folders as $folder) {
        $file = $langPath . $folder . "/" . $xmlFile;
        if (file_exists($file)) {
            $values = simplexml_load_file($file);
            $languages[$i] = $values;
            $i++;
        }
    }

    return $languages;
}

$LANG = getLanguageArray();
