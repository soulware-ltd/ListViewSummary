<?php

namespace Soulware;

class viewMerge {

    //screen | console | false
    protected $log_messages = "screen";

    public function install() {

        require_once(__DIR__ . '/view_merge.config.php');

        if (isset($merge_config) && is_array($merge_config)) {

            $this->log("metadata info found.");

            $this->log("iterate over user defined views to extend");
            foreach ($merge_config as $file_info) {

                $paths = $this->getPaths($file_info['module'], $file_info['type'], $file_info['sourcefile']);

                $this->log("custom path: " . $paths['custom_path']);
              
                if (isset($paths['custom_path']) && !empty($paths['custom_path']) && is_file($paths['custom_path'])) {

                    $this->log("if view file exists in custom folder");

                    $this->log("opening " . $paths['custom_path']);

                    $file_content = $this->getFile($paths['custom_path']);

                    if ($class_name = $this->getClassName($file_content)) {

                        $this->log("init class $class_name");

                        $object = new $class_name();

                        if (method_exists($object, $file_info['method_name'])) {

                            $this->log($class_name . "::" . $file_info['method_name'] . " exists, extend method with custom code");

                            $new_content = $this->extendMethod($file_content, $file_info);
                        } else {

                            $this->log($class_name . "::" . $file_info['method_name'] . " DOES NOT exists, extend class with method");

                            $new_content = $this->extendClass($file_content, $class_name, $file_info);
                        }

                        $this->log("save file to " . $paths['custom_path']);

                        $this->saveFile($paths['custom_path'], $new_content, true);
                    } else {

                        $this->log($paths['custom_path'] . " has no class.");
                    }
                } elseif (isset($paths['path']) && !empty($paths['path']) && is_file($paths['path'])) {

                    $this->log("if view file exists in modules folder");

                    $this->log("opening " . $paths['path']);

                    $file_content = $this->getFile($paths['path']);

                    if ($class_name = $this->getClassName($file_content)) {

                        $this->log("init class $class_name");

                        $object = new $class_name();

                        $extend = (method_exists($object, $file_info['method_name']));

                        $this->log("create new class with name " . $class_name . " and method " . $file_info['method_name']);

                        $new_content = $this->createClass($class_name, $file_info['method_name'], $file_info['content'], $file_info['insert_method'], $paths['path'], $extend);

                        $this->log("save file to " . $paths['custom_path']);

                        $this->saveFile($paths['custom_path'], $new_content, true);
                    } else {

                        $this->log($paths['custom_path'] . " has no class.");
                    }
                } else {

                    $this->log("view file doesn't exist, extend the given base class");

                    $this->log("creating class from scratch, extending the base class " . $file_info['base_class']);

                    $object = new $file_info['base_class']();
                    $extend = (method_exists($object, $file_info['method_name']));

                    $this->log("create class");

                    $new_content = $this->createClass($file_info['base_class'], $file_info['method_name'], $file_info['content'], $file_info['insert_method'], null, $extend);

                    $this->log("save file to " . $paths['custom_path']);

                    $this->saveFile($paths['custom_path'], $new_content, true);
                }

                $this->log("bye :)");
            }
        } else {

            $this->log("no merge config data found.");
        }
    }

    public function uninstall() {

        require_once(__DIR__ . '/view_merge.config.php');

        if (isset($merge_config) && is_array($merge_config)) {

            $this->log("metadata info found.");

            $this->log("iterate over user defined views to extend");
            foreach ($merge_config as $file_info) {

                $paths = $this->getPaths($file_info['module'], $file_info['type'], $file_info['sourcefile']);

                $this->log("custom path: " . $paths['custom_path'] . ", path: " . $paths['path']);

                if (isset($paths['custom_path']) && !empty($paths['custom_path']) && is_file($paths['custom_path'])) {

                    $this->log("if view file exists in custom folder");

                    $this->log("opening " . $paths['custom_path']);

                    $file_content = $this->getFile($paths['custom_path']);

                    $new_content = $this->removeExtendPackage($file_content, $file_info);

                    $this->saveFile($paths['custom_path'], $new_content, true);

                    $this->log("bye :)");
                }
            }
        } else {

            $this->log("no merge config data found.");
        }
    }

    //log
    
    protected function log($message) {

        if ($this->log_messages == "console") {
            $this->logToConsole($message);
        } elseif ($this->log_messages == "screen") {
            $this->logToScreen($message);
        }
    }

