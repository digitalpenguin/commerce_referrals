<?php
namespace DigitalPenguin\Referrals\Modules\Admin\Order;

use modmore\Commerce\Admin\Section;
use modmore\Commerce\Admin\Widgets\HtmlWidget;

class ReferralSection extends Section {
    /** @var \comOrder $order */
    protected $order;
    protected $intro;
    protected $referrer;

    public function setUp()
    {
        $this->key = 'referrer_section';
        $this->priority = $this->getOption('priority');
        $this->referrer = $this->getOption('referrer');
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
        $html[] = "<p>".$this->adapter->lexicon($this->intro)."</p>";
        $html[] = "<style>#referrer-info td {padding:3px 15px;}</style>";
        $html[] = "<table id='referrer-info'>"
                ."<tr><td>Name:</td><td><b>{$this->referrer['name']}</b></td></tr>"
                ."<tr><td>Contact:</td><td>{$this->referrer['contact_person']}</td></tr>"
                ."<tr><td>Email:</td><td><a href='mailto:{$this->referrer['email']}'>{$this->referrer['email']}</a></td></tr>"
                ."<tr><td>Phone:</td><td>{$this->referrer['phone']}</td></tr>"
                ."<tr><td>Website:</td><td><a href='{$this->referrer['website']}' target='_blank'>{$this->referrer['website']}</a></td></tr>"
                ."</table>";
        $html[] = '</div>';
        $this->addWidget(new HtmlWidget($this->commerce, [
            'html' => implode('', $html),
        ]));
    }



    public function getReferrerData() {
        //TODO:Grab referrer info from db
    }
}