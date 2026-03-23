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
    private const SETTING_KEY_PREFIX = 'personalized_header_footer_';

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
        $this->setModuleSetting($settings, 'personalized_header_html', '');
        $this->setModuleSetting($settings, 'personalized_footer_html', '');
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
        $this->deleteModuleSetting($settings, 'personalized_header_html');
        $this->deleteModuleSetting($settings, 'personalized_footer_html');
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
            'personalized_header_html' => $this->getModuleSetting($settings, 'personalized_header_html', ''),
            'personalized_footer_html' => $this->getModuleSetting($settings, 'personalized_footer_html', ''),
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

        $this->setModuleSetting($settings, 'personalized_header_html', $params['personalized_header_html']);
        $this->setModuleSetting($settings, 'personalized_footer_html', $params['personalized_footer_html']);
    }
    
    /**
     * Build a unique settings key compatible with generic Omeka settings APIs.
     */
    private function getSettingKey(string $name): string
    {
        return self::SETTING_KEY_PREFIX . $name;
    }

    /**
     * Persist a module setting using the generic settings API and, when
     * available, the module-specific API for compatibility with newer Omeka
     * versions.
     *
     * @param object $settings
     * @param mixed $value
     */
    private function setModuleSetting($settings, string $name, $value): void
    {
        $settings->set($this->getSettingKey($name), $value);
        if (method_exists($settings, 'setForModule')) {
            $settings->setForModule(self::NAMESPACE, $name, $value);
        }
    }

    /**
     * Retrieve a module setting, preferring the generic key and falling back to
     * legacy module-scoped storage when available.
     *
     * @param object $settings
     * @param mixed $default
     * @return mixed
     */
    private function getModuleSetting($settings, string $name, $default = null)
    {
        $value = $settings->get($this->getSettingKey($name), null);
        if (null !== $value || !method_exists($settings, 'getForModule')) {
            return null !== $value ? $value : $default;
        }

        $value = $settings->getForModule(self::NAMESPACE, $name);
        return null !== $value ? $value : $default;
    }

    /**
     * Remove a module setting from all supported storage strategies.
     *
     * @param object $settings
     */
    private function deleteModuleSetting($settings, string $name): void
    {
        $settings->delete($this->getSettingKey($name));
        if (method_exists($settings, 'deleteForModule')) {
            $settings->deleteForModule(self::NAMESPACE, $name);
        }
    }
}
