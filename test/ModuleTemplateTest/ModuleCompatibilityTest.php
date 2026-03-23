<?php
declare(strict_types=1);

namespace Laminas\Mvc\Controller {
    abstract class AbstractController
    {
        /** @var object */
        private $paramsPlugin;

        public function __construct($paramsPlugin = null)
        {
            $this->paramsPlugin = $paramsPlugin;
        }

        public function params()
        {
            return $this->paramsPlugin;
        }
    }
}

namespace Laminas\ServiceManager {
    interface ServiceLocatorInterface
    {
        public function get($name);
    }
}

namespace Omeka\Module {
    abstract class AbstractModule
    {
        /** @var object|null */
        private $serviceLocator;

        public function setServiceLocator($serviceLocator): void
        {
            $this->serviceLocator = $serviceLocator;
        }

        public function getServiceLocator()
        {
            return $this->serviceLocator;
        }
    }
}

namespace Omeka\Mvc\Controller\Plugin {
    class Messenger
    {
        public function addSuccess($message): void
        {
        }

        public function addWarning($message): void
        {
        }
    }
}

namespace Omeka\Stdlib {
    class Message
    {
        public function __construct(string $message)
        {
        }
    }
}

namespace ModuleTemplateTest {
    require_once dirname(__DIR__, 2) . '/Module.php';

    use Laminas\Mvc\Controller\AbstractController;
    use Laminas\ServiceManager\ServiceLocatorInterface;
    use PersonalizedHeaderFooter\Module;
    use PHPUnit\Framework\TestCase;

    class ModuleCompatibilityTest extends TestCase
    {
        public function testInstallAndConfigWorkWithoutModuleSpecificSettingsApi(): void
        {
            $settings = new GenericSettingsStub();
            $serviceLocator = new ServiceLocatorStub($settings);
            $module = new Module();
            $module->setServiceLocator($serviceLocator);

            $module->install($serviceLocator);

            $this->assertSame(
                '',
                $settings->get('personalized_header_footer_personalized_header_html')
            );
            $this->assertSame(
                '',
                $settings->get('personalized_header_footer_personalized_footer_html')
            );

            $controller = new ControllerStub(new ParamsStub([
                'personalized_header_html' => '<header>Header</header>',
                'personalized_footer_html' => '<footer>Footer</footer>',
            ]));

            $module->handleConfigForm($controller);

            $this->assertSame(
                '<header>Header</header>',
                $this->invokeModuleMethod(
                    $module,
                    'getModuleSetting',
                    [$settings, 'personalized_header_html', '']
                )
            );
            $this->assertSame(
                '<footer>Footer</footer>',
                $this->invokeModuleMethod(
                    $module,
                    'getModuleSetting',
                    [$settings, 'personalized_footer_html', '']
                )
            );

            $module->uninstall($serviceLocator);

            $this->assertNull(
                $settings->get('personalized_header_footer_personalized_header_html')
            );
            $this->assertNull(
                $settings->get('personalized_header_footer_personalized_footer_html')
            );
        }

        public function testConfigFallsBackToLegacyModuleScopedSettings(): void
        {
            $settings = new LegacyAwareSettingsStub();
            $settings->setForModule(
                Module::NAMESPACE,
                'personalized_header_html',
                '<header>Legacy</header>'
            );
            $settings->setForModule(
                Module::NAMESPACE,
                'personalized_footer_html',
                '<footer>Legacy</footer>'
            );

            $serviceLocator = new ServiceLocatorStub($settings);
            $module = new Module();
            $module->setServiceLocator($serviceLocator);

            $this->assertSame(
                '<header>Legacy</header>',
                $this->invokeModuleMethod(
                    $module,
                    'getModuleSetting',
                    [$settings, 'personalized_header_html', '']
                )
            );
            $this->assertSame(
                '<footer>Legacy</footer>',
                $this->invokeModuleMethod(
                    $module,
                    'getModuleSetting',
                    [$settings, 'personalized_footer_html', '']
                )
            );
        }

        /**
         * @param array<int, mixed> $arguments
         * @return mixed
         */
        private function invokeModuleMethod(Module $module, string $method, array $arguments)
        {
            $reflectionMethod = new \ReflectionMethod($module, $method);
            $reflectionMethod->setAccessible(true);
            return $reflectionMethod->invokeArgs($module, $arguments);
        }
    }

    class ServiceLocatorStub implements ServiceLocatorInterface
    {
        /** @var object */
        private $settings;

        public function __construct($settings)
        {
            $this->settings = $settings;
        }

        public function get($name)
        {
            if ('Omeka\Settings' !== $name) {
                throw new \InvalidArgumentException(sprintf('Unexpected service "%s".', $name));
            }

            return $this->settings;
        }
    }

    class GenericSettingsStub
    {
        /** @var array<string, mixed> */
        protected $values = [];

        public function set(string $name, $value): void
        {
            $this->values[$name] = $value;
        }

        public function get(string $name, $default = null)
        {
            return array_key_exists($name, $this->values)
                ? $this->values[$name]
                : $default;
        }

        public function delete(string $name): void
        {
            unset($this->values[$name]);
        }
    }

    class LegacyAwareSettingsStub extends GenericSettingsStub
    {
        /** @var array<string, mixed> */
        private $moduleValues = [];

        public function setForModule(string $module, string $name, $value): void
        {
            $this->moduleValues[$module . ':' . $name] = $value;
        }

        public function getForModule(string $module, string $name)
        {
            $key = $module . ':' . $name;
            return array_key_exists($key, $this->moduleValues)
                ? $this->moduleValues[$key]
                : null;
        }

        public function deleteForModule(string $module, string $name): void
        {
            unset($this->moduleValues[$module . ':' . $name]);
        }
    }

    class ParamsStub
    {
        /** @var array<string, string> */
        private $values;

        public function __construct(array $values)
        {
            $this->values = $values;
        }

        public function fromPost(): array
        {
            return $this->values;
        }
    }

    class ControllerStub extends AbstractController
    {
    }
}
