<?php
namespace DigitalPenguin\Referrals\Admin\Referrer;

use modmore\Commerce\Admin\Sections\SimpleSection;
use modmore\Commerce\Admin\Page;

class Create extends Page {
    public $key = 'referrers/create';
    public $title = 'commerce_referrals.add_referrer';

    public function setUp()
    {
        $section = new SimpleSection($this->commerce, [
            'title' => $this->title
        ]);
        $section->addWidget((new Form($this->commerce, ['id' => 0]))->setUp());
        $this->addSection($section);
        return $this;
    }

}