<?php
/**
 * Created by PhpStorm.
 * User: Agustín Houlgrave
 * Date: 25/09/2015
 * Time: 10:06 AM
 */

namespace Brown\SiteBundle\Twig\Extension;


use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

class BrownExtension extends \Twig_Extension
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var \Twig_Environment
     */
    protected $environment;

    public function initRuntime(Twig_Environment $environment)
    {
        $this->setEnvironment($environment);
    }

    /**
     * Get current controller name
     */
    public function getControllerName()
    {
        if(null !== $this->request)
        {
            $pattern = '#Controller\\\([a-zA-Z]*)Controller#';
            $matches = array();
            preg_match($pattern, $this->request->get('_controller'), $matches);

            return strtolower($matches[1]);
        }

    }

    /**
     * Get current bundle name
     */
    public function getBundleName()
    {
        if(null !== $this->request)
        {
            $fullName = $this->request->get('_controller');
            $exploded = explode('\\', $fullName);
            $name = $exploded[1];
            return strtolower($name);
        }

    }

    /**
     * Get current action name
     */
    public function getActionName()
    {
        if(null !== $this->request)
        {
            $pattern = "#::([a-zA-Z]*)Action#";
            $matches = array();
            preg_match($pattern, $this->request->get('_controller'), $matches);

            return $matches[1];
        }
    }



    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function getName()
    {
        return 'brown_twig_extension';
    }

    public function getFunctions()
    {
        return array(
            'get_controller_name' => new \Twig_Function_Method($this, 'getControllerName'),
            'get_action_name' => new \Twig_Function_Method($this, 'getActionName'),
            'get_bundle_name' => new \Twig_Function_Method($this, 'getBundleName'),
        );
    }

    /**
     * @return Twig_Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param Twig_Environment $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

}