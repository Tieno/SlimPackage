# SlimPackage
SlimPackage is a bundling of the following components. It can be used as a starting point for small projects.


* Model: PHPActiveRecord ([kla/php-activerecord](https://github.com/kla/php-activerecord))
* View: Twig ([fabpot/Twig](https://github.com/fabpot/Twig))
* Controller: Slim ([codeguy/Slim](https://github.com/codeguy/Slim))
* HTML/CSS/Javascript: Twitter Bootstrap ([twitter/bootstrap](https://github.com/twitter/bootstrap))


## Adjustments/hacks
To get these components working together (or for personal enjoyment) the following adjustments/hacks were made:

### 1. To vendor/php-activerecord/lib/model.php
to make PHPActiveRecord work with the Twig templating engine the __isset() function and set_timestamps() functions were changed.
	
	<?php
	/**
	 * Determines if an attribute exists for this {@link Model}.
	 *
	 * @param string $attribute_name
	 * @return boolean
	 */
	public function __isset($attribute_name)
	{
		return array_key_exists($attribute_name,$this->attributes) 
			|| array_key_exists($attribute_name,static::$alias_attribute)
			|| method_exists($this, "attributes");
	}

	/**
	 * Updates a model's timestamps.
	 */
	public function set_timestamps()
	{
		$now = date('Y-m-d H:i:s');
		try {
			$this->updated_at = $now;
		} catch (UndefinedPropertyException $e) {
				
		}
		try {
			if($this->is_new_record()) {
				$this->created_at = $now;
			}
		} catch (UndefinedPropertyException $e) {
		}

	}

### 2. incorporated [Slim-Extras](https://github.com/codeguy/Slim-Extras)/TwigView.php
This is an extension to get the Slim framework working with Twig templating engine

#### 2.1 To the TwigView extension the following adjustments were made

##### 2.1.1 Added a static twigFunctions array for easy setting of TwigFunctions and easy loading of Twigfunctions in getEnvironment()
	
	/**
     * @var TwigFunction the custom functions you want to load
     * @param functionName alias for the function
     * @param function the actual function (can be a static class method)
     */
    public static $twigFunctions = array();
    /**
     * @var TwigEnvironment The Twig environment for rendering templates.
     */
    private $twigEnvironment = null;
	
	/**
     * Creates new TwigEnvironment if it doesn't already exist, and returns it.
     *
     * @return Twig_Environment
     */
    public function getEnvironment() {
        if ( !$this->twigEnvironment ) {
            require_once self::$twigDirectory . '/Autoloader.php';
            Twig_Autoloader::register();
            $loader = new Twig_Loader_Filesystem($this->getTemplatesDirectory());
            $this->twigEnvironment = new Twig_Environment(
                $loader,
                self::$twigOptions
            );
            
            foreach (self::$twigFunctions as $function) {
            	$this->twigEnvironment->addFunction($function['functionName'], new Twig_Function_Function($function['function']));	            
            }
            
            
            $extension_autoloader = dirname(__FILE__) . '/Extension/TwigAutoloader.php';
            if (file_exists($extension_autoloader)) {
                require_once $extension_autoloader;
                Twig_Extensions_Autoloader::register();

                foreach (self::$twigExtensions as $ext) {
                    $this->twigEnvironment->addExtension(new $ext);
                }
            }
        }
        return $this->twigEnvironment;
    }
##### 2.1.2 getRender() function added just to catch the result of a template render

	public function getRender($template, $data) {
   		$env = $this->getEnvironment();
   		$template = $env->loadTemplate($template);
   		$data = array_merge($data, $this->data);
   		return $template->render($data);
   	}