    protected function logToConsole($message) {
        echo $message . "\n";
    }

    protected function logToScreen($message) {
        echo $message . "<br />";
    }

    //HELPERS

    protected function removeExtendPackage($file_content, $file_info) {

        if (($file_info['insert_method'] == 'append' || $file_info['insert_method'] == 'prepend') && isset($file_info['method_remove']) && $file_info['method_remove']) {
            
            $pattern = "/(" . $file_info['method_visit'] . "\s+)?function\s+" . $file_info['method_name'] . "\s*\([^\)]*\)\s*{/";
            
            //$content_to_remove = "\n\n" . $file_info['method_visit'] . " function " . $file_info['method_name'] . "(){\n\n" . $file_info['content'] . "\n}\n";
            //return preg_replace($content_to_insert, '', $file_content);
            return $this->removeMethod($pattern, $file_content);
        }
        if ($file_info['insert_method'] == 'replace') {
            return str_replace($file_info['content'], $file_info['original_content'], $file_content);
        }
        return str_replace($file_info['content'], '', $file_content);
    }

    protected function removeMethod($pattern, $content) {

        $return_array = array();

        if (preg_match($pattern, $content, $return_array, PREG_OFFSET_CAPTURE)) {

            $content_length = strlen($content);
            $pattern_length = strlen($return_array[0][0]);
            $pattern = $return_array[0][0];
            $pattern_start_position = $return_array[0][1];

            $content_array = explode($pattern, $content);

            $first_part = $content_array[0];
            $second_part = $content_array[1];

            $relative_position_closing_bracket = $this->closingBracketPosition($second_part);
            $method_length = $pattern_length + $relative_position_closing_bracket;

            $return_string = substr_replace($content, '', $pattern_start_position, $method_length);
            
            return $return_string;
            
        }
    }
    
    protected function getPaths($module, $type, $filename) {
        if ($module != 'application') {
            $path = 'modules/' . $module . '/' . $type . '/' . $filename;
            $custom_path = 'custom/' . $path;

            $return_array = array();

            $return_array['path'] = $path;
            $return_array['custom_path'] = $custom_path;
        } else {
            $path = $type . '/' . $filename;
            $return_array = array();

            $return_array['path'] = $path;
            $return_array['custom_path'] = $path;
        }

        return $return_array;
    }

    protected function getFile($path) {

        if ($file_permission_error = $this->filePermissionError($path))
            die($file_permission_error);

        $content = file_get_contents($path);

        if ($new_content = $this->removeSugarEntry($content)) {

            file_put_contents($path, $new_content);
        }

        //works only if run inside of sugar, need some workaround
        //one possible way is to check for extend part and create a dummy class on the fly
        require_once($path);

        return ($new_content) ? $new_content : $content;
    }

    protected function removeSugarEntry($content) {

        $pattern = "sugarEntry";

        $full_pattern = "if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');";

        $regexp_pattern = '/if\s*\(\s*\!\s*defined\s*\(\s*(\'|\")\s*sugarEntry\s*(\'|\")\s*\)\s*\|\|\s*\!sugarEntry\s*\)\s*die\s*\(.*\)\s*;/';

        if (strpos($content, $pattern)) {

            return preg_replace($regexp_pattern, '', $content);
        } else {

            return false;
        }
    }

    //TODO
    protected function filePermissionError($path) {

        return false;
    }

    protected function getClassName($content) {

        $pattern = '/class\s*[A-Za-z0-9_]+\s*(extends\s+[A-Za-z0-9_]+\s*)?\{/';

        $return_array = array();

        if (preg_match($pattern, $content, $return_array)) {

            $className = $return_array[0];
            $className = str_replace('class', '', $className);
            $className = str_replace('extends', '', $className);
            $className = str_replace('{', '', $className);
            $className = trim($className);
            $className = str_replace('	', ' ', $className);
            $classNameArray = explode(' ', $className);

            $returnClassName = $classNameArray[0];

            return $returnClassName;
        } else {

            return false;
        }
    }

