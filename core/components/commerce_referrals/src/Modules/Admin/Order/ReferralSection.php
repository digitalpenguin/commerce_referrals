<?php
namespace DigitalPenguin\Referrals\Modules\Admin\Order;

use modmore\Commerce\Admin\Section;
use modmore\Commerce\Admin\Widgets\HtmlWidget;

class ReferralSection extends Section {
    /** @var \comOrder $order */
    protected $order;
    protected $intro;

    public function setUp()
    {
        $this->key = 'referrer_section';
        $this->priority = $this->getOption('priority');
        $this->order = $this->getOption('order');
        $this->title = 'commerce_referrals.referrer';
        $this->intro = 'commerce_referrals.referrer_order_intro';
        $this->addHTML();
        return $this;
    }

    public function getTitle()
    {
        return '<div style="margin-top:15px;">'.$this->adapter->lexicon($this->title).'</div>';
    }

    public function addHTML() {
        $html = [];
        $html[] = '<div style="margin-bottom:30px;">';
        $html[] = $this->adapter->lexicon($this->intro);
        $html[] = '</div>';
        $this->addWidget(new HtmlWidget($this->commerce, [
            'html' => implode('', $html),
        ]));
    }

    public function getReferrerData() {
        //TODO:Grab referrer info from db
    }
}