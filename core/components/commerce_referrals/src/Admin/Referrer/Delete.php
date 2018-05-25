<?php

namespace DigitalPenguin\Referrals\Admin\Referrer;

use modmore\Commerce\Admin\Sections\SimpleSection;
use modmore\Commerce\Admin\Page;
use modmore\Commerce\Admin\Widgets\DeleteFormWidget;
use modmore\Commerce\Admin\Widgets\TextWidget;

class Delete extends Page {
    public $key = 'referrers/delete';
    public $title = 'commerce_referrals.referrer.delete';

    public function setUp()
    {
        $objectId = (int)$this->getOption('id', 0);
        $object = $this->adapter->getObject('CommerceReferralsReferrer', ['id' => $objectId]);

        $section = new SimpleSection($this->commerce, [
            'title' => $this->title
        ]);
        if ($object) {
            $widget = new DeleteFormWidget($this->commerce, [
                'title' => 'commerce_referrals.referrer.delete'
            ]);
            $widget->setRecord($object);
            $widget->setClassKey('CommerceReferralsReferrer');
            $widget->setFormAction($this->adapter->makeAdminUrl('referrers/delete', ['id' => $object->get('id')]));
            $widget->setUp();
            $section->addWidget($widget);
        }
        else {
            $section->addWidget((new TextWidget($this->commerce, ['text' => 'Referrer not found.']))->setUp());
        }
        $this->addSection($section);
        return $this;
    }
}