    protected function extendMethod($file_content, $file_info) {

        if ($file_info['insert_method'] == 'append') {

            $pattern = "/function\s+" . $file_info['method_name'] . "\s*\([^\)]*\)\s*\{/";

            return $this->append($pattern, $file_info['content'], $file_content);
        } else if ($file_info['insert_method'] == 'prepend') {

            $pattern = "/function\s+" . $file_info['method_name'] . "\s*\([^\)]*\)\s*\{/";

            return $this->prepend($pattern, $file_info['content'], $file_content);
        } else {
            $pattern = "/function\s+" . $file_info['method_name'] . "\s*\([^\)]*\)\s*\{/";

            return $this->replace($pattern, $file_info, $file_content);
        }
    }

    protected function extendClass($file_content, $class_name, $file_info) {

        $pattern = "/class\s+($class_name)\s*((extends)\s+[a-zA-Z0-9]+\s*)?\{/";

        $content_to_insert = "\n\n" . $file_info['method_visit'] . " function " . $file_info['method_name'] . "(){\n\n" . $file_info['content'] . "\n}\n";

        return $this->prepend($pattern, $content_to_insert, $file_content);
    }

    protected function prepend($pattern, $content_to_insert, $content) {

        $return_array = array();

        if (preg_match($pattern, $content, $return_array)) {

            $content_to_add = $return_array[0] . "\n\n" . $content_to_insert . "\n";

            $new_content = str_replace($return_array[0], $content_to_add, $content);

            return $new_content;
        } else {

            return false;
        }
    }

    protected function append($pattern, $content_to_insert, $content) {

        $return_array = array();

        if (preg_match($pattern, $content, $return_array, PREG_OFFSET_CAPTURE)) {

            $content_length = strlen($content);
            $pattern_length = strlen($return_array[0][0]);
            $pattern = $return_array[0][0];
            $pattern_start_position = $return_array[0][1];

            $content_array = explode($pattern, $content);

            $first_part = $content_array[0];
            $second_part = $content_array[1];

            $relative_position_closing_bracket = $this->closingBracketPosition($second_part);

            $second_part_first_part = substr($second_part, 0, $relative_position_closing_bracket - 1);

            $second_part_second_part = substr($second_part, $relative_position_closing_bracket - 1);

            $return_string = $first_part . $pattern . $second_part_first_part . "\n" . $content_to_insert . "\n" . $second_part_second_part;

            return $return_string;
        }
    }

    protected function replace($pattern, $file_info, $content) {

        if (preg_match($pattern, $content, $return_array)) {
            $new_content = str_replace($file_info['original_content'], $file_info['content'], $content);
            return $new_content;
        }
        return false;
    }

    protected function closingBracketPosition($string) {

        $open = 0;
        $close = 0;

        $balance = 0;

        $char_num = 0;

        $closing_position = false;

        $chunks = str_split($string);

        foreach ($chunks as $char) {

            if ($char == "{")
                $balance++;
            if ($char == "}")
                $balance--;
            $char_num++;

            //echo "char: '" . $balance ."', position: " . $char_num . "\n";

            if ($balance == -1 && $closing_position == false)
                $closing_position = $char_num;
        }

        return $closing_position;
    }

    protected function saveFile($path, $content, $add_sugar_entry = true) {

        if ($add_sugar_entry) {

            $sugar_entry = "<?php \n\nif(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');\n";

            $content = str_replace('<?php', $sugar_entry, $content);
        }

        $this->createDirStructure($path);

        file_put_contents($path, $content);
    }

    protected function createClass($class_name, $method_name, $content_to_insert, $insert_method, $base_class_path, $extend) {

        $content = "<?php\n";
        if ($base_class_path != null)
            $content .= "require_once('" . $base_class_path . "');\n";
        $content .= "class Custom$class_name extends $class_name{\n\n";
        $content .= "public function $method_name(){\n\n";
        if ($extend && $insert_method == 'append')
            $content .= "parent::$method_name();\n";
        $content .= $content_to_insert . "\n";
        if ($extend && $insert_method == 'prepend')
            $content .= "parent::$method_name();\n";
        $content .= "}\n}\n?>";

        return $content;
    }

    protected function createDirStructure($path) {

        $current_path = "";

        $dir_array = $this->getDirArray($path);

        foreach ($dir_array as $dir) {

            $current_path .= $dir . "/";

            if (!is_dir($current_path)) {

                mkdir($current_path);
            }
        }

        return true;
    }

    protected function getDirArray($path) {

        $return_array = explode('/', $path);
        array_pop($return_array);

        return $return_array;
    }

}
