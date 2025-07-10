<?php
declare(strict_types=1);

namespace PersonalizedHeaderFooter\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;

class ConfigForm extends Form
{
    /**
     * Initialize the form elements.
     */
    public function init(): void
    {
        $this->add([
            'name' => 'personalized_header_html',
            'type' => Element\Textarea::class,
            'options' => [
                'label' => 'Personalized Header HTML',
                'info' => 'Enter the HTML content for the personalized header. This will be displayed at the top of public pages.',
            ],
            'attributes' => [
                'id' => 'personalized_header_html',
                'rows' => 10,
            ],
        ]);

        $this->add([
            'name' => 'personalized_footer_html',
            'type' => Element\Textarea::class,
            'options' => [
                'label' => 'Personalized Footer HTML',
                'info' => 'Enter the HTML content for the personalized footer. This will be displayed at the bottom of public pages.',
            ],
            'attributes' => [
                'id' => 'personalized_footer_html',
                'rows' => 10,
            ],
        ]);
    }
}
