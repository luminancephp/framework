<?php
/**
 * @file Luminance/Http/Parser.php
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