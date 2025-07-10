<?php
declare(strict_types=1);

namespace PersonalizedHeaderFooter;

use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\Mvc\Controller\AbstractController;
use Laminas\View\Renderer\PhpRenderer;
use Omeka\Module\AbstractModule;
use Omeka\Mvc\Controller\Plugin\Messenger;
use Omeka\Stdlib\Message;
use PersonalizedHeaderFooter\Form\ConfigForm;

/**
 * Main class for the PersonalizedHeaderFooter module.
 */
class Module extends AbstractModule
{
    public const NAMESPACE = __NAMESPACE__;

    /**
     * Retrieve the configuration array.
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Execute logic when the module is installed.
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function install(ServiceLocatorInterface $serviceLocator)
    {
        $messenger = new Messenger();
        $message = new Message("PersonalizedHeaderFooter module installed.");
        $messenger->addSuccess($message);
        // Default settings
        $settings = $serviceLocator->get('Omeka\Settings');
        $settings->setForModule(self::NAMESPACE, 'personalized_header_html', '');
        $settings->setForModule(self::NAMESPACE, 'personalized_footer_html', '');
    }
    /**
     * Execute logic when the module is uninstalled.
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function uninstall(ServiceLocatorInterface $serviceLocator)
    {
        $messenger = new Messenger();
        $message = new Message("PersonalizedHeaderFooter module uninstalled.");
        $messenger->addWarning($message);

        // Remove settings
        $settings = $serviceLocator->get('Omeka\Settings');
        $settings->deleteForModule(self::NAMESPACE, 'personalized_header_html');
        $settings->deleteForModule(self::NAMESPACE, 'personalized_footer_html');
    }
    
    /**
     * Register the file validator service and renderers.
     *
     * @param SharedEventManagerInterface $sharedEventManager
     */
    public function attachListeners(SharedEventManagerInterface $sharedEventManager): void
    {
        // Replace the default file validator with our custom one
    }
    
    /**
     * Get the configuration form for this module.
     *
     * @param PhpRenderer $renderer
     * @return string
     */
    public function getConfigForm(PhpRenderer $renderer)
    {
        $services = $this->getServiceLocator();
        $settings = $services->get('Omeka\Settings');
        
        $form = new ConfigForm;
        $form->init();
        
        $form->setData([
            'personalized_header_html' => $settings->getForModule(self::NAMESPACE, 'personalized_header_html'),
            'personalized_footer_html' => $settings->getForModule(self::NAMESPACE, 'personalized_footer_html'),
        ]);
        
        return $renderer->formCollection($form, false);
    }
    
    /**
     * Handle the configuration form submission.
     *
     * @param AbstractController $controller
     */
    public function handleConfigForm(AbstractController $controller)
    {
        $services = $this->getServiceLocator();
        $settings = $services->get('Omeka\Settings');
        
        $params = $controller->params()->fromPost();

        $settings->setForModule(self::NAMESPACE, 'personalized_header_html', $params['personalized_header_html']);
        $settings->setForModule(self::NAMESPACE, 'personalized_footer_html', $params['personalized_footer_html']);
    }
    
    // /**
}
