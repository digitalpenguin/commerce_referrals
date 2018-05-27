<?php
namespace DigitalPenguin\Referrals\Admin\Referral;

use modmore\Commerce\Admin\Page;
use modmore\Commerce\Admin\Sections\SimpleSection;

class ReferralPage extends Page {
    public $key = 'referrals';
    public $title = 'commerce_referrals.referrals';

    public function setUp()
    {
        $section = new SimpleSection($this->commerce, [
            'title' => $this->getTitle()
        ]);
        $section->addWidget(new ReferralGrid($this->commerce));
        $this->addSection($section);
        return $this;
    }
}