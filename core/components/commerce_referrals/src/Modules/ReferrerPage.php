<?php
namespace DigitalPenguin\Referrals\Modules;

use modmore\Commerce\Admin\Page;
use modmore\Commerce\Admin\Sections\SimpleSection;

class ReferrerPage extends Page {
    public $key = 'referrers';
    public $title = 'commerce_referrals.referrers';

    public function setUp()
    {
        $section = new SimpleSection($this->commerce, [
            'title' => $this->getTitle()
        ]);
        $section->addWidget(new ReferrerGrid($this->commerce));
        $this->addSection($section);
        return $this;
    }
}