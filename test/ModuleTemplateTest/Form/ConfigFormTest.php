<?php
declare(strict_types=1);

namespace ModuleTemplateTest\Form;

use Laminas\Form\Form;
use PersonalizedHeaderFooter\Form\ConfigForm;
use PHPUnit\Framework\TestCase;

class ConfigFormTest extends TestCase
{
    private $form;
    
    public function setUp(): void
    {
        $this->form = new ConfigForm();
    }
    
    public function testFormCanBeInstantiated(): void
    {
        $this->assertInstanceOf(ConfigForm::class, $this->form);
        $this->assertInstanceOf(Form::class, $this->form);
    }
}
