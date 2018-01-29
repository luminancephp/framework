<?php
/**
 * @file Luminance/Template/Parser.php
 * @namespace Luminance\Template
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Template;
use Luminance\Configuration\Loader;

/**
 * This class providers a lightweight and basic
 * template engine and parser, which supports
 * simple replacements, and returns the
 * requested string. It can be used
 * anywhere, however is not
 * recommended for complex
 * templates and large
 * datasets.
 *
 * @author Michael <michaeldoestech@gmail.com>
 */
class Parser
{
    /**
     * @var string
     */
    private $template_path = "";

    /**
     * @var string
     */
    private $template_content = "";

    /**
     * @var array
     */
    private $replacements = array();

    /**
     * @var string
     */
    public $template_name = "";

    /**
     * Parser constructor, this will accept a template name,
     * and optional replacements array for your template.
     *
     * @param string $template_name
     * @param array $replacements
     */
    public function __construct(string $template_name, array $replacements = array())
    {
        $this->template_name = $template_name;
        if(!empty($replacements))
        {
            $this->replacements = $replacements;
        }
        $this->findTemplatePath();
    }

    /**
     * This will hunt for the template path, using
     * very basic detection in the userland
     * directory for Views, and looks for
     * $TEMPLATE_NAME.html files, but
     * if you use PHP for templates
     * this will not work for you.
     */
    protected function findTemplatePath()
    {
        if(file_exists("app/Views/".$this->template_name.".html"))
        {
            $this->template_path = "app/Views/".$this->template_name.".html";
            $this->template_content = file_get_contents($this->template_path);
        }
    }

    /**
     * This will set the required template replacements
     * for the given template, set in constructor, or
     * in setTemplate method.
     *
     * @param array $replacements
     */
    public function setReplacements(array $replacements = array())
    {
        $this->replacements = $replacements;
    }

    /**
     * Sets the template name, and finds the
     * template content, loads it into
     * the local $template_content
     * variable.
     *
     * @param string $template_name
     */
    public function setTemplate(string $template_name)
    {
        $this->template_name = $template_name;
        $this->findTemplatePath();
    }

    /**
     * If for some reason you need to reload
     * the specified template, you can call
     * this method, and it will re-run the
     * findTemplatePath method and load
     * the template back into the
     * $this->template_content
     * variable for you.
     */
    public function reloadTemplate()
    {
        $this->findTemplatePath();
    }

    /**
     * All application configuration variables
     * are available to templates parsed in
     * this Template Parser class here.
     */
    protected function addApplicationConfigToReplacements()
    {
    	$app_config = new Loader("application");
        $array_of_config = $app_config->config;
        $replacements = $this->replacements;
        if(is_array($array_of_config))
        {
            foreach($array_of_config as $item => $value)
            {
                if(is_array($value))
                {
                    unset($array_of_config[$item]); // strip multi-level arrays
                }
            }
            $final = array_merge($replacements, $array_of_config);
            $this->replacements = $final;
        }
    }

    /**
     * This logical replacement will simply parse and replace
     * anything with the syntax listed below in the examples.
     * We use the syntax class for the logical replacements
     * and if we need to extend templates, etc. and we
     * write them to the local cache storage.
     *
     * @param string $content
     *
     * @return string
     */
    protected function runLogicalReplacements(string $content = "") : string
    {
        if(stristr($content, "{%"))
        {
            $set_regex = "/\{\%.*set.*\%\}/i";
            preg_match_all($set_regex, $content, $matches, PREG_PATTERN_ORDER);
            if(count($matches) > 0)
            {
                foreach($matches as $match)
                {
                    $tmp = $match[0];
                    $orig = $tmp;
                    // echo $orig;
                    // echo "\n";
                    $new = str_replace("{% set ", "", $orig);
                    $new = str_replace(" %}", "", $new);
                    $words = explode(" ", $new, 3);
                    $final = '<?php '."$".$words[0]." = " . $words[2] . ";" .' ?>';
                    $content = str_replace($orig, $final, $content);
                }
            }
            $foreach_end_regex = "/\{\%.*endforeach.*\%\}/i";
            preg_match_all($foreach_end_regex, $content, $f_matches, PREG_PATTERN_ORDER);
            if(count($f_matches) > 0)
            {
                foreach($f_matches as $match)
                {
                    $tmp = $match[0];
                    $orig = $tmp;
                    $new = str_replace('{% endforeach %}', '<?php endforeach; ?>', $orig);
                    $content = str_replace($orig, $new, $content);
                }
            }
            $foreach_regex = "/\{\%.*foreach.*\%\}/i";
            preg_match_all($foreach_regex, $content, $fe_matches, PREG_PATTERN_ORDER);
            if(count($fe_matches) > 0)
            {
                foreach($fe_matches as $match)
                {
                    $tmp = $match[0];
                    $orig = $tmp;
                    $new = str_replace('{% foreach', '<?php foreach(', $orig);
                    $new = str_replace(' %}', '): ?>', $orig);
                    $content = str_replace($orig, $new, $content);
                }
            }
            $simple_replacement = "/\@.*\;/i";
            preg_match_all($simple_replacement, $content, $sr_matches, PREG_PATTERN_ORDER);
            if(count($sr_matches) > 0)
            {
                foreach($fe_matches as $match)
                {
                    $tmp = $match[0];
                    $orig = $tmp;
                    $new = str_replace('@', '<?php echo ', $orig);
                    $new = str_replace(';', '; ?>', $orig);
                    $content = str_replace($orig, $new, $content);
                }
            }
        }
        return $content;
    }

    /**
     * This will run through and make the replacements
     * in the template specified, with it's content
     * being replaced, updated, and then returned
     * to the end user.
     *
     * @param boolean $return
     *
     * @return string
     */
    public function render(bool $return = false)
    {
        $template_content = $this->template_content;
        $this->addApplicationConfigToReplacements();
        $replacements = $this->replacements;
        foreach($replacements as $replacement => $value)
        {
            $template_content = str_replace('{{ '.$replacement.' }}', $value, $template_content);
        }
        $this->runLogicalReplacements($template_content);
        $this->template_content = $template_content;
        if($return)
        {
            return $template_content;
        }
        else
        {
            echo $template_content;
        }
    }
}
