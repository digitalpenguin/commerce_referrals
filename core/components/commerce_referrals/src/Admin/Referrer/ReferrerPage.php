<?php
namespace DigitalPenguin\Referrals\Admin\Referrer;

use modmore\Commerce\Admin\Page;
use modmore\Commerce\Admin\Sections\SimpleSection;
use modmore\Commerce\Admin\Widgets\HtmlWidget;

class ReferrerPage extends Page {
    public $key = 'referrers-page';
    public $title = 'commerce_referrals.referrers';

    public function setUp()
    {
        $section = new SimpleSection($this->commerce, [
            'title' => $this->getTitle()
        ]);
        $section->addWidget(new HtmlWidget($this->commerce,[
            'html'  =>  '<div style="margin-bottom:30px;">'.$this->adapter->lexicon('commerce_referrals.referrers_desc').'</div>'
        ]));
        $section->addWidget(new ReferrerGrid($this->commerce));
        $this->addSection($section);
        return $this;
    }
}